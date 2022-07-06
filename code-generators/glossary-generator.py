import os
import shutil

dir = '02 Writing Algorithms/01 Key Concepts/09 Glossary'
if os.path.exists(dir):
    shutil.rmtree(dir)
os.makedirs(dir)

source = open("Resources/glossary.php", 'r', encoding="utf-8").readlines()
active = True
keys = []
backslash = "\""

for line in source:
    if ");" in line:
        active = False
    
    if active and "=>" in line:
        keys.append(line.split("=>")[0].strip())

for i, key in enumerate(keys):
    with open(f"{dir}/{i+2:02} {key.replace(backslash, '').title()}.php", "w", encoding="utf-8") as php:
        php.write(f'''<?php 
include(DOCS_RESOURCES."/glossary.php");
$getGlossaryTermHTML({key});
?>''')

with open(f"{dir}/01 Introduction.php", "w", encoding="utf-8") as php:       
    php.write('<p>This page defines terms in QuantConnect products and documentation.</p>')