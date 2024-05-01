### To be run on Lean repo's root directory to obtain all enum type names
import re
from pathlib import Path

m = []
for path in Path.rglob(Path.cwd(), "*.cs"):
    with open(path, "r", encoding="utf-8") as file:
        content = file.read()
    methods = re.findall(fr"enum ([A-Za-z0-9_]+)\n", content)
    m.extend(list(methods))
    methods = re.findall(fr"static class ([A-Za-z0-9_]+)\n", content)
    m.extend(list(methods))
    
print(m)