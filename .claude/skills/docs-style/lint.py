"""Flag style-guide violations in documentation .html/.php fragments.

Usage: python lint.py <file-or-folder> [...]
Avoid-term lists are read from the Style Guide tables, so edit those, not this file.
"""
import html
import json
import re
import sys
from pathlib import Path

GUIDE = Path(__file__).resolve().parents[3] / "Style Guide"


def avoid_terms():
    """(term, replacement, source) from the first two columns of the guide's tables."""
    terms = []
    for name in ["style-guide-concise-terms.md", "style-guide-terminology-global-audience.md"]:
        for line in (GUIDE / name).read_text(encoding="utf-8").splitlines():
            cells = [c.strip() for c in line.strip().strip("|").split("|")]
            if len(cells) < 2 or set(cells[0]) <= set("- ") or cells[0] in ("Avoid", "Metaphor", "Idiom or colloquialism"):
                continue
            if "_" in cells[0]:  # placeholder rows such as "click the _buttonName_ button"
                continue
            for term in cells[0].split(","):
                term = re.sub(r"\(.*?\)", "", term).strip()
                if term:
                    terms.append((term, cells[1], "jargon" if name.endswith("audience.md") and len(cells) > 2 else "term"))
    return terms


CHECKS = [
    ("future", re.compile(r"\bwill\b", re.I), "use present tense unless the action is later from the reader's view"),
    ("there-is", re.compile(r"\bthere (is|are|was|were)\b", re.I), "name the real subject"),
    ("it-is", re.compile(r"(^|[.:]\s+)(it is|it's)\b", re.I), "name the real subject"),
    ("this-alone", re.compile(r"(^|[.:]\s+)this (is|means|makes|causes|lets|allows|gives|keeps)\b", re.I), "follow 'this' with a noun"),
    ("passive", re.compile(r"\b(is|are|was|were|be|been|being) \w+ed by\b", re.I), "prefer active voice"),
    ("semicolon", re.compile(r";"), "split into two sentences"),
    ("exclamation", re.compile(r"!"), "drop the exclamation mark"),
    ("slash", re.compile(r"\b[a-z]+/[a-z]+\b"), "rewrite the choice without a slash"),
]


def prose_lines(path):
    """Yield (line_no, plain text) for prose, skipping code blocks and PHP."""
    text = path.read_text(encoding="utf-8")
    if path.suffix == ".php":
        for no, line in enumerate(text.splitlines(), 1):
            m = re.search(r'"(name|text)":\s*("(?:[^"\\]|\\.)*")', line)
            if m:
                yield no, json.loads(m.group(2))
        return
    in_pre = False
    for no, line in enumerate(text.splitlines(), 1):
        if "<pre" in line:
            in_pre = True
        if in_pre:
            in_pre = "</pre>" not in line
            continue
        yield no, line


def plain(line):
    line = re.sub(r"<code[^>]*>.*?</code>", "CODE", line)
    line = re.sub(r"<[^>]+>", "", line)
    return html.unescape(line).strip()


def lint(path, terms):
    issues = []
    for no, raw in prose_lines(path):
        text = plain(raw)
        if not text:
            continue
        for term, use, kind in terms:
            if re.search(rf"\b{re.escape(term)}\b", text, re.I):
                issues.append((no, kind, f"'{term}' -> '{use}'"))
        for rule, rx, hint in CHECKS:
            if rx.search(text):
                issues.append((no, rule, hint))
        for sentence in re.split(r"(?<=[.?])\s+", text):
            words = len(sentence.split())
            if words > 25:
                issues.append((no, "long", f"{words} words: '{sentence[:60]}...'"))
    return issues


def main():
    terms = avoid_terms()
    files = []
    for arg in sys.argv[1:]:
        p = Path(arg)
        files += sorted(f for f in p.rglob("*") if f.suffix in (".html", ".php")) if p.is_dir() else [p]
    total = 0
    for f in files:
        for no, rule, msg in lint(f, terms):
            print(f"{f}:{no}: [{rule}] {msg}")
            total += 1
    print(f"{total} issue(s) in {len(files)} file(s)")


if __name__ == "__main__":
    main()
