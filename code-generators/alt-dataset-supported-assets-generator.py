import glob
from pathlib import Path
import os
import shutil

DESTINATION = "Resources/datasets/supported-securities/alternatives"
PROCESSED_DIR = "/nas/alternative"
DATASETS = {
    "brain": ["rankings", "sentiment", "report_10k", "report_all"],
    "quiver": ["wallstreetbets", "twitter", "wikipedia", "congresstrading", "governmentcontracts", "cnbc", "lobbying"],
}

def TableCreation(files):
    html = """<table class="table qc-table table-reflow ticker-table hidden-xs">
<thead>
<tr><th colspan="6">Assets Available</th></tr>
</thead>
<tbody>
<tr>"""

    i = 0

    for file in files:
        html += f"<td>{file.split('.')[0]}</td>"
        i += 1
        
        if i == 6:
            html += "</tr>\n<tr>"
            i = 0
    
    html += "\n</tbody>\n</table>"
    
    return html

if __name__ == '__main__':
    DESTINATION = Path(DESTINATION)
    if os.path.exists(DESTINATION):
        shutil.rmtree(DESTINATION)
    DESTINATION.mkdir(parents=True, exist_ok=True)
        
    for vendor, datasets in DATASETS.items():
        for dataset in datasets:
            dataset_dir = f"{PROCESSED_DIR}/{vendor.lower()}/{dataset.lower()}"
            if not os.path.exists(dataset_dir): 
                print("{dataset_dir} does not exist!")
                continue
            
            files = glob.glob(f"{dataset_dir}/*.csv")
            html_table = TableCreation(files)
            
            with open(DESTINATION / vendor.lower() / dataset.lower() / "support-assets.html") as html_file:
                html_file.write(html_table)