"""
Detect language-mixing bugs in section-example-container blocks.

Background
----------
A `<div class="section-example-container">` is shown to every reader regardless of
the C#/Python language they selected. The per-container language toggle only appears
when the container holds BOTH a `<pre class="csharp">` and a `<pre class="python">`.
A container that holds code in only ONE language is therefore always displayed - so
unless something hides it from the other language, that code leaks to the wrong
audience. The screenshot bug: C# `#load` setup cells showing up for Python readers.

A single-language block is hidden from the other language when EITHER

  * the container div carries the language as a second class, e.g.
    `class="section-example-container csharp"`, or
  * the container is nested inside an ancestor element that carries the language
    class, e.g. `<div class="python"> ... <div class="section-example-container">`.
    Ancestor wrappers in this repo include div, li, ol, ul, td, tr, p and section.

So a single-language container is a BUG only when neither it nor any ancestor scopes
it to that language.

What this flags
---------------
Every `section-example-container` whose inner `<pre>` tags are all one language but
which is not scoped to that language by itself or an ancestor, classified by page
context:

  error   - the page contains BOTH languages, so the unscoped block genuinely mixes
            C# and Python in the reader's view (the screenshot bug).
  warning - the page is entirely one language; nothing is mixed in-page, but the
            block is still shown to readers of the other language. Often an
            intentional single-language page - review before scoping.

It also flags, always as an error, the contradiction of a div (or ancestor) scoped to
one language that contains the other language's `<pre>`.

Generated `single-page/` concatenations are skipped by default (they duplicate the
individual source pages); pass --include-single-page to scan them too.

Usage:
    python code-generators/detect-language-mixing.py [root] [--include-single-page]
                                                            [--warnings]
                                                            [--exclude PREFIX ...]

  root                   directory to scan (default: repository root).
  --include-single-page  also scan the generated single-page/ books.
  --warnings             also print the single-language-page warnings (off by
                         default so the genuine mixing errors stand out).
  --exclude PREFIX       skip files whose repo-relative (posix) path equals or
                         starts with PREFIX. Repeatable.

Exit code is 1 when any error-severity issue is found, 0 otherwise.
"""

import argparse
import sys
from html.parser import HTMLParser
from pathlib import Path

# `pre` classes that pin a block to one language. Anything else (html, json,
# all, none) is language-neutral and intentionally shown to every reader.
LANG_CLASSES = ("csharp", "python")

# Elements we keep on the open-element stack: anything that can be a language
# wrapper around a container, or that affects nesting via implicit closing.
STRUCTURAL = {
    "div", "section", "ol", "ul", "li", "dl", "dd", "dt",
    "table", "thead", "tbody", "tfoot", "tr", "td", "th",
    "p", "blockquote",
}

# Void elements never open a scope.
VOID = {
    "area", "base", "br", "col", "embed", "hr", "img", "input",
    "link", "meta", "param", "source", "track", "wbr",
}

# Opening any of these implicitly closes an open <p> (HTML5 rules, trimmed).
P_CLOSERS = {
    "address", "article", "aside", "blockquote", "details", "div", "dl",
    "fieldset", "figcaption", "figure", "footer", "form", "h1", "h2", "h3",
    "h4", "h5", "h6", "header", "hr", "main", "menu", "nav", "ol", "p",
    "pre", "section", "table", "ul", "li",
}


def _autocloses(open_tag, new_tag):
    """True if `open_tag` is implicitly closed by opening `new_tag`."""
    if open_tag == "p":
        return new_tag in P_CLOSERS
    if open_tag == "li":
        return new_tag == "li"
    if open_tag in ("dd", "dt"):
        return new_tag in ("dd", "dt")
    if open_tag in ("td", "th"):
        return new_tag in ("td", "th", "tr")
    if open_tag == "tr":
        return new_tag == "tr"
    return False


class ContainerParser(HTMLParser):
    """Walk the HTML fragment and record every section-example-container."""

    def __init__(self):
        super().__init__(convert_charrefs=True)
        self._stack = []  # open structural elements: dicts with tag/classes/...
        self.containers = []  # one record per section-example-container
        # Total language-specific <pre> tags anywhere on the page, used to tell
        # a bilingual page (genuine mixing) from a single-language page.
        self.page_pre = {"csharp": 0, "python": 0}

    @staticmethod
    def _classes(attrs):
        for name, value in attrs:
            if name == "class" and value:
                return value.split()
        return []

    def _apply_autoclose(self, new_tag):
        while self._stack and _autocloses(self._stack[-1]["tag"], new_tag):
            self._stack.pop()

    def _ancestor_scope(self):
        """Languages that any currently-open ancestor scopes the subtree to."""
        scope = set()
        for frame in self._stack:
            for lang in LANG_CLASSES:
                if lang in frame["classes"]:
                    scope.add(lang)
        return scope

    def handle_starttag(self, tag, attrs):
        classes = self._classes(attrs)

        if tag == "pre":
            # Opening <pre> closes an open <p> but never wraps a container.
            self._apply_autoclose("pre")
            container = self._innermost_container()
            for lang in LANG_CLASSES:
                if lang in classes:
                    self.page_pre[lang] += 1
                    if container is not None:
                        container[lang] += 1
            return

        if tag not in STRUCTURAL:
            return

        self._apply_autoclose(tag)

        frame = {
            "tag": tag,
            "classes": classes,
            "is_container": tag == "div" and "section-example-container" in classes,
            "line": self.getpos()[0],
            "csharp": 0,
            "python": 0,
            "ancestor_scope": self._ancestor_scope(),
        }
        self._stack.append(frame)

    def handle_startendtag(self, tag, attrs):
        # Self-closed <pre/> still counts its language; structural self-closing
        # tags open nothing.
        if tag == "pre":
            self.handle_starttag(tag, attrs)

    def handle_endtag(self, tag):
        if tag not in STRUCTURAL:
            return
        # Pop down to the nearest matching open tag; ignore stray end tags.
        for i in range(len(self._stack) - 1, -1, -1):
            if self._stack[i]["tag"] == tag:
                closed = self._stack[i:]
                del self._stack[i:]
                for frame in closed:
                    if frame["is_container"]:
                        self.containers.append(frame)
                return

    def _innermost_container(self):
        for frame in reversed(self._stack):
            if frame["is_container"]:
                return frame
        return None

    def close_and_drain(self):
        """Flush container divs left unclosed by malformed markup."""
        self.close()
        for frame in self._stack:
            if frame["is_container"]:
                self.containers.append(frame)
        self._stack = []


def classify(container, page_bilingual):
    """Return (severity, kind, detail) for a container, or None if it's fine."""
    classes = container["classes"]
    cs, py = container["csharp"], container["python"]
    scope = set(container["ancestor_scope"])
    if "csharp" in classes:
        scope.add("csharp")
    if "python" in classes:
        scope.add("python")
    cs_scoped = "csharp" in scope
    py_scoped = "python" in scope

    # A block scoped to one language must not contain the other's code.
    if cs_scoped and not py_scoped and py > 0:
        return ("error", "csharp-scope-contains-python",
                f"scoped to csharp but holds {py} python <pre>")
    if py_scoped and not cs_scoped and cs > 0:
        return ("error", "python-scope-contains-csharp",
                f"scoped to python but holds {cs} csharp <pre>")

    # C# code only, not scoped to C# -> leaks to Python readers.
    if cs > 0 and py == 0 and not cs_scoped:
        severity = "error" if page_bilingual else "warning"
        return (severity, "csharp-leaks-to-python",
                f"{cs} csharp <pre>, 0 python; not scoped to csharp")

    # Python code only, not scoped to Python -> leaks to C# readers.
    if py > 0 and cs == 0 and not py_scoped:
        severity = "error" if page_bilingual else "warning"
        return (severity, "python-leaks-to-csharp",
                f"{py} python <pre>, 0 csharp; not scoped to python")

    return None


def scan_file(path):
    parser = ContainerParser()
    try:
        parser.feed(path.read_text(encoding="utf-8", errors="replace"))
        parser.close_and_drain()
    except Exception as exc:  # noqa: BLE001 - keep scanning other files
        return [("error", 0, "parse-error", str(exc))]

    page_bilingual = parser.page_pre["csharp"] > 0 and parser.page_pre["python"] > 0
    results = []
    for container in parser.containers:
        verdict = classify(container, page_bilingual)
        if verdict is not None:
            severity, kind, detail = verdict
            results.append((severity, container["line"], kind, detail))
    return results


def main(argv):
    parser = argparse.ArgumentParser(
        description="Detect language-mixing in section-example-container blocks.")
    parser.add_argument("root", nargs="?", default=None,
                        help="Directory to scan (default: repository root).")
    parser.add_argument("--include-single-page", action="store_true",
                        help="Also scan the generated single-page/ books.")
    parser.add_argument("--warnings", action="store_true",
                        help="Also print the single-language-page warnings.")
    parser.add_argument("--exclude", action="append", default=[], metavar="PREFIX",
                        help="Skip files whose repo-relative path equals or starts "
                             "with PREFIX (repeatable).")
    opts = parser.parse_args(argv)

    root = Path(opts.root) if opts.root else Path(__file__).resolve().parent.parent
    root = root.resolve()
    excludes = [e.replace("\\", "/").rstrip("/") for e in opts.exclude]

    files = sorted(p for ext in ("*.html", "*.php") for p in root.rglob(ext))

    error_count = warning_count = 0
    skipped_single_page = skipped_excluded = 0
    error_files = set()

    for path in files:
        rel_posix = path.relative_to(root).as_posix()
        if not opts.include_single_page and rel_posix.startswith("single-page/"):
            skipped_single_page += 1
            continue
        if any(rel_posix == ex or rel_posix.startswith(ex + "/") for ex in excludes):
            skipped_excluded += 1
            continue

        issues = scan_file(path)
        for severity, line, kind, detail in sorted(issues, key=lambda i: i[1]):
            if severity == "error":
                error_count += 1
                error_files.add(rel_posix)
                print(f"ERROR   {rel_posix}:{line}: [{kind}] {detail}")
            else:
                warning_count += 1
                if opts.warnings:
                    print(f"warning {rel_posix}:{line}: [{kind}] {detail}")

    print()
    scanned = len(files) - skipped_single_page - skipped_excluded
    extra = f", {skipped_excluded} excluded by --exclude" if skipped_excluded else ""
    print(f"Scanned {scanned} files "
          f"({skipped_single_page} single-page/ files skipped{extra}).")
    print(f"Errors  : {error_count} mixing issue(s) across {len(error_files)} file(s) "
          f"-- single-language block on a bilingual page.")
    print(f"Warnings: {warning_count} unscoped block(s) on single-language pages "
          f"({'shown above' if opts.warnings else 'use --warnings to list'}).")
    return 1 if error_count else 0


if __name__ == "__main__":
    sys.exit(main(sys.argv[1:]))
