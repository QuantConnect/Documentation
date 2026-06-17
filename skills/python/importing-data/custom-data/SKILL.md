---
name: custom-data
description: >
  Use when adding a custom or external data source to a QuantConnect/LEAN
  algorithm. Triggers: custom data reader, external dataset, `PythonData`,
  CSV, JSON, XML, ZIP, REST endpoint, Object Store, linked data, unlinked signals,
  custom universes, or local files for a QC strategy. Skip existing QC datasets
  subscribed with `add_data`, unless writing a custom reader.
---
# Custom Data in QuantConnect / LEAN
Build a custom reader, wire it into `main.py`, and verify rows load. Keep the reader in `snake_case.py`. Use `add_data` only after the reader type is defined or imported.
## 1. Identify the data shape
- Source: remote file URL, REST endpoint for live mode, local file to upload, or existing Object Store key.
- Format: CSV, JSON, XML, ZIP, or line-based text. For JSON, decide whether the payload is one record, newline-delimited records, or an array that unfolds into many records.
- Scope: unlinked standalone signal, linked stream for an existing QC asset, one symbol per subscription, or custom universe with many symbols per date.
- Coverage: first date, last date, resolution, time zone, and whether live/backtest sources differ.
- Fields: timestamp, numeric fields, `value`, plus string, bool, category, URL, and nullable fields that need typed properties or dynamic fields.
- Storage: for Cloud Object Store use `lean cloud object-store set <key> <path>` from an initialized Lean workspace; for local backtests use `lean object-store set` or copy into the workspace `storage` folder with the intended key path.
## 2. Choose the reader pattern
- Regular unlinked: standalone symbol such as weather or macro data; remote file or Object Store.
- Regular linked: data describes an existing security; subscribe to the security first, then pass its `Symbol` to `add_data`.
- Dual source: branch on `is_live_mode` only when backtests use files and live trading polls REST.
- Unfolding collection: JSON array or one line yields many records; use `FileFormat.UNFOLDING_COLLECTION`.
- ZIP: use `FileFormat.ZIP_ENTRY_NAME` and `archive.zip#inner.csv`; works with remote files and Object Store.
- Universe: dated file emits symbols; verify selection counts instead of single-symbol history.
Do not use `try` / `except` to hide parser errors. Return `None` only for known skipped records: blanks, headers, comments, or malformed optional rows the user explicitly wants ignored.
## 3. Minimal reader
Replace class name, key/URL, date parsing, fields, and value index. For remote files, switch to `SubscriptionTransportMedium.REMOTE_FILE`.
```python
# region imports
from AlgorithmImports import *
# endregion
class MyCustomData(PythonData):
    def get_source(self, config, date, is_live_mode):
        return SubscriptionDataSource("custom-data/my-dataset.csv", SubscriptionTransportMedium.OBJECT_STORE)
    def reader(self, config, line, date, is_live_mode):
        if not line.strip() or not line[0].isdigit():
            return None
        csv = line.split(",")
        data = MyCustomData()
        data.symbol = config.symbol
        data.time = datetime.strptime(csv[0], "%Y-%m-%d")
        data.end_time = data.time + timedelta(days=1)
        data["signal"] = float(csv[1])
        data.value = float(csv[1])
        return data
```
## 4. Subscribe, trade, verify
Store custom symbols and read from the custom symbol, not the underlying asset. For non-universe readers, add one history row-count check in `on_end_of_algorithm`.
```python
class MyAlgorithm(QCAlgorithm):
    def initialize(self):
        self.set_start_date(2020, 1, 1)
        self.set_end_date(2020, 2, 1)
        self._equity = self.add_equity("SPY", Resolution.DAILY).symbol
        self._signal = self.add_data(MyCustomData, "SIGNAL", Resolution.DAILY).symbol
    def on_data(self, data):
        if self._signal not in data:
            return
        self.set_holdings(self._equity, 1 if data[self._signal].value > 0 else 0)
    def on_end_of_algorithm(self):
        history = self.history(MyCustomData, self._signal, self.start_date, self.time)
        self.log(f"History rows: {len(history)}")
```
For linked custom data, subscribe to the asset first, pass its `Symbol` to `add_data`, trade the asset symbol, and read the custom symbol only for signals.
```python
self._asset = self.add_equity("AAPL", Resolution.DAILY).symbol
self._signal = self.add_data(MyCustomData, self._asset, Resolution.DAILY).symbol
if self._signal in data and data[self._signal].value > 0:
    self.set_holdings(self._asset, 1)
```
## 5. JSON, ZIP, live, and universe notes
- JSON: use `import json`, parse named fields, preserve non-numeric fields, set `value` from the requested numeric signal, and fail loudly on unexpected shape.
- ZIP: point `SubscriptionDataSource` at `custom-data/signals.zip#signals.csv` with `SubscriptionTransportMedium.OBJECT_STORE, FileFormat.ZIP_ENTRY_NAME`; the reader receives extracted lines.
```python
def get_source(self, config, date, is_live_mode):
    return SubscriptionDataSource(
        "custom-data/signals.zip#signals.csv",
        SubscriptionTransportMedium.OBJECT_STORE,
        FileFormat.ZIP_ENTRY_NAME
    )
```
- Live/backtest split: branch in `get_source` only when the source differs; return identical parsed objects from both paths.
- Arrays/unfolding: keep the requested JSON array shape. Do not convert it to JSONL or CSV unless the user explicitly asks. Store Object Store JSON array files as one-line/minified JSON under the requested key, and do not change Object Store keys during debugging unless you report the change. Use `FileFormat.UNFOLDING_COLLECTION`, parse the array, sort emitted objects by `end_time`, and return `BaseDataCollection(objects[-1].end_time, config.symbol, objects)`.
```python
import json

def get_source(self, config, date, is_live_mode):
    return SubscriptionDataSource(
        "custom-data/custom-news-releases.json",
        SubscriptionTransportMedium.OBJECT_STORE,
        FileFormat.UNFOLDING_COLLECTION
    )

def reader(self, config, line, date, is_live_mode):
    rows = json.loads(line)
    objects = []
    for row in rows:
        data = MyCustomData()
        data.symbol = config.symbol
        data.time = datetime.strptime(row["date"], "%Y-%m-%d")
        data.end_time = data.time + timedelta(days=1)
        data["headline"] = row["headline"]
        data["impact"] = float(row["impact"])
        data.value = data["impact"]
        objects.append(data)
    objects.sort(key=lambda x: x.end_time)
    return BaseDataCollection(objects[-1].end_time, config.symbol, objects) if objects else None
```
- Universes: emit symbols, log selected count at each rebalance, and skip the single-symbol history check.
## 6. Compile and backtest loop
1. Compile first; fix every build error before backtesting.
2. Backtest the smallest date window that covers one representative record. Use the full file only for date-dependent file selection, unfolding behavior, or live/backtest branching.
3. Confirm `History rows: N` with `N > 0` for non-universe readers.
4. If `N == 0`, inspect the first real record and source path, then manually walk date parsing, transport medium, resolution, and start/end dates.
5. Preserve the user's trading rule. If data loads but no orders appear, log/report condition pass counts and explain whether the provided sample data satisfies the rule. Only relax strategy logic if the user explicitly asks for a smoke-test trade.
6. Before finishing, verify or mark not applicable: linked, unlinked, universe, unfolding collection, ZIP, remote file, Object Store, and target language.
7. Report compile result, backtest result, loaded row count, order count, and Object Store key or remote URL.
