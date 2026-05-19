from __future__ import annotations
import ast
import re
from pathlib import Path
from typing import Dict, List, Optional, Tuple


# Matches helper-class names like `_QCAlgorithm_AddData` or
# `_Typed_QCAlgorithm_AddData`: leading underscore, optional `Typed_`
# prefix, then two PascalCase identifiers separated by an underscore.
HELPER_CLASS_RE = re.compile(r'^_(Typed_)?[A-Z]\w*_[A-Z]\w*$')


class PropertyNormalizer:
    """Rewrites .pyi files so callable-property patterns appear as methods.

    The quantconnect-stubs package models C# overloaded methods as a
    `@property` that returns a helper class with `__call__` overloads.
    mkdocstrings classifies the property as an attribute (`attr`) rather
    than a method (`meth`).

    For each non-helper class, this finds:
        @property
        def <name>(self) -> _<ClassName>_<MethodName>: ...
    and replaces it with the `__call__` overloads of the helper class
    (and the `_Typed_<ClassName>_<MethodName>` variant if it exists),
    renamed to `<name>`. The existing OverloadMerger then merges the
    overloads into a single method signature in the sibling `.py` file.
    """

    def process_dir(self, stubs_root: str) -> None:
        # Iterate through each .pyi file in the stubs root.
        for pyi_path in Path(stubs_root).rglob("*.pyi"):
            self._process_file(pyi_path)

    def _process_file(self, pyi_path: Path) -> None:
        text = pyi_path.read_text(encoding="utf-8")
        try:
            tree = ast.parse(text, filename=str(pyi_path))
        except SyntaxError:
            return

        # Find all top-level helper classes in this file, keyed by class
        # name. Nested helper classes aren't used in the stubs.
        helper_classes: Dict[str, ast.ClassDef] = {
            node.name: node for node in tree.body
            if isinstance(node, ast.ClassDef)
            and HELPER_CLASS_RE.match(node.name)
        }
        if not helper_classes:
            return

        # Collect (property_def, helper_class_name) pairs for every
        # @property in a non-helper class that returns a helper class.
        replacements: List[Tuple[ast.FunctionDef, str]] = []
        for node in tree.body:
            if (not isinstance(node, ast.ClassDef)
                    or HELPER_CLASS_RE.match(node.name)):
                continue
            for child in node.body:
                if (isinstance(child, ast.FunctionDef)
                        and self._is_property(child)):
                    helper_name = self._helper_name(child.returns)
                    if helper_name in helper_classes:
                        replacements.append((child, helper_name))
        if not replacements:
            return

        # Build line edits. Each edit replaces lines[start:end] with
        # new_lines.
        lines = text.splitlines()
        edits: List[Tuple[int, int, List[str]]] = []
        used_helpers: set = set()

        for prop_def, helper_name in replacements:
            start, end = self._line_range(prop_def)
            new_lines = self._render_overloads(
                prop_def.name, helper_classes[helper_name], lines
            )
            used_helpers.add(helper_name)
            # Include the `_Typed_` variant's overloads if present, so
            # the generic typed signatures show up in the docs too.
            typed_name = "_Typed_" + helper_name[1:]
            typed = helper_classes.get(typed_name)
            if typed is not None:
                new_lines += self._render_overloads(prop_def.name, typed, lines)
                used_helpers.add(typed_name)
            edits.append((start, end, new_lines))

        # Remove the helper classes we consumed so they don't appear in
        # the generated docs.
        for name in used_helpers:
            start, end = self._line_range(helper_classes[name])
            edits.append((start, end, []))

        # Apply edits in descending start order to keep line indices
        # stable.
        edits.sort(key=lambda e: e[0], reverse=True)
        new_lines = lines[:]
        for start, end, replacement in edits:
            new_lines[start:end] = replacement

        pyi_path.write_text("\n".join(new_lines) + "\n", encoding="utf-8")

    def _is_property(self, fn: ast.FunctionDef) -> bool:
        return any(
            isinstance(d, ast.Name) and d.id == "property"
            for d in fn.decorator_list
        )

    def _helper_name(self, ann: Optional[ast.AST]) -> Optional[str]:
        # Resolve the return annotation to a bare class name. The stub
        # uses fully qualified attribute access (e.g.
        # `QuantConnect.Algorithm._QCAlgorithm_AddData`).
        if ann is None:
            return None
        if isinstance(ann, ast.Name):
            name = ann.id
        elif isinstance(ann, ast.Attribute):
            name = ann.attr
        else:
            return None
        return name if HELPER_CLASS_RE.match(name) else None

    def _line_range(self, node: ast.AST) -> Tuple[int, int]:
        """Return (start, end) as 0-based half-open line indices,
        including any decorators preceding the def.
        """
        start = node.lineno
        decorators = getattr(node, "decorator_list", None) or []
        if decorators:
            start = min(d.lineno for d in decorators)
        return start - 1, node.end_lineno

    def _render_overloads(
            self, new_name: str, helper: ast.ClassDef,
            source_lines: List[str]) -> List[str]:
        """Return the lines of each `__call__` overload in `helper`,
        with the method renamed to `new_name`. Each block is followed
        by a blank line.
        """
        out: List[str] = []
        for child in helper.body:
            if not (isinstance(child, ast.FunctionDef)
                    and child.name == "__call__"):
                continue
            start, end = self._line_range(child)
            block = source_lines[start:end]
            renamed = False
            for ln in block:
                if not renamed and "def __call__" in ln:
                    ln = ln.replace("def __call__", f"def {new_name}", 1)
                    renamed = True
                out.append(ln)
            out.append("")
        return out
