import ast
from typing import List, Dict, Tuple

def is_overload_decorator(dec: ast.expr) -> bool:
    return (
        (isinstance(dec, ast.Name) and dec.id == "overload") or 
        (isinstance(dec, ast.Attribute) and dec.attr == "overload")
    )

def func_has_overload_decorator(fn: ast.FunctionDef) -> bool:
    return any(is_overload_decorator(d) for d in fn.decorator_list)

def walk_classes(node: ast.AST, qual_prefix: str = "") -> List[Tuple[str, ast.ClassDef]]:
    """Walk an AST node and collect all class definitions with their 
    qualified names.

    Args:
        node: The AST node to traverse (a Module or ClassDef).
        qual_prefix: The qualified name prefix for nested classes 
                     (internal use)

    Returns:
        List of tuples containing (qualified_name, class_definition) 
        pairs.

    Example:
        For code like:
        ```
            class Outer:
                class Inner:
                    pass
        ```
        Returns: [("Outer", <ClassDef>), ("Outer.Inner", <ClassDef>)]
    """
    out: List[Tuple[str, ast.ClassDef]] = []
    # Iterate through each class in the file.
    for child in getattr(node, "body", []):
        if isinstance(child, ast.ClassDef):
            qualified_name = f"{qual_prefix}.{child.name}" if qual_prefix else child.name
            # Add this class to the list of results.
            out.append((qualified_name, child))
            # Recurse, incase the class contains other class defintiions.
            out.extend(walk_classes(child, qualified_name))
    return out

def group_methods_by_name(def_: ast.ClassDef) -> Dict[str, List[ast.FunctionDef]]:
    """Create a dictionary where keys are method names and values are 
    lists of function definitions with that name.
    """
    defs_by_method_name: Dict[str, List[ast.FunctionDef]] = {}
    # Iterate through each method definition in the class body.
    for node in def_.body:
        if isinstance(node, ast.FunctionDef):
            # Append the method defintiion to the list of definitions for
            # this method name.
            defs_by_method_name.setdefault(node.name, []).append(node)
    return defs_by_method_name

def format_bases(class_def: ast.ClassDef) -> str:
    # If the class doesn't inherit from a base class, return an empty 
    # string.
    if not class_def.bases:
        return ""
    bases = [ast.unparse(node).strip() for node in class_def.bases]
    return f"({', '.join(bases)})"

def _indent(level: int) -> str:
    return "    " * level