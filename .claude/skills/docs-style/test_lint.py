"""Tests for lint.py.

Usage: python -m unittest discover -s .claude/skills/docs-style -p "test_*.py"
"""
import importlib.util
import pathlib
import tempfile
import unittest

_spec = importlib.util.spec_from_file_location("lint", pathlib.Path(__file__).parent / "lint.py")
lint = importlib.util.module_from_spec(_spec)
_spec.loader.exec_module(lint)


class LintTests(unittest.TestCase):
    def run_lint(self, suffix, content):
        with tempfile.TemporaryDirectory() as tmp:
            path = pathlib.Path(tmp) / f"page{suffix}"
            path.write_text(content, encoding="utf-8")
            return [(no, rule) for no, rule, _ in lint.lint(path, [])]

    def test_php_html_prose_matches_html(self):
        # Regression for #2913: HTML prose in .php fragments was never checked.
        line = "<p>There is a need to use this!</p>\n"
        self.assertEqual(self.run_lint(".php", line), self.run_lint(".html", line))
        self.assertIn((1, "there-is"), self.run_lint(".php", line))

    def test_mixed_html_and_php(self):
        content = (
            "<?\n"
            "$note = \"It will work; there is no issue!\";\n"
            "include(DOCS_RESOURCES.\"/x.php\");\n"
            "?>\n"
            "<p>There is a page <? echo \"it will!\"; ?> here.</p>\n"
            "<pre>there is code!</pre>\n"
            "<p>Read it!</p>\n"
            "<script>\n"
            "<?php include(\"x.js\"); ?>\n"
            "render(); done();\n"
            "</script>\n"
        )
        self.assertEqual(self.run_lint(".php", content), [(5, "there-is"), (7, "exclamation")])

    def test_php_faq_json_text(self):
        content = (
            "<?php\n"
            "$faqSchema = <<<'JSON'\n"
            "{\n"
            '    "name": "Will it run?",\n'
            '    "text": "There is a \\"limit\\" here; see <a href=\\"/docs\\">docs</a>.",\n'
            '    "@type": "Answer"\n'
            "}\n"
            "JSON;\n"
            'include(DOCS_RESOURCES."/faq.php");\n'
            "?>\n"
        )
        self.assertEqual(self.run_lint(".php", content), [(4, "future"), (5, "there-is"), (5, "semicolon")])


if __name__ == "__main__":
    unittest.main()
