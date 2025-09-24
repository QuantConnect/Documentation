import ast
import re
from typing import List, Tuple
from collections import OrderedDict

# Define some regex to help with parsing the docstrings.
_PARAM_LINE_RE = re.compile(r"^\s*:param\s+([A-Za-z_]\w*)\s*:\s*(.*)$")
_RET_LINE_RE = re.compile(r"^\s*:returns?\s*:\s*(.*)$")
_STOP_BLOCK_RE = re.compile(r"^\s*:(param|type|returns?|rtype)\b")


class Docstring:

    def create_from_overloads(
            self, overload_fns: List[ast.FunctionDef], class_name: str, 
            method_name: str) -> str:
        intro_blocks: List[List[str]] = []
        seen_intro_keys = set()
        params_union: "OrderedDict[str, str]" = OrderedDict()
        returns_set: List[str] = []

        # Iterate through each overload.
        for fn in overload_fns:
            # Get the docstring of the overload.
            doc = (ast.get_docstring(fn) or "").strip()
            # If there is no docstring in the overload, just continue.
            if not doc:
                continue
            # Decompose the docstring into its 3 parts.
            intro, param_lines, return_lines = self._split_doc(doc)
            # If there is an introduction in the docstring...
            if intro:
                # Make the introduction look nice.
                key = self._normalize_block(intro)
                # Only save unique introduction text blocks.
                if key and key not in seen_intro_keys:
                    seen_intro_keys.add(key)
                    intro_blocks.append(intro)
            # Iterate through each parameter description in the 
            # docstring.
            for pl in param_lines:
                m = _PARAM_LINE_RE.match(pl)
                if not m:
                    continue
                # Get the parameter name and description.
                name, desc = m.group(1), m.group(2).strip()
                if name not in params_union:
                    params_union[name] = desc
                elif not params_union[name] and desc:
                    params_union[name] = desc
            # Iterate through each return description in the docstring.
            for rl in return_lines:
                m = _RET_LINE_RE.match(rl)
                if not m:
                    continue
                desc = m.group(1).strip()
                if desc and desc not in returns_set:
                    returns_set.append(desc)
        # Create an empty list to store the lines we'll write.
        out_lines: List[str] = []
        # If there was only one unique description we found, use it.
        if len(intro_blocks) == 1:
            out_lines.extend(intro_blocks[0])
        # Otherwise, just list all of the unique descriptions.
        elif len(intro_blocks) > 1:
            out_lines.append("Signature descriptions:")
            out_lines.append("")
            for i, block in enumerate(intro_blocks):
                first = True
                for ln in block:
                    if first:
                        out_lines.append(f"- {ln}")
                        first = False
                    else:
                        out_lines.append(f"  {ln}")
                if i < len(intro_blocks) - 1:
                    out_lines.append("")

        # Write the parameter descriptions.
        if params_union:
            if out_lines:
                out_lines.append("")
            for name, desc in params_union.items():
                out_lines.append(f":param {name}: {desc}")

        # Write the return description.
        if returns_set:
            # If there was only one, use it.
            if len(returns_set) == 1:
                if out_lines:
                    out_lines.append("")
                out_lines.append(f":returns: {returns_set[0]}")
            # Otherwise, list out all the cases.
            else:
                combined = "; ".join(f"Case {i+1}: [{desc}]" for i, desc in enumerate(returns_set))
                if out_lines:
                    out_lines.append("")
                out_lines.append(f":returns: Depends on the signature used. {combined}")

        return "\n".join(out_lines).rstrip()

    def _split_doc(self, doc: str) -> Tuple[List[str], List[str], List[str]]:
        # Split the docstring into lines.
        lines = [ln.rstrip() for ln in doc.splitlines()]
        # Create empty lists to store the intro, param descriptions, and
        # return description.
        intro: List[str] = []
        param_lines: List[str] = []
        return_lines: List[str] = []
        in_intro = True
        # Iterate through each line in the docstring.
        for line in lines:
            # Collect the intro description in the docstring.
            if in_intro:
                if not line.strip():
                    in_intro = False
                    continue
                if _STOP_BLOCK_RE.match(line):
                    in_intro = False
                else:
                    intro.append(line)
                    continue
            # Collect the :param: lines in the doctring.
            if _PARAM_LINE_RE.match(line):
                param_lines.append(line.strip())
                continue
            # Collect the :return: line in the docstring.
            if _RET_LINE_RE.match(line):
                return_lines.append(line.strip())
        return intro, param_lines, return_lines

    def _normalize_block(self, lines: List[str]) -> str:
        text = "\n".join(lines).strip()
        return "\n".join(" ".join(l.split()) for l in text.splitlines()).strip()