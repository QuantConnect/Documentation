import os
from glob import iglob
from shutil import rmtree

dir = '03 Writing Algorithms/01 Key Concepts/99 Glossary'
if os.path.exists(dir):
    rmtree(dir)
os.makedirs(dir, exist_ok=True)

# Get glossary terms from Resources/glossary/ directory in alphabetical order
glossary_dir = 'Resources/glossary'
files = sorted([f for f in os.listdir(glossary_dir) if f.endswith('.php')], key=str.lower)

keys = {}
lower_keys = {}
i = 2

for f in files:
    term = f.replace('.php', '')
    keys[term] = i
    lower_keys[term.replace('-', ' ').lower()] = i
    i += 1

# Write the glossary pages
for term, n in keys.items():
    with open(f"{dir}/{n:02} {term}.php", "w", encoding="utf-8") as php:
        php.write(f'''<? include(DOCS_RESOURCES."/glossary/{term}.php"); ?>''')

with open(f"{dir}/01 Introduction.php", "w", encoding="utf-8") as php:
    php.write("""<p>This page defines terms in QuantConnect products and documentation.</p>

<script type="text/x-mathjax-config">
    MathJax.Hub.Config({tex2jax: {inlineMath: [['$','$'], ['\\\\(','\\\\)']]}});
</script>
<script type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=TeX-AMS-MML_HTMLorMML">
</script>""")

with open(f"{dir}/metadata.json", "w", encoding="utf-8") as json:
    json.write('''{
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

# Search and replace all links
quotation = "'"
double_quotation = "\""
for root_dir in ["01 Cloud Platform/", "02 Local Platform/", "03 Writing Algorithms/", "04 Research Environment/", "05 Lean CLI/", "06 LEAN Engine/", "Resources/"]:
    for filename in list(iglob(root_dir + "**/*.html", recursive=True)) + \
                    list(iglob(root_dir + "**/*.php", recursive=True)) + \
                    list(iglob(root_dir + "**/*.json", recursive=True)):
        filename = filename.replace("\\", os.path.sep).replace("/", os.path.sep)
        content = open(filename, 'r', encoding="utf-8").read()

        if "glossary#" in content:
            content = [x if k == 0 else x[2:] for k, x in enumerate(content.split("glossary#"))]
            items = [x.split(quotation)[0].split(double_quotation)[0].replace("-", " ").strip() for x in content[1:]]
            page_no = [lower_keys[item.lower()] for item in items]

            new_content = ""
            for k, text in enumerate(content):
                if k > 0:
                    new_content += f"glossary#{page_no[k-1]:02}"
                new_content += text

            with open(filename, 'w', encoding="utf-8") as file:
                file.write(new_content)
