import os
from os.path import isfile, join
import pandas as pd
from pathlib import Path

SKIP = [
    "01 Cloud Platform",
    "02 Local Platform",
    join("03 Writing Algorithms", "03 Securities", "99 Asset Classes", "01 US Equity", "09 Market Hours"),
    join("03 Writing Algorithms", "03 Securities", "99 Asset Classes", "02 Equity Options", "04 Market Hours"),
    join("03 Writing Algorithms", "03 Securities", "99 Asset Classes", "03 Crypto", "05 Market Hours"),
    join("03 Writing Algorithms", "03 Securities", "99 Asset Classes", "04 Crypto Futures", "05 Market Hours"),
    join("03 Writing Algorithms", "03 Securities", "99 Asset Classes", "05 Forex", "04 Market Hours"),
    join("03 Writing Algorithms", "03 Securities", "99 Asset Classes", "06 Futures", "04 Market Hours"),
    join("03 Writing Algorithms", "03 Securities", "99 Asset Classes", "07 Future Options", "04 Market Hours"),
    join("03 Writing Algorithms", "03 Securities", "99 Asset Classes", "08 Index", "04 Market Hours"),
    join("03 Writing Algorithms", "03 Securities", "99 Asset Classes", "09 Index Options", "04 Market Hours"),
    join("03 Writing Algorithms", "03 Securities", "99 Asset Classes", "10 CFD", "04 Market Hours"),
    join("03 Writing Algorithms", "03 Securities", "99 Asset Classes", "99 India Equity"),
    join("03 Writing Algorithms", "14 Datasets"),
    join("03 Writing Algorithms", "22 Trading and Orders", "07 Option Strategies"),
    "02 Supported Models",
    join("03 Writing Algorithms", "28 Indicators", "01 Supported Indicators"),
    join("03 Writing Algorithms", "98 API Reference"),
    join("04 Research Environment", "12 Applying Research"),
    "05 Lean CLI",
    "06 LEAN Engine",
    "07 Meta",
    "08 Drafts",
    "90 QuantConnect Home",
    "91 LEAN Home"
]

def find_deepest_subdirectories_with_digit_start(root_directory):
    deepest_dirs = []

    # List all entries in the root directory
    for entry in os.listdir(root_directory):
        entry_path = os.path.join(root_directory, entry)

        # Check if it's a directory and starts with a digit
        if os.path.isdir(entry_path) and entry[0].isdigit():
            # Check if this directory has subdirectories
            subdirs = [d for d in os.listdir(entry_path) if os.path.isdir(os.path.join(entry_path, d))]
            if subdirs:
                deepest_dirs.append(entry_path)

    return deepest_dirs

def find_deepest_directories(directory):
    deepest_dirs = []

    # Walk through the directory
    for root, dirs, files in os.walk(directory):
        if [x for x in files if not x.endswith(".json") and not x.startswith("00")]:
            deepest_dirs.append(root)  # Add to the existing list

    return deepest_dirs

def find_examples_html_in_directories(directories):
    any_examples = {}
    
    for directory in directories:
        files = [f for f in os.listdir(directory) if isfile(join(directory, f))]
        directory = os.path.relpath(directory, start=str(Path.cwd()))
        example_file = [file for file in files if file.endswith("Examples.html")]
        if not example_file:
            any_examples[directory] = False
            
        else:
            with open(join(directory, example_file[0]), "r", encoding="utf-8") as file:
                if file.read().strip().startswith("<div class=\"example-fieldset\">"):
                    any_examples[directory] = False
                    continue
                
            any_examples[directory] = True

    return any_examples

roots = find_deepest_subdirectories_with_digit_start(Path.cwd())
deepest_dirs = []
for sub_root in roots:
    deepest_dirs.extend(find_deepest_directories(sub_root))
filtered_deepest_dirs = [x for x in deepest_dirs if all(y not in x for y in SKIP)]
any_examples = find_examples_html_in_directories(filtered_deepest_dirs)

df = pd.DataFrame({"Directory": list(any_examples.keys()), "Has Examples": list(any_examples.values())})
df.to_csv("examples-check/has_examples.csv")