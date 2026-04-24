#!/usr/bin/env python3
"""Install SKILL.md files from this repo into ~/.claude/skills/<name>/SKILL.md.

<name> is read from the YAML frontmatter of each SKILL.md.
"""
from __future__ import annotations

from argparse import ArgumentParser
from pathlib import Path
from re import MULTILINE, compile
from shutil import copy2
from sys import exit, stderr


FRONTMATTER_NAME = compile(r"^name:\s*(.+?)\s*$", MULTILINE)
# Only scan the numbered top-level chapters where SKILL.md files may live.
CHAPTER_DIR = compile(r"^0[1-6] ")
# Frontmatter lives at the top of the file; 2KB is plenty and avoids reading
# full SKILL.md bodies (some are hundreds of lines).
HEAD_BYTES = 2048


def extract_name(skill_file: Path) -> str | None:
    with skill_file.open(encoding="utf-8") as f:
        head = f.read(HEAD_BYTES)
    if not head.startswith("---"):
        return None
    end = head.find("\n---", 3)
    if end == -1:
        return None
    match = FRONTMATTER_NAME.search(head[3:end])
    return match.group(1).strip() if match else None


def find_skill_files(root: Path) -> list[Path]:
    results: list[Path] = []
    for child in root.iterdir():
        if child.is_dir() and CHAPTER_DIR.match(child.name):
            results.extend(child.rglob("SKILL.md"))
    return sorted(results)


def install(src: Path, dst: Path, mode: str, dry_run: bool) -> None:
    action = f"{'link' if mode == 'link' else 'copy'}: {src} -> {dst}"
    if dry_run:
        print(f"DRY-RUN: mkdir -p {dst.parent}")
        print(f"DRY-RUN: {action}")
        return
    dst.parent.mkdir(parents=True, exist_ok=True)
    dst.unlink(missing_ok=True)
    if mode == "link":
        dst.symlink_to(src)
    else:
        copy2(src, dst)


def main() -> int:
    parser = ArgumentParser(description=__doc__)
    parser.add_argument(
        "--link",
        dest="mode",
        action="store_const",
        const="link",
        default="copy",
        help="Symlink instead of copy (needs admin/Developer Mode on Windows).",
    )
    parser.add_argument("-n", "--dry-run", action="store_true")
    args = parser.parse_args()

    repo_root = Path(__file__).resolve().parent
    dest_root = Path.home() / ".claude" / "skills"

    installed = skipped = 0
    for skill_file in find_skill_files(repo_root):
        rel = skill_file.relative_to(repo_root)
        name = extract_name(skill_file)
        if not name:
            print(f"SKIP: no 'name:' in frontmatter — {rel}", file=stderr)
            skipped += 1
            continue

        target = dest_root / name / "SKILL.md"
        print(f"Installing '{name}' from {rel}")
        install(skill_file, target, args.mode, args.dry_run)
        installed += 1

    print()
    print(f"Done. Installed: {installed}, Skipped: {skipped}")
    print(f"Dest: {dest_root}")
    return 0


if __name__ == "__main__":
    exit(main())
