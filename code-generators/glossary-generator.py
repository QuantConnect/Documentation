import os
from glob import iglob
from shutil import rmtree

dir = '03 Writing Algorithms/01 Key Concepts/99 Glossary'
if os.path.exists(dir):
    rmtree(dir)
os.makedirs(dir, exist_ok=True)

# Get glossary terms from Resources/glossary/ directory in alphabetical order
glossary_dir = 'Resources/glossary'
files = sorted([f for f in os.listdir(glossary_dir) if f.endswith('.html') and f != 'include-mathjax.html'], key=str.lower)

mathjax = open(f"{glossary_dir}/include-mathjax.html", "r", encoding="utf-8").read()

keys = {}
lower_keys = {}
i = 2

proper_nouns = {'sharpe', 'sortino', 'treynor', 'pearson', 'probabilistic'}
for f in files:
    term = f.replace('.html', '')
    words = term.replace('-', ' ').split()
    display_name = ' '.join(w.capitalize() if w in proper_nouns else w for w in words)
    keys[term] = (i, display_name)
    lower_keys[display_name.lower()] = i
    i += 1

# Write the glossary pages
for term, (n, display_name) in keys.items():
    content = open(f"{glossary_dir}/{term}.html", "r", encoding="utf-8").read()
    with open(f"{dir}/{n:02} {display_name}.html", "w", encoding="utf-8") as html:
        html.write(content)

with open(f"{dir}/01 Introduction.html", "w", encoding="utf-8") as html:
    html.write(f"{mathjax}\n<p>This page defines terms in QuantConnect products and documentation.</p>")

with open(f"01 Cloud Platform/11 Optimization/03 Objectives/01 Introduction.html", "w", encoding="utf-8") as html:
    html.write(f"{mathjax}\n<p>An optimization objective is the performance metric that's used to compare the backtest performance of different parameter values. The optimizer currently supports the compound annual growth rate (CAGR), drawdown, Sharpe ratio, and Probabilistic Sharpe ratio (PSR) as optimization objectives. When the optimization job finishes, the results page displays the value of the objective with respect to the parameter values.</p>")

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
