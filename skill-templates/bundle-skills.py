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


def fetch_fundamental_lookup(language: str) -> dict[str, list[str]]:
    """Walk Fundamental and its non-leaf sub-types via the QC inspector.

    BFS from `Fundamental`. For each property whose type lives in
    `QuantConnect.Data.Fundamental.*` and whose base is *not* MultiPeriodField,
    queue it for expansion. MultiPeriodField wrappers are listed by name in the
    parent's properties but never expanded — their only members are period
    accessors that the skill already documents.

    Each entry is keyed by the property-accessor name from its parent
    (snake_case in Python, PascalCase in C#) — e.g. CompanyReference is listed
    under `company_reference` in Python because that's how callers reach it
    (`f.company_reference`). The root uses the lowercased type name in Python.
    MultiPeriodField wrappers are suffixed with `*` so the skill can flag which
    properties need a period accessor.
    """
    inspector_url = (
        "https://www.quantconnect.com/services/inspector"
        "?language={lang}&type=T:QuantConnect.Data.Fundamental.{type}"
    )
    fund_prefix = "QuantConnect.Data.Fundamental."
    # MultiPeriodField subclasses (and closed generics) are leaf wrappers —
    # don't recurse into them; their only members are period accessors.
    leaf_base_prefix = "QuantConnect.Data.Fundamental.MultiPeriodField"
    root_type = "Fundamental"

    root_display = root_type.lower() if language == "python" else root_type
    out: dict[str, list[str]] = {}
    visited: set[str] = set()
    # Queue items are (type_name, display_name) — display follows the parent's accessor casing.
    queue: list[tuple[str, str]] = [(root_type, root_display)]
    while queue:
        type_name, display = queue.pop(0)
        if type_name in visited:
            continue
        visited.add(type_name)
        url = inspector_url.format(lang=language, type=type_name)
        with urlopen(url) as r:
            data = json.load(r)
        names: list[str] = []
        for p in data["properties"]:
            prop_name = p["property-name"]
            full = p["property-full-type-name"]
            base = p.get("property-base-type-full-name") or ""
            if base.startswith(leaf_base_prefix):
                names.append(prop_name + "*")
            else:
                names.append(prop_name)
                if full.startswith(fund_prefix):
                    short = full[len(fund_prefix):]
                    if short not in visited:
                        queue.append((short, prop_name))
        out[display] = names
    # Append the enum-helper classes — their named integer constants live in
    # `fields`, not `properties`. They're not reachable via tree-walking and
    # appear at the bottom of the rendered lookup.
    for type_name in FUND_ENUM_HELPER_TYPES:
        url = inspector_url.format(lang=language, type=type_name)
        with urlopen(url) as r:
            data = json.load(r)
        out[type_name] = [f["field-name"] for f in data.get("fields", [])]
    return out


def format_fundamental_lookup(lookup: dict[str, list[str]]) -> str:
    """Render in three tiers: root, chainable types (alpha), enum helpers (alpha).

    Sorting is case-insensitive so PascalCase headings interleave naturally
    with snake_case ones; the root is pinned to the top because every other
    path chains from it.
    """
    root = next(iter(lookup))
    helpers = [k for k in lookup if k in FUND_ENUM_HELPER_TYPES]
    chainable = [k for k in lookup if k != root and k not in FUND_ENUM_HELPER_TYPES]
    ordered = [root] + sorted(chainable, key=str.lower) + sorted(helpers, key=str.lower)
    return "\n".join(f"- **{k}**: {', '.join(lookup[k])}" for k in ordered)


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