#!/usr/bin/env python3
"""Validate, split, zip, and (optionally) install SKILL.md files.

The script lives in `skill-templates/` and scans every SKILL.md under that
directory. Build outputs (`skills/` tree and `skills.zip`) are written at
the parent (repo root) so they never intermingle with the source SKILL.md
files inside `skill-templates/`.

Default run: validate + split + zip. No install.

Every SKILL.md must be dual-language. Validation fails if the file has no
language markers — add at least one of:
  - py`X`cs`Y` inline markers (or cs-first cs`Y`py`X`),
  - <!-- python-only -->...<!-- /python-only --> prose blocks
    (or <!-- csharp-only -->...),
  - both ```python and ```csharp fenced code blocks.

For each SKILL.md found:
  1. Validate YAML frontmatter (name + description required) and confirm the
     file is dual-language.
  2. Split into language-specific trees, preserving the path relative to
     `skill-templates/`:
         <repo>/skills/python/<rel-path>/SKILL.md
         <repo>/skills/csharp/<rel-path>/SKILL.md
     Frontmatter `name` is left untouched.
  3. Bundle the entire `<repo>/skills/` tree into `<repo>/skills.zip`.
  4. With `--py` or `--cs`: also install the matching tree flat into
     ~/.claude/skills/<name>/SKILL.md so Claude can discover the skills.

Flags:
  -n / --dry-run         Print actions without touching the filesystem.
  -v / --validate-only   Validate frontmatter, then stop.
  --py                   Install the Python versions after build.
  --cs                   Install the C# versions after build.
"""
from __future__ import annotations

import json
import re
from argparse import ArgumentParser
from dataclasses import dataclass
from os import environ
from pathlib import Path
from shutil import copy2, rmtree
from sys import exit, stderr
from urllib.request import urlopen
from zipfile import ZIP_DEFLATED, ZipFile

import yaml


REQUIRED_KEYS = {"name", "description"}
DESCRIPTION_MIN = 30
DESCRIPTION_MAX = 1024
LANGS = ("python", "csharp")
IN_ACTIONS = environ.get("GITHUB_ACTIONS") == "true"
# Dual-language inline markers (whitespace between halves tolerated).
# py-first: py`X`cs`Y`     groups: (1)=python (2)=csharp
# cs-first: cs`Y`py`X`     groups: (1)=csharp (2)=python
PY_CS_INLINE = re.compile(r"py`([^`]*)`\s*cs`([^`]*)`")
CS_PY_INLINE = re.compile(r"cs`([^`]*)`\s*py`([^`]*)`")
# Language-only prose blocks (HTML comments, invisible in rendered Markdown).
# Markers must sit on their own lines. The trailing `\n?` swallows the blank
# line a removed block would otherwise leave behind.
PY_ONLY_BLOCK = re.compile(
    r"<!--\s*python-only\s*-->\n?(.*?)<!--\s*/python-only\s*-->\n?", re.DOTALL
)
CS_ONLY_BLOCK = re.compile(
    r"<!--\s*csharp-only\s*-->\n?(.*?)<!--\s*/csharp-only\s*-->\n?", re.DOTALL
)
# Per-type attribute-table marker, e.g. `<!-- fundamental-attributes: BalanceSheet -->`.
# Replaced (per language) with a Markdown table of that Fundamental type's
# attributes + descriptions, fetched from the QC inspector. Used by the
# `fundamental-data-point-attributes-*` skills.
FUND_ATTR_MARKER = re.compile(r"<!--\s*fundamental-attributes:\s*([A-Za-z0-9_]+)\s*-->")
# Companion marker: `<!-- fundamental-subgroups: TYPE -->` lists TYPE's nested
# Fundamental containers (e.g. FinancialStatements -> income_statement / balance_sheet
# / cash_flow_statement) as pointers to their own skills, since their fields can't
# fit in a parent cell.
FUND_SUBGROUPS_MARKER = re.compile(r"<!--\s*fundamental-subgroups:\s*([A-Za-z0-9_]+)\s*-->")
INSPECTOR_URL = (
    "https://www.quantconnect.com/services/inspector"
    "?language={lang}&type=T:QuantConnect.Data.Fundamental.{type}"
)
FUND_PREFIX = "QuantConnect.Data.Fundamental."
MULTI_PERIOD_BASE = FUND_PREFIX + "MultiPeriodField"
ATTR_SKILL_PREFIX = "fundamental-data-point-attributes-"


class FrontmatterError(ValueError):
    """Raised when a SKILL.md's YAML frontmatter is missing or invalid."""


@dataclass(frozen=True)
class Skill:
    rel: Path        # source path relative to templates root
    rel_dir: Path    # source parent relative to templates root
    content: str     # raw source content (utf-8)
    name: str        # frontmatter `name`


def parse_frontmatter(content: str) -> dict:
    """Parse YAML frontmatter from SKILL.md content; validate required keys."""
    parts = content.split("---\n", 2)
    if len(parts) < 3 or parts[0]:
        raise FrontmatterError("missing or unterminated YAML frontmatter")
    try:
        data = yaml.safe_load(parts[1])
    except yaml.YAMLError as e:
        raise FrontmatterError(f"invalid YAML:\n{e}") from e
    if not isinstance(data, dict):
        raise FrontmatterError("frontmatter must be a mapping")
    missing = REQUIRED_KEYS - data.keys()
    if missing:
        raise FrontmatterError(f"frontmatter missing: {', '.join(sorted(missing))}")
    desc_len = len(data["description"])
    if not DESCRIPTION_MIN <= desc_len <= DESCRIPTION_MAX:
        raise FrontmatterError(
            f"description is {desc_len} characters; must be between "
            f"{DESCRIPTION_MIN} and {DESCRIPTION_MAX}"
        )
    return data


def is_dual_language(content: str) -> bool:
    return (
        bool(PY_CS_INLINE.search(content))
        or bool(CS_PY_INLINE.search(content))
        or bool(PY_ONLY_BLOCK.search(content))
        or bool(CS_ONLY_BLOCK.search(content))
        or ("```python\n" in content and "```csharp\n" in content)
    )


def load_skill(skill_file: Path, templates_root: Path) -> Skill:
    content = skill_file.read_text(encoding="utf-8")
    name = parse_frontmatter(content)["name"]
    # The Claude skill loader expects the SKILL.md's parent folder to match the
    # frontmatter `name` exactly. Enforce that at build time so we never ship
    # a skill that the runtime will reject.
    parent_dir_name = skill_file.parent.name
    if parent_dir_name != name:
        raise FrontmatterError(
            f"folder name '{parent_dir_name}' does not match skill name '{name}'. "
            f"Rename the folder to '{name}' (or change the frontmatter `name` to match)."
        )
    if not is_dual_language(content):
        raise FrontmatterError(
            "skill is not dual-language. Add at least one of:\n"
            "  - py`X`cs`Y` inline markers (or cs-first cs`Y`py`X`),\n"
            "  - <!-- python-only -->...<!-- /python-only --> prose blocks\n"
            "    (or <!-- csharp-only -->...),\n"
            "  - both ```python and ```csharp fenced code blocks."
        )
    return Skill(
        rel=skill_file.relative_to(templates_root),
        rel_dir=skill_file.parent.relative_to(templates_root),
        content=content,
        name=name,
    )


def strip_code_blocks(content: str, lang: str) -> str:
    """Remove fenced ```<lang> ... ``` blocks, plus one trailing blank line each."""
    fence = f"```{lang}"
    lines = content.split("\n")
    out: list[str] = []
    i = 0
    n = len(lines)
    while i < n:
        if lines[i].startswith(fence):
            i += 1
            while i < n and not lines[i].startswith("```"):
                i += 1
            i += 1  # consume closing ```
            if i < n and lines[i] == "":
                i += 1  # absorb the blank line that followed the block
        else:
            out.append(lines[i])
            i += 1
    return "\n".join(out)


def _skill_slug(prop_name: str) -> str:
    """Map a nested-container accessor to its skill name. Skill names use kebab-case
    (lowercase letters, numbers, hyphens). Python names are snake_case; C# names are
    PascalCase. Both convert to kebab-case (income_statement / IncomeStatement ->
    income-statement)."""
    if "_" in prop_name:
        kebab = prop_name.replace("_", "-")
    elif prop_name.islower():
        kebab = prop_name
    else:
        kebab = re.sub(r"(?<!^)(?=[A-Z])", "-", prop_name).lower()
    return ATTR_SKILL_PREFIX + kebab


def fetch_type_members(type_name: str, language: str) -> tuple[str, list[tuple], list[tuple]]:
    """Fetch one Fundamental type's members from the inspector, with descriptions.

    Returns `(kind, rows, subgroups)`:
      - kind `"properties"`: `rows` are `(name, type, description)` leaf
        attributes — `type` is `"MultiPeriodField"` for period wrappers (their
        `short-type-name` is a per-property alias, not useful), else the
        property's short type name. `subgroups` are `(name, skill_slug)` for
        properties whose type is itself a Fundamental container (e.g.
        IncomeStatement) — those are pointers, not leaves.
      - kind `"fields"`: enum-helper types (e.g. MorningstarSectorCode) have no
        properties; their constants live in `fields`. `rows` are
        `(name, description)`; `subgroups` is empty.
    """
    url = INSPECTOR_URL.format(lang=language, type=type_name)
    with urlopen(url) as r:
        data = json.load(r)
    props = data.get("properties") or []
    if props:
        rows, subgroups = [], []
        for p in props:
            base = p.get("property-base-type-full-name") or ""
            full = p.get("property-full-type-name") or ""
            if base.startswith(MULTI_PERIOD_BASE):
                rows.append((p["property-name"], "MultiPeriodField", p.get("property-description") or ""))
            elif full.startswith(FUND_PREFIX):
                subgroups.append((p["property-name"], _skill_slug(p["property-name"])))
            else:
                rows.append((p["property-name"], p.get("property-short-type-name") or "",
                             p.get("property-description") or ""))
        return "properties", rows, subgroups
    fields = data.get("fields") or []
    rows = [(f.get("field-name"), f.get("field-description") or "") for f in fields]
    return "fields", rows, []


def _table_cell(text: str) -> str:
    """Collapse whitespace/newlines and escape pipes so a description sits in one cell."""
    return " ".join(text.split()).replace("|", "\\|")


def render_subgroups(subgroups: list[tuple]) -> str:
    """Render nested-container properties as pointers to their dedicated skills."""
    return "\n".join(f"- `{name}` — see the **{slug}** skill" for name, slug in subgroups)


def render_attribute_table(kind: str, rows: list[tuple]) -> str:
    """Render fetch_type_members output as a Markdown table.

    `fields` (enum constants) → Value | Description (type is uniform by nature).
    `properties` → Attribute | Type | Description, but the Type column is
    dropped when every property shares one type (e.g. balance-sheet fields are
    all MultiPeriodField) — there it's noise, and the skill's prose states it
    once. Mixed nodes (company_reference, asset_classification) keep it.
    """
    if kind == "fields":
        if all(not desc.strip() for _, desc in rows):
            # Morningstar documents no per-constant description for some enums
            # (e.g. MorningstarIndustryGroupCode) — list the values rather than
            # render a table full of empty cells.
            return ", ".join(f"`{name}`" for name, _ in rows)
        out = ["| Value | Description |", "|---|---|"]
        out += [f"| `{name}` | {_table_cell(desc)} |" for name, desc in rows]
        return "\n".join(out)
    if len({typ for _, typ, _ in rows}) <= 1:
        out = ["| Attribute | Description |", "|---|---|"]
        out += [f"| `{name}` | {_table_cell(desc)} |" for name, _, desc in rows]
    else:
        out = ["| Attribute | Type | Description |", "|---|---|---|"]
        out += [f"| `{name}` | {typ} | {_table_cell(desc)} |" for name, typ, desc in rows]
    return "\n".join(out)


def expand_attribute_markers(content: str, language: str, cache: dict) -> str:
    """Expand `<!-- fundamental-attributes: TYPE -->` (leaf table) and
    `<!-- fundamental-subgroups: TYPE -->` (nested-container pointers) per language."""
    def members(type_name: str) -> tuple:
        key = (type_name, language)
        if key not in cache:
            print(f"Fetching {type_name} members ({language}) from inspector...")
            cache[key] = fetch_type_members(type_name, language)
        return cache[key]

    def repl_table(match: re.Match) -> str:
        kind, rows, _ = members(match.group(1))
        return render_attribute_table(kind, rows)

    def repl_subgroups(match: re.Match) -> str:
        _, _, subgroups = members(match.group(1))
        return render_subgroups(subgroups)

    content = FUND_ATTR_MARKER.sub(repl_table, content)
    content = FUND_SUBGROUPS_MARKER.sub(repl_subgroups, content)
    return content


def split_for_language(content: str, lang: str) -> str:
    """Resolve py/cs inline markers (either order), keep prose blocks for `lang`,
    drop prose blocks for the other language, strip the other language's code blocks."""
    if lang == "python":
        # py`X`cs`Y` → `X` (group 1); cs`Y`py`X` → `X` (group 2)
        content = PY_CS_INLINE.sub(r"`\1`", content)
        content = CS_PY_INLINE.sub(r"`\2`", content)
        content = PY_ONLY_BLOCK.sub(r"\1", content)  # keep
        content = CS_ONLY_BLOCK.sub("", content)     # drop
        return strip_code_blocks(content, "csharp")
    if lang == "csharp":
        # py`X`cs`Y` → `Y` (group 2); cs`Y`py`X` → `Y` (group 1)
        content = PY_CS_INLINE.sub(r"`\2`", content)
        content = CS_PY_INLINE.sub(r"`\1`", content)
        content = PY_ONLY_BLOCK.sub("", content)     # drop
        content = CS_ONLY_BLOCK.sub(r"\1", content)  # keep
        return strip_code_blocks(content, "python")
    raise ValueError(f"unknown language: {lang}")


def report_error(path: Path, err: str) -> None:
    print(f"\n{path}:\n{err}", file=stderr)
    if IN_ACTIONS:
        print(f"::error file={path}::{err.splitlines()[0]}")


def write_file(path: Path, content: str, *, dry_run: bool) -> None:
    if dry_run:
        print(f"DRY-RUN: write {path} ({len(content)} bytes)")
        return
    path.parent.mkdir(parents=True, exist_ok=True)
    # write_bytes skips Windows' \n -> \r\n translation, keeping zips reproducible.
    path.write_bytes(content.encode("utf-8"))


def install(src: Path, dst: Path, *, dry_run: bool) -> None:
    if dry_run:
        print(f"DRY-RUN: copy {src} -> {dst}")
        return
    dst.parent.mkdir(parents=True, exist_ok=True)
    copy2(src, dst)


def remove_tree(path: Path, *, dry_run: bool) -> bool:
    """Remove `path` if it exists. Returns True iff something was (or would be) removed."""
    if not path.exists():
        return False
    if dry_run:
        print(f"DRY-RUN: rmtree {path}")
    else:
        rmtree(path)
    return True


def main() -> int:
    parser = ArgumentParser(description=__doc__)
    parser.add_argument(
        "-n", "--dry-run", action="store_true",
        help="Print actions without touching the filesystem.",
    )
    parser.add_argument(
        "-v", "--validate-only", action="store_true",
        help="Validate SKILL.md frontmatter, then stop.",
    )
    install_group = parser.add_mutually_exclusive_group()
    install_group.add_argument(
        "--py", dest="install_lang", action="store_const", const="python",
        help="After build, install the Python versions to ~/.claude/skills/.",
    )
    install_group.add_argument(
        "--cs", dest="install_lang", action="store_const", const="csharp",
        help="After build, install the C# versions to ~/.claude/skills/.",
    )
    args = parser.parse_args()

    templates_root = Path(__file__).resolve().parent

    skills: list[Skill] = []
    failed = False
    for skill_file in sorted(templates_root.rglob("SKILL.md")):
        try:
            skills.append(load_skill(skill_file, templates_root))
        except FrontmatterError as e:
            report_error(skill_file.relative_to(templates_root), str(e))
            failed = True

    # Two SKILLs sharing a frontmatter `name` would silently overwrite each
    # other on install — flag the collision instead.
    seen: dict[str, Path] = {}
    for s in skills:
        if s.name in seen:
            report_error(s.rel, f"duplicate skill name '{s.name}' (also in {seen[s.name]})")
            failed = True
        else:
            seen[s.name] = s.rel

    if failed:
        return 1

    if args.validate_only:
        print(f"Validated {len(skills)} SKILL.md file(s), all OK.")
        return 0

    repo_root = templates_root.parent
    skills_root = repo_root / "skills"
    install_root = Path.home() / ".claude" / "skills"

    # Wipe the build dir so deletions in the source propagate.
    remove_tree(skills_root, dry_run=args.dry_run)

    # Lazily fetched per (type, language); shared across skills that table the same type.
    attr_cache: dict[tuple[str, str], str] = {}

    # Build: split each skill into a Python and a C# tree.
    for skill in skills:
        for lang in LANGS:
            content = split_for_language(skill.content, lang)
            content = expand_attribute_markers(content, lang, attr_cache)
            write_file(
                skills_root / lang / skill.rel_dir / "SKILL.md",
                content,
                dry_run=args.dry_run,
            )
        print(f"Built '{skill.name}' from {skill.rel}")

    if args.install_lang:
        for skill in skills:
            install(
                skills_root / args.install_lang / skill.rel_dir / "SKILL.md",
                install_root / skill.name / "SKILL.md",
                dry_run=args.dry_run,
            )

    print()
    print(f"Done. Built {len(skills)} skill(s).")
    print(f"Build root: {skills_root}")
    if args.install_lang:
        print(f"Installed {len(skills)} {args.install_lang} skill(s) to {install_root}")
    return 0


if __name__ == "__main__":
    exit(main())