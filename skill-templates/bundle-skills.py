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

import html
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
# Enum-like helper classes whose constants are exposed as `fields` (not
# `properties`); shared by fetch_fundamental_lookup and format_fundamental_lookup.
FUND_ENUM_HELPER_TYPES = (
    "MorningstarSectorCode",
    "MorningstarIndustryGroupCode",
    "MorningstarIndustryCode",
    "MorningstarEconomySphereCode",
)
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


def fetch_fundamental_lookup(language: str) -> dict:
    """Walk `Fundamental` via the QC inspector and return every readable data
    point as `(full path from f, description)`.

    DFS from `Fundamental`, extending the path at each step (so the output is in
    tree order). A property whose type lives in `QuantConnect.Data.Fundamental.*`
    and is *not* a MultiPeriodField is a nested container — recurse into it. A
    MultiPeriodField property is a leaf that needs a period accessor, so its path
    gets the period-options suffix (e.g. `.[value 1M 2M 3M 6M 9M 12M]`). Anything
    else is a plain leaf read directly.

    Returns `{"rows": [(path, description), ...], "enums": {type_name:
    [(constant, description), ...]}}`. The enum helper types (MorningstarSectorCode,
    ...) expose their constants as `fields`, aren't reachable by a data path, and
    are returned separately for comparisons.
    """
    inspector_url = (
        "https://www.quantconnect.com/services/inspector"
        "?language={lang}&type=T:QuantConnect.Data.Fundamental.{type}"
    )
    fund_prefix = "QuantConnect.Data.Fundamental."
    # MultiPeriodField subclasses (and closed generics) are leaf wrappers — their
    # only members are period accessors, shown as the bracketed suffix below.
    leaf_base_prefix = "QuantConnect.Data.Fundamental.MultiPeriodField"
    period_suffix = (
        ".[value 1M 2M 3M 6M 9M 12M]" if language == "python"
        else ".[Value 1M 2M 3M 6M 9M 12M]"
    )

    rows: list[tuple[str, str]] = []
    visited: set[str] = set()

    def walk(type_name: str, prefix: str) -> None:
        if type_name in visited:
            return
        visited.add(type_name)
        url = inspector_url.format(lang=language, type=type_name)
        with urlopen(url) as r:
            data = json.load(r)
        for p in data["properties"]:
            name = p["property-name"]
            full = p["property-full-type-name"]
            base = p.get("property-base-type-full-name") or ""
            desc = p.get("property-description") or ""
            path = f"{prefix}.{name}"
            if base.startswith(leaf_base_prefix):
                rows.append((path + period_suffix, desc))
            elif full.startswith(fund_prefix):
                walk(full[len(fund_prefix):], path)
            else:
                rows.append((path, desc))

    walk("Fundamental", "f")

    enums: dict[str, list[tuple[str, str]]] = {}
    for type_name in FUND_ENUM_HELPER_TYPES:
        url = inspector_url.format(lang=language, type=type_name)
        with urlopen(url) as r:
            data = json.load(r)
        enums[type_name] = [
            (f["field-name"], f.get("field-description") or "")
            for f in data.get("fields", [])
        ]
    return {"rows": rows, "enums": enums}


def _table_cell(text: str) -> str:
    """Unescape HTML entities (the inspector returns `&gt;` etc.), collapse
    whitespace/newlines, and escape pipes for a Markdown table cell."""
    return " ".join(html.unescape(text).split()).replace("|", "\\|")


def format_fundamental_lookup(lookup: dict) -> str:
    """Render the data-point table (path + description), then each enum's
    constants — as a Value/Description table where the inspector documents them,
    or a plain list where it doesn't (e.g. MorningstarIndustryGroupCode)."""
    table = ["| Data point | Description |", "|---|---|"]
    table += [f"| `{path}` | {_table_cell(desc)} |" for path, desc in lookup["rows"]]

    sections = []
    for type_name, values in lookup["enums"].items():
        if all(not d.strip() for _, d in values):
            body = ", ".join(f"`{v}`" for v, _ in values)
        else:
            vt = ["| Value | Description |", "|---|---|"]
            vt += [f"| `{v}` | {_table_cell(d)} |" for v, d in values]
            body = "\n".join(vt)
        sections.append(f"#### `{type_name}`\n\n{body}")

    return (
        "\n".join(table)
        + "\n\n### Classification code constants\n\n"
        + "\n\n".join(sections)
    )


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

    fund_lookup_marker = "<!-- fundamental-lookup -->"
    # Lazily fetched per language; populated only when a skill needs the lookup.
    fundamental_lookup: dict[str, str] = {}

    # Build: split each skill into a Python and a C# tree.
    for skill in skills:
        for lang in LANGS:
            content = split_for_language(skill.content, lang)
            if fund_lookup_marker in content:
                if lang not in fundamental_lookup:
                    print(f"Fetching Fundamental property tree ({lang}) from inspector...")
                    fundamental_lookup[lang] = format_fundamental_lookup(
                        fetch_fundamental_lookup(lang)
                    )
                content = content.replace(fund_lookup_marker, fundamental_lookup[lang])
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