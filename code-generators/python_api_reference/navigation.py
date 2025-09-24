from pathlib import Path
from typing import Any, Dict, List, Optional, Tuple

class Navigation:

    def add_file(
            self, 
            nav_root: Dict[str, Any],
            folder_parts: List[str],
            title: Optional[str],
            rel_file_path: str,
            is_index: bool) -> None:
        """Insert a file entry into a nested nav tree structure.
        nav_root is a nested dict; each folder dict has optional 
        "__files__": [ {title, path, is_index} ].
        """
        # Add the module parts (ex: ('QuantConnect', 'Algorithm', 
        # 'Framework')) to the nav_root dictionary.
        node = nav_root
        for part in folder_parts:
            node = node.setdefault(part, {})
        # Add a `__files__` key to the nav_root if it doesn't alreayd
        # exist.
        files = node.setdefault("__files__", [])
        # Add this file to the `__files__` list.
        files.append({"title": title, "path": rel_file_path, "is_index": is_index})

    def write_mkdocs_yaml(
            self, nav_root: Dict[str, Any], header_text: str, out_path: Path
        ) -> None:
        """Write mkdocs.yml by combining a header (string) + generated 
        nav. The generated nav is appended under a top-level "nav:" key.
        """
        # Create an empty list to store the lines we'll write.
        lines: List[str] = []
        # Add the mkdocs_header.yml content to the lines.
        header_text = header_text.rstrip("\n")
        if header_text:
            lines.append(header_text)
        # Ensure there's a blank line between header and nav.
        if lines and lines[-1].strip():
            lines.append("")
        # Write the nav tree.
        lines.append("nav:")
        # Add QCAlgorithm reference to the top of the nav.
        lines.append("  - QCAlgorithm: QuantConnect/Algorithm/QCAlgorithm.md")
        # Add the rest of the classes/modules.
        lines.extend(self._nav_to_yaml_lines(nav_root, indent=1))
        lines.append("")  # trailing newline
        out_path.write_text("\n".join(lines), encoding="utf-8")

    def _nav_to_yaml_lines(
            self, nav_root: Dict[str, Any], indent: int = 1) -> List[str]:
        """Convert the nav tree to MkDocs YAML lines.
        Rules:
          - Emit index.md first in each folder (if present).
          - Then MIX directories and remaining files together, sorted 
            case-insensitively by display name.
          - For files with a title, emit "Title: path"; else emit "path".
        """
        pad = "  " * indent
        # Create an empty list to store the lines we'll write.
        lines: List[str] = []
        # Get the list of files.
        files = nav_root.get("__files__", [])
        index_lines, non_indices = self._emit_files_for_folder(files, pad)
        # Add the list of index files to the lines.
        lines.extend(index_lines)

        # Create an empty list to store the unified entries.
        entries: List[Dict[str, Any]] = []
        # Add directories to the `entries.`
        for key in (k for k in nav_root.keys() if k != "__files__"):
            entries.append({"kind": "dir", "name": key})
        # Add files (non-index) to the `entries.`
        for f in non_indices:
            display = (f.get("title") or Path(f["path"]).name)
            entries.append({"kind": "file", "name": display, "file": f})
        # Sort the entries.
        entries.sort(key=lambda e: e["name"].lower())

        # Iterate through each entry.
        for e in entries:
            # If it's a directory, add the directory to the lines, then
            # recurse.
            if e["kind"] == "dir":
                key = e["name"]
                lines.append(f"{pad}- {key}:")
                lines.extend(self._nav_to_yaml_lines(nav_root[key], indent + 1))
            # Otherwise, just add the file to the lines.
            else:
                f = e["file"]
                if f.get("title"):
                    # Skip QCAlgorithm, since it's at the top of the 
                    # nav.
                    if f['title'] == 'QCAlgorithm':
                        continue
                    lines.append(f"{pad}- {f['title']}: {f['path']}")
                else:
                    lines.append(f"{pad}- {f['path']}")

        return lines
    
    def _emit_files_for_folder(
            self, files: List[Dict[str, Any]], pad: str
        ) -> Tuple[List[str], List[Dict[str, Any]]]:
        """Emit index.md first (if any). Return (lines_for_index, 
        remaining_non_index_files). Index lines are emitted as plain 
        paths. Remaining files are returned to be mixed with directories.
        """
        lines: List[str] = []
        # Get the index and non-index files.
        indices = [f for f in files if f.get("is_index")]
        non_indices = [f for f in files if not f.get("is_index")]
        # Sort index files alphabetically.
        for f in sorted(indices, key=lambda d: d["path"].lower()):
            lines.append(f"{pad}- {f['path']}")

        return lines, non_indices