import ast
import os
from pathlib import Path
from typing import Any, Dict, List, Tuple

from navigation import Navigation

# Do NOT generate pages for these classes; 
# also remove them from module index tables & nav.
EXCLUDED_CLASS_PAGES = {"_EventContainer"}


class Documentation:

    def __init__(self):
        self._navigation = Navigation()
    
    def create_docs(self, stubs_root, docs_root):
        # If the docs directory doesn't exist yet, create it.
        docs_root.mkdir(parents=True, exist_ok=True)
        # Create the markdown files for the documentation.
        nav_root = self._create_markdown_files(stubs_root, docs_root)
        # Create Path objects that point to the input and output yml.
        mkdocs_header = Path("mkdocs_header.yml")
        mkdocs_out = Path("mkdocs.yml")
        # Read the mkdocs_header.yml file.
        header_text = mkdocs_header.read_text(encoding="utf-8")
        # Write the mkdocs.yml file by combining the mkdocs_header.yml 
        # content and the `nav`.
        self._navigation.write_mkdocs_yaml(nav_root, header_text, mkdocs_out)

    def _create_markdown_files(
            self, stubs_root: Path, docs_root: Path
        ) -> Tuple[Dict[str, Any], int, int]:
        """Walk stubs_root, write docs into docs_root, and build a nav 
        tree.
        
        Returns nav_root.
        """
        nav_root: Dict[str, Any] = {}

        # Iterate through the directories in the /stubs directory.
        for dirpath, _, filenames in os.walk(stubs_root):
            # Get the .pyi file(s) in this directory.
            filenames = [f for f in filenames if f.endswith(".pyi")]
            if not filenames:
                continue
            # Iterate through the .pyi file(s).
            for filename in filenames:
                # Define the path to the stub file.
                pyi_path = Path(dirpath) / filename
                # Define the path to the module the stub is in.
                module_path = self._rel_module_from_stub(stubs_root, pyi_path)
                # Read and parse the file contents.
                classes_info, enums, funcs = self._parse_pyi(
                    pyi_path.read_text(encoding="utf-8", errors="ignore")
                )
                # Filter out some classes (for index + pages + nav).
                classes_info_filtered = [
                    (n, d) for (n, d) in classes_info 
                    if n not in EXCLUDED_CLASS_PAGES
                ]
                # If nothing to show, skip this module entirely.
                if not classes_info_filtered and not enums and not funcs:
                    continue
                # Get the path in the /docs directory where the docs
                # should go.
                docs_dir = docs_root.joinpath(
                    *pyi_path.parent.relative_to(stubs_root).parts
                )
                # If the directory in the /docs doesn't exist yet, 
                # create it.
                docs_dir.mkdir(parents=True, exist_ok=True)

                # Write module md files (ex: QuantConnect.Algorithm).
                has_any_for_index = bool(classes_info_filtered or enums or funcs)
                if has_any_for_index:
                    mod_index_md = docs_dir / "index.md"
                    contents = self._render_module_index_md(
                        module_path, classes_info_filtered, enums, funcs
                    )
                    if contents.strip():
                        mod_index_md.write_text(contents, encoding="utf-8")

                # Write class md files (ex: 
                # QuantConnect.Algorithm.QCAlgorithm).
                sorted_classes = sorted(
                    classes_info_filtered, key=lambda t: t[0].lower()
                )
                for cls_name, _ in sorted_classes:
                    # If the class is 'Index', create a new directory
                    # so that we can write the class info to 
                    # Index/index.md.
                    if cls_name == 'Index':
                        (docs_dir / 'Index').mkdir(parents=True, exist_ok=True)
                    cls_md = docs_dir / f"{self._clean_class_path(cls_name)}.md"
                    cls_md.write_text(
                        self._render_class_md(module_path, cls_name), 
                        encoding="utf-8"
                    )

                # ----- nav entries -----
                # Split the module path into it's parts.
                # Example: ('QuantConnect', 'Algorithm', 'Framework')
                mod_dir_parts = Path(
                    docs_dir.relative_to(docs_root).as_posix()
                ).parts

                # Write module landing pages to the nav.
                if has_any_for_index:
                    self._navigation.add_file(
                        nav_root,
                        list(mod_dir_parts),
                        title=None,
                        rel_file_path=(
                            docs_dir / "index.md"
                        ).relative_to(docs_root).as_posix(),
                        is_index=True,
                    )

                # Write class pages to the nav.
                for cls_name, _ in sorted_classes:
                    self._navigation.add_file(
                        nav_root,
                        list(mod_dir_parts),
                        title=cls_name,
                        rel_file_path=(
                            docs_dir / f"{self._clean_class_path(cls_name)}.md"
                        ).relative_to(docs_root).as_posix(),
                        is_index=False,
                    )
        return nav_root

    def _parse_pyi(
            self, text: str
        ) -> Tuple[List[Tuple[str, str]], List[str], List[str]]:
        """
        Return (classes_info, enums, funcs)
        - classes_info: list of (ClassName, docstring)
        - enums: list of Enum class names
        - funcs: list of module-level function names
        """
        # Parse the pyi file into an AST tree to make it easy to analyze.
        tree = ast.parse(text)
        # Create empty lists for classes, enums, and functions in the 
        # file.
        classes: List[Tuple[str,str]] = []
        enums: List[str] = []
        funcs: List[str] = []
        # Iterate through each node in the tree.
        for node in tree.body:
            # Case 1: Class
            if isinstance(node, ast.ClassDef):
                # Get the class name.
                name = node.name
                # Check if this class is an Enum.
                # Simple Enum check: any base ends with 'Enum'
                is_enum = any(
                    (isinstance(b, ast.Name) and b.id.endswith("Enum")) or
                    (isinstance(b, ast.Attribute) and b.attr.endswith("Enum"))
                    for b in node.bases
                )
                # If it's an enum, add it to the enum list.
                if is_enum:
                    enums.append(name)
                # If it's a class, add it to the class list.
                else:
                    classes.append((name, (ast.get_docstring(node) or "").strip()))
            # Case 2: Function
            elif isinstance(node, ast.FunctionDef):
                funcs.append(node.name)
        # Return classes, enums, and functions present in the pyi file.
        return classes, enums, funcs
    
    def _rel_module_from_stub(self, stubs_root: Path, pyi_path: Path) -> str:
        """
        Convert a stub path to a dotted module path (without the .pyi extension).
        For package stubs (foo/__init__.pyi), return 'foo' (not 'foo.__init__').
        """
        rel = pyi_path.parent.relative_to(stubs_root)
        # If rel is empty (unlikely), return empty string
        return ".".join(rel.parts) if rel.parts else ""

    def _render_module_index_md(
            self, 
            module_path: str,
            classes_info: List[Tuple[str,str]],
            enums: List[str],
            funcs: List[str]) -> str:
        """
        Create the module index page with tables for Classes/Enums/Functions.
        """
        # Define an empty list of lines to write.
        lines: List[str] = []
        # Write the title.
        pretty_title = module_path or "(root)"
        lines.append(f"# {pretty_title}")
        lines.append("")
        # Write class table.
        if classes_info:
            lines.append("## Classes")
            lines.append("")
            # Table header
            lines.append("| Class | Description |")
            lines.append("|-------|-------------|")
            # Iterate through classes in alphabetical order.
            for cls, desc in sorted(classes_info, key=lambda t: t[0].lower()):
                # Get the link to the reference page of the class.
                link = f"{self._clean_class_path(cls)}.md"
                # Get a short description about the class.
                short = (desc.splitlines()[0] if desc else "").strip()
                if len(short) < len(desc):
                    short += '...'
                # Write the class name and description in the table.
                lines.append(f"| [{cls}]({link}) | {short} |")
            lines.append("")

        # Write enums.
        if enums:
            lines += ["## Enumerations", ""]
            for name in sorted(enums):
                lines += [f"::: {module_path}.{name}", ""]
        # Write functions.
        if funcs:
            lines += ["## Functions", ""]
            for name in sorted(funcs):
                lines += [f"::: {module_path}.{name}", ""]

        return "\n".join(lines).rstrip() + "\n" if lines else ""
    
    def _render_class_md(self, module_path: str, class_name: str) -> str:
        """
        No H1. Just the mkdocstrings directive.
        """
        fq = f"{module_path}.{class_name}" if module_path else class_name
        return f"::: {fq}\n"
    
    def _clean_class_path(self, class_name):
        if class_name == 'Index':
            return 'Index/index'
        return class_name