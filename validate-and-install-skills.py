#!/usr/bin/env python3
"""Validate SKILL.md frontmatter and install into ~/.claude/skills/<name>/SKILL.md.

Frontmatter must be a YAML mapping with at least `name` and `description`.
Pass `-v` / `--validate-only` to validate without installing.
"""
from __future__ import annotations

from argparse import ArgumentParser
from io import StringIO
from os import environ
from pathlib import Path
from re import compile
from shutil import copy2
from sys import exit, stderr

import yaml


REQUIRED_KEYS = {"name", "description"}
# Only scan the numbered top-level chapters where SKILL.md files may live.
CHAPTER_DIR = compile(r"^0[1-6] ")
IN_ACTIONS = environ.get("GITHUB_ACTIONS") == "true"


class FrontmatterError(ValueError):
    """Raised when a SKILL.md's YAML frontmatter is missing or invalid."""


def find_skill_files(root: Path) -> list[Path]:
    return sorted(
        skill
        for child in root.iterdir()
        if child.is_dir() and CHAPTER_DIR.match(child.name)
        for skill in child.rglob("SKILL.md")
    )


def load_frontmatter(skill_file: Path) -> dict:
    parts = skill_file.read_text(encoding="utf-8").split("---\n", 2)
    if len(parts) < 3 or parts[0]:
        raise FrontmatterError("missing or unterminated YAML frontmatter")
    stream = StringIO(parts[1])
    stream.name = str(skill_file)
    try:
        data = yaml.safe_load(stream)
    except yaml.YAMLError as e:
        raise FrontmatterError(f"invalid YAML:\n{e}") from e
    if not isinstance(data, dict):
        raise FrontmatterError("frontmatter must be a mapping")
    missing = REQUIRED_KEYS - data.keys()
    if missing:
        raise FrontmatterError(f"frontmatter missing: {', '.join(sorted(missing))}")
    return data


def report_error(path: Path, err: str) -> None:
    print(f"\n{path}:\n{err}", file=stderr)
    if IN_ACTIONS:
        print(f"::error file={path}::{err.splitlines()[0]}")


def install(src: Path, dst: Path, *, link: bool, dry_run: bool) -> None:
    action = f"{'link' if link else 'copy'}: {src} -> {dst}"
    if dry_run:
        print(f"DRY-RUN: mkdir -p {dst.parent}")
        print(f"DRY-RUN: {action}")
        return
    dst.parent.mkdir(parents=True, exist_ok=True)
    dst.unlink(missing_ok=True)
    if link:
        dst.symlink_to(src)
    else:
        copy2(src, dst)


def main() -> int:
    parser = ArgumentParser(description=__doc__)
    parser.add_argument(
        "--link",
        action="store_true",
        help="Symlink instead of copy (needs admin/Developer Mode on Windows).",
    )
    parser.add_argument("-n", "--dry-run", action="store_true")
    parser.add_argument(
        "-v",
        "--validate-only",
        action="store_true",
        help="Validate SKILL.md frontmatter without installing.",
    )
    args = parser.parse_args()

    repo_root = Path(__file__).resolve().parent
    skill_files = find_skill_files(repo_root)

    validated: list[tuple[Path, dict]] = []
    failed = False
    for skill_file in skill_files:
        rel = skill_file.relative_to(repo_root)
        try:
            data = load_frontmatter(skill_file)
        except FrontmatterError as e:
            report_error(rel, str(e))
            failed = True
        else:
            validated.append((skill_file, data))

    if failed:
        return 1

    if args.validate_only:
        print(f"Validated {len(skill_files)} SKILL.md file(s), all OK.")
        return 0

    dest_root = Path.home() / ".claude" / "skills"
    for skill_file, data in validated:
        rel = skill_file.relative_to(repo_root)
        target = dest_root / data["name"] / "SKILL.md"
        print(f"Installing '{data['name']}' from {rel}")
        install(skill_file, target, link=args.link, dry_run=args.dry_run)

    print()
    print(f"Done. Installed: {len(validated)}")
    print(f"Dest: {dest_root}")
    return 0


if __name__ == "__main__":
    exit(main())
