import ast
from typing import List, Tuple, Optional
from collections import OrderedDict


class ParamInfo:
    
    # Define the attributes that are allowed for objects of this class.
    __slots__ = ("name", "types", "seen_in", "default", "order_index", "kind")

    def __init__(self, name: str, order_index: int, kind: str):
        self.name = name
        self.types: List[str] = []
        self.seen_in: int = 0
        self.default: Optional[str] = None
        self.order_index = order_index
        self.kind = kind  # ∈ {"pos", "vararg", "kwonly", "varkw"}


class Signature:

    def create_from_overloads(
            self, method_name: str, overloads: List[ast.FunctionDef]) -> str:
        param_info_by_name: "OrderedDict[str, ParamInfo]" = OrderedDict()
        total_overloads = len(overloads)
        order_counter = 0
        # Iterate through each overload and populate the 
        # param_info_by_name dictionary.
        for fn in overloads:
            # Collect the parameters of this overload signature and then
            # iterate through each parameter.
            for name, ann, default, kind in self._collect_params(fn):
                # If this parameter name hasn't been seen yet, create a
                # ParamInfo object for it.
                if name not in param_info_by_name:
                    param_info_by_name[name] = ParamInfo(
                        name, order_counter, kind
                    )
                    order_counter += 1
                # Get the ParamInfo object that we created for this 
                # parameter.
                param_info = param_info_by_name[name]  
                # Increment the number of occurances for the parameter.
                param_info.seen_in += 1  
                # Add the type of this parameter for this overload to 
                # the ParamInfo object. The type may change across 
                # different overloads.
                if ann and ann not in param_info.types:
                    param_info.types.append(ann)
                # Keep first non-empty default for non-starred params 
                # only.
                if (kind not in ("vararg", "varkw") and
                    default is not None and 
                    (param_info.default is None or param_info.default.strip() == "")):
                    param_info.default = default

        # Define some empty lists to track the required and optional 
        # parameters.
        required_parts: List[str] = []
        optional_parts: List[str] = []

        def _to_optional_annotation(types: List[str]) -> str:
            if "None" in types:
                non_none = [t for t in types if t != "None"]
                if not non_none:
                    return "Optional[None]"
                inner = " | ".join(non_none)
                return f"Optional[{inner}]"
            return " | ".join(types)

        # Iterate through the ParamInfo objects.
        for param_info in param_info_by_name.values():
            types = param_info.types[:]
            is_star = param_info.kind in ("vararg", "varkw")
            # If the parameter is missing in some overloads (for 
            # non-star params), it's optional.
            is_optional = (
                (param_info.seen_in < total_overloads) and not is_star
            )

            # For starred params, never add None and never set a default.
            if is_star:
                ann = " | ".join(types) if types else ""
                part = f"{param_info.name}: {ann}" if ann else param_info.name
                # place starred params after requireds; they’re 
                # inherently optional
                optional_parts.append(part)
                continue

            # For non-starred params: if optional and 'None' absent, add 
            # it.
            if is_optional and "None" not in types:
                types.append("None")
            # Get the default value.
            if is_optional and param_info.default is None:
                default = 'None'
            else:
                default = param_info.default

            # build annotation (use Optional[...] when None in union)
            if "None" in types:
                ann = _to_optional_annotation(types)
            else:
                ann = (" | ".join(types) if types else "")
            part = f"{param_info.name}: {ann}" if ann else param_info.name
            if default is not None:
                part += f" = {default}"

            (optional_parts if (is_optional or default is not None) else required_parts).append(part)

        # Define all the arguments in the signature.
        all_parts = ["self"] + required_parts + optional_parts

        # Define the return type of the signature.
        if method_name == "__init__":
            return_type = "None"
        else:
            # Get the unique return types from each overload.
            return_types: List[str] = []
            for fn in overloads:
                return_type = ast.unparse(fn.returns).strip()
                if return_type and return_type not in return_types:
                    return_types.append(return_type)
            # If one of the overloads returns None...
            if "None" in return_types:
                # Then the return typeof the signature should be 
                # Optional[...].
                non_none = [t for t in return_types if t != "None"]
                return_type = f"Optional[{' | '.join(non_none)}]" if non_none else "Optional[None]"
            else:
                # Otherwise, the return type of the signature should be
                # the union of all the return types.
                return_type = " | ".join(return_types) if return_types else ""
        # Write the return type of the signature.
        return_annotation = f" -> {return_type}" if return_type else ""
        # Write the `( <args> ) -> <return_type>`.
        return f"({', '.join(all_parts)}){return_annotation}"
    
    def _collect_params(
            self, fn: ast.FunctionDef
        ) -> List[Tuple[str, Optional[str], Optional[str], str]]:
        """Return list of (name, annotation_str_or_None, 
        default_str_or_None, kind) for 
        positional/var-positional/kw-only/var-keyword params, skipping 
        'self'.
        """
        # Define an empty list to hold the parameters.
        params: List[Tuple[str, Optional[str], Optional[str], str]] = []
        a: ast.arguments = fn.args

        # Case 1: positional-or-keyword
        total = len(a.args)
        num_defaults = len(a.defaults)
        defaults_start = total - num_defaults
        for i, arg in enumerate(a.args):
            if arg.arg == "self":
                continue
            ann = ast.unparse(arg.annotation) if arg.annotation else None
            default_node = a.defaults[i - defaults_start] if i >= defaults_start else None
            default = ast.unparse(default_node) if default_node is not None else None
            params.append((arg.arg, ann, default, "pos"))

        # Case 2: var-positional (*args)
        if a.vararg:
            ann = ast.unparse(a.vararg.annotation) if a.vararg.annotation else None
            params.append(("*" + a.vararg.arg, ann, None, "vararg"))

        # Case 3: keyword-only
        for i, arg in enumerate(a.kwonlyargs):
            ann = ast.unparse(arg.annotation) if arg.annotation else None
            default_node = a.kw_defaults[i] if a.kw_defaults else None
            default = ast.unparse(default_node) if default_node is not None else None
            params.append((arg.arg, ann, default, "kwonly"))

        # Case 4: var-keyword (**kwargs)
        if a.kwarg:
            ann = ast.unparse(a.kwarg.annotation) if a.kwarg.annotation else None
            params.append(("**" + a.kwarg.arg, ann, None, "varkw"))

        return params
