#!/usr/bin/env python3
"""Install SKILL.md files from this repo into ~/.claude/skills/<name>/SKILL.md.

<name> is read from the YAML frontmatter of each SKILL.md.
"""
from __future__ import annotations

import argparse
import re
import shutil
import sys
from pathlib import Path


FRONTMATTER_NAME = re.compile(r"^name:\s*(.+?)\s*$", re.MULTILINE)
SKIP_DIRS = {".git", "node_modules", ".venv", "__pycache__"}


def extract_name(skill_file: Path) -> str | None:
    text = skill_file.read_text(encoding="utf-8")
    if not text.startswith("---"):
        return None
    end = text.find("\n---", 3)
    if end == -1:
        return None
    frontmatter = text[3:end]
    match = FRONTMATTER_NAME.search(frontmatter)
    return match.group(1).strip() if match else None


def find_skill_files(root: Path) -> list[Path]:
    results: list[Path] = []
    for path in root.rglob("SKILL.md"):
        if any(part in SKIP_DIRS for part in path.relative_to(root).parts):
            continue
        results.append(path)
    return sorted(results)


def install(src: Path, dst: Path, mode: str, dry_run: bool) -> None:
    action = f"{'link' if mode == 'link' else 'copy'}: {src} -> {dst}"
    if dry_run:
        print(f"DRY-RUN: mkdir -p {dst.parent}")
        print(f"DRY-RUN: {action}")
        return
    dst.parent.mkdir(parents=True, exist_ok=True)
    if dst.exists() or dst.is_symlink():
        dst.unlink()
    if mode == "link":
        dst.symlink_to(src)
    else:
        shutil.copy2(src, dst)


def main() -> int:
    parser = argparse.ArgumentParser(description=__doc__)
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
            print(f"SKIP: no 'name:' in frontmatter — {rel}", file=sys.stderr)
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
    sys.exit(main())
