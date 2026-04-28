#!/usr/bin/env python3
"""Validate, split, zip, and (optionally) install SKILL.md files.

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
  2. Split into language-specific trees:
         <repo>/skills/python/<chapter-path>/SKILL.md
         <repo>/skills/csharp/<chapter-path>/SKILL.md
     Frontmatter `name` is left untouched.
  3. Bundle the entire <repo>/skills/ tree into a single <repo>/skills.zip.
  4. With `--py` or `--cs`: also install the matching tree flat into
     ~/.claude/skills/<name>/SKILL.md so Claude can discover the skills.

Flags:
  -n / --dry-run         Print actions without touching the filesystem.
  -v / --validate-only   Validate frontmatter, then stop.
  --py                   Install the Python versions after build.
  --cs                   Install the C# versions after build.
"""
from __future__ import annotations

import re
from argparse import ArgumentParser
from dataclasses import dataclass
from os import environ
from pathlib import Path
from shutil import copy2, rmtree
from sys import exit, stderr
from zipfile import ZIP_DEFLATED, ZipFile

import yaml


REQUIRED_KEYS = {"name", "description"}
DESCRIPTION_MIN = 30
DESCRIPTION_MAX = 1024
LANGS = ("python", "csharp")
# Only scan the numbered top-level chapters where SKILL.md files may live.
CHAPTER_DIR = re.compile(r"^0[1-6] ")
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


class FrontmatterError(ValueError):
    """Raised when a SKILL.md's YAML frontmatter is missing or invalid."""


@dataclass(frozen=True)
class Skill:
    rel: Path           # source path relative to repo root
    rel_chapter: Path   # source parent relative to repo root
    content: str        # raw source content (utf-8)
    name: str           # frontmatter `name`


def find_skill_files(root: Path) -> list[Path]:
    return sorted(
        skill
        for child in root.iterdir()
        if child.is_dir() and CHAPTER_DIR.match(child.name)
        for skill in child.rglob("SKILL.md")
    )


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


def load_skill(skill_file: Path, repo_root: Path) -> Skill:
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
        rel=skill_file.relative_to(repo_root),
        rel_chapter=skill_file.parent.relative_to(repo_root),
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


def zip_tree(src_dir: Path, zip_path: Path, *, dry_run: bool) -> None:
    """Zip every file under src_dir into zip_path, with src_dir.name as archive root."""
    if dry_run:
        print(f"DRY-RUN: zip {src_dir} -> {zip_path}")
        return
    zip_path.parent.mkdir(parents=True, exist_ok=True)
    zip_path.unlink(missing_ok=True)
    root = src_dir.name
    with ZipFile(zip_path, "w", ZIP_DEFLATED) as zf:
        for f in sorted(src_dir.rglob("*")):
            if f.is_file():
                zf.write(f, arcname=f"{root}/{f.relative_to(src_dir).as_posix()}")


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

    repo_root = Path(__file__).resolve().parent
    skill_files = find_skill_files(repo_root)

    skills: list[Skill] = []
    failed = False
    for skill_file in skill_files:
        try:
            skills.append(load_skill(skill_file, repo_root))
        except FrontmatterError as e:
            report_error(skill_file.relative_to(repo_root), str(e))
            failed = True

    if failed:
        return 1

    if args.validate_only:
        print(f"Validated {len(skills)} SKILL.md file(s), all OK.")
        return 0

    skills_root = repo_root / "skills"
    bundle_zip = repo_root / "skills.zip"
    install_root = Path.home() / ".claude" / "skills"

    # Wipe the build dir so deletions in the source propagate.
    remove_tree(skills_root, dry_run=args.dry_run)

    # Build: split each skill into a Python and a C# tree.
    for skill in skills:
        for lang in LANGS:
            write_file(
                skills_root / lang / skill.rel_chapter / "SKILL.md",
                split_for_language(skill.content, lang),
                dry_run=args.dry_run,
            )
        print(f"Built '{skill.name}' from {skill.rel}")

    if skills:
        zip_tree(skills_root, bundle_zip, dry_run=args.dry_run)

    if args.install_lang:
        for skill in skills:
            install(
                skills_root / args.install_lang / skill.rel_chapter / "SKILL.md",
                install_root / skill.name / "SKILL.md",
                dry_run=args.dry_run,
            )

    print()
    print(f"Done. Built {len(skills)} skill(s).")
    print(f"Build root: {skills_root}")
    print(f"Bundle:     {bundle_zip}")
    if args.install_lang:
        print(f"Installed {len(skills)} {args.install_lang} skill(s) to {install_root}")
    return 0


if __name__ == "__main__":
    exit(main())