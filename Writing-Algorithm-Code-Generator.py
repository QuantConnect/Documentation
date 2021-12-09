import json
import pathlib
from urllib.request import urlopen

source = {"CSharp": "http://cdn.quantconnect.com.s3.us-east-1.amazonaws.com/terminal/cache/api/csharp_tree.json",
          "Python": "http://cdn.quantconnect.com.s3.us-east-1.amazonaws.com/terminal/cache/api/python_tree.json"}

for json_file in source.values():
    doc = json.load(urlopen(source))

    qc_algorithm = json_file["tree"]["core"]["data"][0]
    
    # Create path if not exist
    main_dir = f'02 Writing Algorithms/04 API Reference'
    destination_folder = pathlib.Path(main_dir)
    destination_folder.mkdir(parents=True, exist_ok=True)