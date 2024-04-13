import re
from exceptions import ENUMS, SWAPS, EXCEPTIONS

def get_code_snippet(pre_content):
    content = str(pre_content).split('>', maxsplit=1)[1].rsplit("</pre>", 1)[0]
    return content

def conversion(content):
    # do not check for the string in EXCEPTIONS list
    content_to_check = content
    for e in EXCEPTIONS:
        content_to_check = content_to_check.replace(e, "")

    methods = re.findall(r"def\s+(\w+)\s*\(", content_to_check)

    for method in sorted(set(methods), key=len, reverse=True):
        snake_case_method = _title_to_snake_case(method)
        content = content.replace(f"def {method}(", f"def {snake_case_method}(")
    
    methods = re.findall(r"\.(\w+)", content_to_check)

    for method in sorted(set(methods), key=len, reverse=True):
        snake_case_method = _title_to_snake_case(method)
        content = content.replace(f".{method}", f".{snake_case_method}")
    
    # Named arguments. E.g.: mappingResolveDate=datetime(2021, 12, 1)
    methods = [x for x in re.findall(r"\(([^)]+)\)", content_to_check) if '=' in x]
    for method in sorted(set(methods), key=len, reverse=True):
        for arg in re.findall(r"(\w+)=", method.replace(' ','')):
            snake_case_args = _title_to_snake_case(arg)
            content = content.replace(arg, snake_case_args)

    # Local variables: E.g.: sortedByDollarVolume
    methods = re.findall(r"\    (\w+) =", content_to_check)
    for method in sorted(set(methods), key=len, reverse=True):
        snake_case_method = _title_to_snake_case(method)
        content = content.replace(method, snake_case_method)
    
    methods = re.findall(r"<\?=\$research \? \"qb\.\" : \"self\.\"\?>(\w+)", content_to_check)

    for method in sorted(set(methods), key=len, reverse=True):
        snake_case_method = _title_to_snake_case(method)
        content = content.replace(f"<?=$research ? \"qb.\" : \"self.\"?>{method}", f"<?=$research ? \"qb.\" : \"self.\"?>{snake_case_method}")
    
    methods = re.findall(r"<\?=\$pythonPrefix\?>(\w+)", content_to_check)

    for method in sorted(set(methods), key=len, reverse=True):
        snake_case_method = _title_to_snake_case(method)
        content = content.replace(f"<?=$pythonPrefix?>{method}", f"<?=$pythonPrefix?>{snake_case_method}")
        
    for enum in sorted(ENUMS, key=len, reverse=True):
        wanted_pattern = fr"(?![\.]){enum}\.(\w+)(?!\(|\.)"
        unwanted_pattern = fr"(?![\.]){enum}\.(\w+)[\(\.]"
        wanted_methods = re.findall(wanted_pattern, f"{content}<")
        unwanted_methods = [x[:-1] for x in re.findall(unwanted_pattern, f"{content}<")]
        
        for method in sorted(set(wanted_methods).difference(set(unwanted_methods)), key=len, reverse=True):
            content = content.replace(f"{enum}.{method}", f"{enum}.{method.upper()}")
    
    return content

def _title_to_snake_case(title):
    for original, new in SWAPS.items():
        if original in title:
            title = title.replace(original, new)
        
    # for whole method is upper case: indicators, ID, ...
    if title.isupper():
        return title
    
    snake_case = re.sub(r'(?<!^)(?=[A-Z])', '_', title).lower()
    return snake_case