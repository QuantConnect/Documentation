from __future__ import annotations
import ast
from pathlib import Path
from typing import List, Dict, Tuple

from signature import Signature
from docstring import Docstring
from ast_utils import (
    func_has_overload_decorator, 
    walk_classes,
    group_methods_by_name,
    _indent
)


class OverloadMerger:

    def __init__(self):
        self._signature = Signature()
        self._docstring = Docstring()

    def process_dir(self, stubs_root: str):
        # Create a Path to the stubs root.
        stubs_root = Path(stubs_root)
        # Iterate through each .pyi file.
        for pyi_path in stubs_root.rglob("*.pyi"):
            # Aggregate all the overload methods into non-overload 
            # method signatures.
            block = self._generate_for_pyi(pyi_path)
            # If this file didn't have any overloads, continue onto the 
            # next .pyi file.
            if not block:
                continue
            # Append the non-overload method signatures to the .py file 
            # that's in the same directory as the .pyi file.
            with pyi_path.with_suffix(".py").open("a", encoding="utf-8") as f:
                f.write(block)

    def _generate_for_pyi(self, input_pyi: Path) -> str:
        # Parse the .pyi file into an AST (Abstract Syntax Tree) 
        # representation so it's easy to analyze.
        tree = ast.parse(
            input_pyi.read_text(encoding="utf-8"), filename=str(input_pyi)
        )
        # Analyze the `tree` to understand the class heirarchy.
        (
            def_by_qualified_name, 
            children_names_by_parent_name, 
            root_qualified_names
        ) = self._build_class_hierarchy(tree)
        # If the .pyi file has no classes, do nothing.
        if not def_by_qualified_name:
            return ""
        # Create a dictionary where the keys are qualified names and the 
        # values are methods in the respective class where all
        # definitions for the method have the @overload decorator.
        flagged_by_qualified_name = self._compute_flagged_methods_per_class(
            def_by_qualified_name
        )
        # Determine which classes we need to include in the .py file.
        include_by_qualified_name = self._mark_classes_to_emit(
            children_names_by_parent_name, flagged_by_qualified_name, 
            root_qualified_names
        )
        # If there are no non-overload method signatures to write, 
        # continue to the next .pyi file.
        if not any(include_by_qualified_name.values()):
            return ""
        # Create an empty list to store the lines of code that will
        # contine the non-overload method signatures.
        body_lines: List[str] = []
        # Iterate through each class in the pyi file.
        for qualified_name in root_qualified_names:
            # If this class is not flagged to be included in the py file, 
            # skip it.
            if not include_by_qualified_name.get(qualified_name):
                continue
            # Write the non-overload method defintions for the class.
            self._emit_class_recursive(
                qualified_name, def_by_qualified_name, 
                children_names_by_parent_name, flagged_by_qualified_name, 
                include_by_qualified_name, depth=0, out_lines=body_lines
            )
            body_lines.append("")  # blank line between top-level classes

        header = [
            "",
            "",
            "# === Auto-generated: merged overload methods from stub (BEGIN) ===",
            f"# Source stub: {input_pyi}",
            "",
            "# Imports required by the real annotations below",
            "from typing import Any, Optional, Union, Literal, TypedDict, Mapping, MutableMapping, Sequence, MutableSequence, Iterable, Iterator, Reversible, Collection, Callable, Awaitable, Coroutine, AsyncIterable, AsyncIterator, Generator, AsyncGenerator, Type, Tuple, List, Set, FrozenSet, Dict",
            "",
        ]
        footer = [
            "# === Auto-generated: merged overload methods from stub (END) ===",
            "",
        ]
        return "\n".join(header + body_lines + footer)

    def _build_class_hierarchy(
            self, tree: ast.AST
        ) -> Tuple[Dict[str, ast.ClassDef], Dict[str, List[str]], List[str]]:
        # Find all the classes (included nested classes) in this .pyi 
        # file.
        def_by_qualified_name: Dict[str, ast.ClassDef] = {
            qualified_name: def_ for qualified_name, def_ in walk_classes(tree)
        }
        # Create an empty dictionary to store the children of each 
        # class.
        children_names_by_parent_name = {}
        # Create an empty list to store the root qualified names.
        # That is, qualified names with no `.`
        root_qualified_names = []
        # Iterate through each class in the file.
        for qualified_name in def_by_qualified_name.keys():
            # If the qualified name has a period...
            # (ex: QuantConnect.Algorithm)
            if "." in qualified_name:
                # Get the qualified name of the parent class/module.
                parent = qualified_name.rsplit(".", 1)[0]
                # Connect this child to its parent.
                if parent not in children_names_by_parent_name:
                    children_names_by_parent_name[parent] = []
                children_names_by_parent_name[parent].append(qualified_name)
            # If there is no period in the qualified name, it's a root 
            # class.
            else:
                root_qualified_names.append(qualified_name)
        return (
            def_by_qualified_name, 
            children_names_by_parent_name, 
            root_qualified_names
        )
    
    def _compute_flagged_methods_per_class(
            self, 
            def_by_qualified_name: Dict[str, ast.ClassDef]
        ) -> Dict[str, Dict[str, List[ast.FunctionDef]]]:
        # Set up an empty dictionary to store the data we need.
        flagged_by_qualified_name = {}
        # Iterate through each class in the flie.
        for qualified_name, def_ in def_by_qualified_name.items():
            # Create a dictionary where the key is the method name and 
            # value is the list of method definitions with that name.
            defs_by_method_name = group_methods_by_name(def_)
            # Flag the methods that have the @overload decorator for
            # all definitions of the method.
            flagged = {
                name: defs for name, defs in defs_by_method_name.items()
                if defs and all(func_has_overload_decorator(d) for d in defs)
            }
            flagged_by_qualified_name[qualified_name] = flagged
        return flagged_by_qualified_name
    
    def _mark_classes_to_emit(
            self, 
            children_names_by_parent_name: Dict[str, List[str]],
            flagged_by_qualified_name: Dict[str, Dict[str, List[ast.FunctionDef]]],
            root_qualified_names: List[str]) -> Dict[str, bool]:
        """Mark a class True if it has overload-only methods or any of 
        its nested classes do.
        """
        include_by_qualified_name: Dict[str, bool] = {}
        def dfs(qualified_name: str) -> bool:
            # If this class has methods that need a non-overload 
            # signature, `own` is True.
            own = bool(flagged_by_qualified_name.get(qualified_name))
            # If this class has a nested class, recurse.
            children_qualified_names = children_names_by_parent_name.get(
                qualified_name, []
            )
            any_child = any(dfs(ch) for ch in children_qualified_names)
            # If this class or any of its nested classes need a 
            # non-overload signature, flag it here.
            include_by_qualified_name[qualified_name] = own or any_child
            # Include a return statement here to stop the recursion.
            return include_by_qualified_name[qualified_name]
        # Iterate through each root qualified name.
        for qualified_name in root_qualified_names:
            # Add it to the include_by_qualified_name dictionary.
            dfs(qualified_name)
        # Return the results.
        return include_by_qualified_name
    
    def _emit_class_recursive(
            self,
            qualified_name: str,
            def_by_qualified_name: Dict[str, ast.ClassDef],
            children_names_by_parent_name: Dict[str, List[str]],
            flagged_by_qualified_name: Dict[str, Dict[str, List[ast.FunctionDef]]],
            include_by_qualified_name: Dict[str, bool],
            depth: int,
            out_lines: List[str]) -> None:
        # If this class is not flagged to be included in the py file,
        # skip it.
        if not include_by_qualified_name.get(qualified_name):
            return
        # Get the class definition.
        class_def = def_by_qualified_name[qualified_name]
        # Create a string for the base class.
        bases_str = f"({', '.join([ast.unparse(node).strip() for node in class_def.bases])})"
        # Write the class signature.
        ind = _indent(depth)
        out_lines.append(f"{ind}class {class_def.name}{bases_str}:")
        # Set a flag to track if there is a body to the class.
        emitted_something = False
        # If the class has any methods that need a non-overload 
        # signature, write them.
        flagged = flagged_by_qualified_name.get(qualified_name, {})
        for method_name, overloads in sorted(flagged.items()):
            out_lines.append(
                self._emit_merged_method(
                    class_def.name, method_name, overloads, 
                    indent_level=depth + 1
                )
            )
            out_lines.append("")  # blank line between methods
            emitted_something = True
        # If the class has any nested classes that we need to include,
        # recurse to include them.
        for ch in children_names_by_parent_name.get(qualified_name, []):
            if include_by_qualified_name.get(ch):
                self._emit_class_recursive(
                    ch, def_by_qualified_name, children_names_by_parent_name, 
                    flagged_by_qualified_name, include_by_qualified_name, 
                    depth + 1, out_lines
                )
                out_lines.append("")  # blank line between inner classes
                emitted_something = True
        # If we don't have a body for the class, just use `pass`.
        if not emitted_something:
            out_lines.append(f"{ind}    pass")
            out_lines.append("")

    def _emit_merged_method(
            self, class_name: str, method_name: str, 
            overloads: List[ast.FunctionDef], indent_level: int) -> str:
        # Write the signature for the non-overload method.
        sig = self._signature.create_from_overloads(method_name, overloads)
        # Write the docstring for the non-overload method.
        doc = self._docstring.create_from_overloads(
            overloads, class_name, method_name
        )
        # Create an empty list to store the lines of code.
        lines = []
        # Write the method signature.
        ind = _indent(indent_level)
        lines.append(f"{ind}def {method_name}{sig}:")
        # If there is a docstring, write it.
        if doc:
            lines.append(f"{ind}    \"\"\"")
            for ln in doc.splitlines():
                lines.append(f"{ind}    {ln}")
            lines.append(f"{ind}    \"\"\"")
        lines.append(f"{ind}    ...")
        return "\n".join(lines)