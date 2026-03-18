import os
import re
from glob import iglob
from itertools import chain
from shutil import rmtree

output_dir = '03 Writing Algorithms/01 Key Concepts/99 Glossary'
if os.path.exists(output_dir):
    rmtree(output_dir)
os.makedirs(output_dir, exist_ok=True)

glossary_dir = 'Resources/glossary'
files = sorted([f for f in os.listdir(glossary_dir) if f.endswith('.html')], key=str.lower)

proper_nouns = {'sharpe', 'sortino', 'treynor', 'pearson', 'probabilistic'}

def display_name(term):
    words = term.replace('-', ' ').split()
    return ' '.join(w.capitalize() if w in proper_nouns else w for w in words)

# Write glossary pages and build the anchor-link lookup in one pass
lower_keys = {}
for n, f in enumerate(files, start=2):
    term = f.removesuffix('.html')
    name = display_name(term)
    lower_keys[name.lower()] = n
    with open(f"{output_dir}/{n:02} {name}.php", "w", encoding="utf-8") as php:
        php.write(f'<? include(DOCS_RESOURCES."/glossary/{term}.html"); ?>')

with open(f"{output_dir}/01 Introduction.php", "w", encoding="utf-8") as php:
    php.write('<?php include(DOCS_RESOURCES."/_mathjax.html"); ?>\n<p>This page defines terms in QuantConnect products and documentation.</p>')

with open(f"{output_dir}/metadata.json", "w", encoding="utf-8") as f:
    f.write('''{
    "type": "metadata",
    "values": {
        "description": "This page defines terms in QuantConnect products and documentation.",
        "keywords": "alpha, sharpe ratio, win rate, capacity",
        "og:description": "This page defines terms in QuantConnect products and documentation.",
        "og:title": "Glossary - Documentation QuantConnect.com",
        "og:type": "website",
        "og:site_name": "Glossary - QuantConnect.com",
        "og:image": "https://cdn.quantconnect.com/docs/i/writing-algorithms/key-concepts/glossary.png"
    }
}''')

# Update all glossary anchor links to reflect current page numbering
def fix_anchor(m):
    slug = m.group(1)
    n = lower_keys[slug.replace('-', ' ').lower()]
    return f"glossary#{n:02}-{slug}"

root_dirs = ["01 Cloud Platform/", "02 Local Platform/", "03 Writing Algorithms/",
             "04 Research Environment/", "05 Lean CLI/", "06 LEAN Engine/", "Resources/"]

for filename in chain.from_iterable(
    iglob(root_dir + f"**/*.{ext}", recursive=True)
    for root_dir in root_dirs
    for ext in ("html", "php", "json")
):
    filename = os.path.normpath(filename)
    content = open(filename, 'r', encoding="utf-8", errors='replace').read()
    if "glossary#" not in content:
        continue
    new_content = re.sub(r"glossary#\d+-([\w-]+)", fix_anchor, content)
    with open(filename, 'w', encoding="utf-8") as f:
        f.write(new_content)
