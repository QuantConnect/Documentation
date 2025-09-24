from pathlib import Path
import shutil
import importlib.util

from overload_merger import OverloadMerger
from documentation import Documentation

# Inputs:
DEFAULT_DST = "./stubs"

def copy_dir(src, dst):
    # If the destination dir already exists, remove it.
    dst = Path(dst)
    if dst.exists():
        shutil.rmtree(dst)
    # Copy everything from the source to the destination.
    shutil.copytree(Path(src), dst)
    
def main():
    # Copy the quantconnect-python-stubs files.
    copy_dir(
        importlib.util.find_spec("QuantConnect").submodule_search_locations[0], 
        DEFAULT_DST + '/QuantConnect'
    )
    # Write the non-overload (concrete) method signatures to sibling
    # .py files in the stubs directory.
    OverloadMerger().process_dir(DEFAULT_DST)
    # Write the markdown files and the nav tree.
    Documentation().create_docs(Path(DEFAULT_DST), Path('./docs'))

if __name__ == "__main__":
    main()
