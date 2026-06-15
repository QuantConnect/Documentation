---
name: upload-object-store
description: >
  Prepares a local folder of files intended for the QuantConnect Object Store, with
  stable relative paths that can be used as Object Store keys. Invoke when any skill or
  the user needs to stage files for QC Object Store, organize Object Store content, or
  make local files ready for later addition to QC.
---

# /upload-object-store -- Prepare Local Object Store Folder

Prepare a local folder that contains the exact files and relative paths intended for
QuantConnect Object Store content. This skill does not send files to QuantConnect.
Algorithms can later read staged keys with
py`SubscriptionTransportMedium.OBJECT_STORE`cs`SubscriptionTransportMedium.ObjectStore`.

---

## Step 1 -- Gather inputs

Required information (ask if not already provided):
- Source file path or generation task
- Local Object Store folder path to populate
- Intended Object Store key or key prefix, for example `custom-data/nifty.json`
  or `custom-data/research-inputs/`

---

## Step 2 -- Choose the local layout

Use the local Object Store folder as the staging root. The path of each file relative to
that folder should match the intended Object Store key.

Examples:
- Local file: `<object-store-folder>/custom-data/nifty.json`
- Intended key: `custom-data/nifty.json`
- Local file: `<object-store-folder>/models/regime/model.pkl`
- Intended key: `models/regime/model.pkl`

---

## Step 3 -- Populate the folder

Create missing directories under the local Object Store folder. Copy, move, or generate
the requested files into their intended relative paths. Do not place temporary files,
logs, caches, credentials, notebooks with secrets, or unrelated project files in the
folder.

Prefer deterministic, portable formats when the algorithm will read the data later:
- `json`, `csv`, `parquet`, or compressed archives for datasets
- `pkl`, `joblib`, `onnx`, or framework-native formats for model artifacts
- small metadata sidecars such as `manifest.json` when file provenance matters

For live algorithms, keep individual objects below 50 MB when possible because live
environment access can be slower than research or backtesting access.

---

## Step 4 -- Verify the local result

List the staged files and confirm their relative paths match the intended Object Store
keys. Check that expected files exist, sizes are reasonable, and names use forward slash
key semantics instead of machine-specific absolute paths.

On success, report:
- Local Object Store folder: `<folder>`
- Staged keys: `<relative paths>`
- Any size or naming concerns
- The folder is ready for the user to add through QuantConnect Object Store tooling
