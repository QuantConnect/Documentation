import glob
import os
import shutil

dir = '02 Writing Algorithms/01 Getting Started/09 Glossary'
if os.path.exists(dir):
    shutil.rmtree(dir)
os.makedirs(dir)

# Get items in the dict
source = open("Resources/glossary.php", 'r', encoding="utf-8").readlines()
active = True
keys = {}
lower_keys = {}
quotation = "'"
double_quotation = "\""
i = 2

for line in source:
    if ");" in line:
        active = False
    
    if active and "=>" in line:
        keys[line.split("=>")[0].strip()] = i
        lower_keys[line.split("=>")[0].strip().replace('-', ' ').replace(quotation, '').replace(double_quotation, '').lower()] = i
        i += 1

# Write the glossary pages
for key, n in keys.items():
    with open(f"{dir}/{n:02} {key.replace(quotation, '').replace(double_quotation, '')}.php", "w", encoding="utf-8") as php:
        php.write(f'''<?php 
include(DOCS_RESOURCES."/glossary.php");
$getGlossaryTermHTML({key});
?>''')

with open(f"{dir}/01 Introduction.php", "w", encoding="utf-8") as php:       
    php.write('<p>This page defines terms in QuantConnect products and documentation.</p>')
    
# Search and replace all links
for root_dir in ["01 Our Platform/", "02 Writing Algorithms/", "03 Research Environment/", "05 Lean CLI/", "99 LEAN Engine/"]:
    for filename in list(glob.iglob(root_dir + "**/*.html", recursive=True)) + \
                    list(glob.iglob(root_dir + "**/*.php", recursive=True)) + \
                    list(glob.iglob(root_dir + "**/*.json", recursive=True)):
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