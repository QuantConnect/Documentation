"""Builds faq.en.json from faq/*.md for the www docs ?q= answer cards.

Each FAQ file is "# <Category> FAQ", then "## <Section>" groups of
"### <Question>" entries whose body ends with a "Docs: <url>" line.
Usage: python code-generators/faq_json_generator.py [output_path]
"""

import json
import re
import sys
from pathlib import Path

import markdown
from bs4 import BeautifulSoup

ROOT = Path(__file__).resolve().parent.parent
FAQ_DIRECTORY = ROOT / "faq"
DOCS_LINE = re.compile(r"^Docs:\s*<?(\S+?)>?\s*$", re.MULTILINE)


def slugify(text):
    return re.sub(r"[^a-z0-9]+", "-", text.lower()).strip("-")


def base_name(path):
    # writing-algorithms.part2.md continues writing-algorithms.part1.md
    return path.name.split(".")[0]


def parse_file(path, category):
    entries = []
    section = None
    question = None
    body = []

    def flush():
        if question is None:
            return
        text = "\n".join(body).strip()
        docs = DOCS_LINE.search(text)
        if docs is None:
            raise ValueError(f"{path.name}: '{question}' has no 'Docs: <url>' line")
        answer_markdown = DOCS_LINE.sub("", text).strip()
        answer_html = markdown.markdown(answer_markdown, extensions=["fenced_code", "tables"])
        entries.append({
            "id": slugify(question),
            "question": question,
            "category": category,
            "section": section,
            "answer": answer_html,
            "text": " ".join(BeautifulSoup(answer_html, "html.parser").get_text(" ").split()),
            "url": docs.group(1),
        })

    in_code = False
    for line in path.read_text(encoding="utf-8").splitlines():
        if line.startswith("```"):
            in_code = not in_code
        if not in_code and line.startswith("## "):
            flush()
            section, question, body = line[3:].strip(), None, []
        elif not in_code and line.startswith("### "):
            flush()
            question, body = line[4:].strip(), []
        elif question is not None:
            body.append(line)
    flush()
    return entries


def main():
    output = Path(sys.argv[1]) if len(sys.argv) > 1 else ROOT / "faq.en.json"
    files = sorted(FAQ_DIRECTORY.glob("*.md"))

    # Part files without their own H1 take the category of their base file
    categories = {}
    for path in files:
        title = re.search(r"^# (.+?)(?: FAQ)?\s*$", path.read_text(encoding="utf-8"), re.MULTILINE)
        if title:
            categories[base_name(path)] = title.group(1)

    entries = []
    for path in files:
        category = categories.get(base_name(path), base_name(path).replace("-", " ").title())
        entries.extend(parse_file(path, category))

    seen = {}
    for entry in entries:
        count = seen.get(entry["id"], 0)
        seen[entry["id"]] = count + 1
        if count:
            entry["id"] = f"{entry['id']}-{count + 1}"

    output.write_text(json.dumps(entries, ensure_ascii=False, indent=1), encoding="utf-8")
    print(f"Wrote {len(entries)} questions to {output}")


if __name__ == "__main__":
    main()
