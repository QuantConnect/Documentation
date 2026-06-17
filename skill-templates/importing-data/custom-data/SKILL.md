---
name: custom-data
description: >
  Use when adding a custom or external data source to a QuantConnect/LEAN
  algorithm. Triggers: custom data reader, external dataset, py`PythonData`cs`BaseData`,
  CSV, JSON, XML, ZIP, REST endpoint, Object Store, linked data, unlinked signals,
  custom universes, or local files for a QC strategy. Skip existing QC datasets
  subscribed with py`add_data`cs`AddData`, unless writing a custom reader.
---
# Custom Data in QuantConnect / LEAN
Build a custom reader, wire it into py`main.py`cs`Main.cs`, and verify rows load. Keep the reader in py`snake_case.py`cs`PascalCase.cs`. Use py`add_data`cs`AddData` only after the reader type is defined or imported.
## 1. Identify the data shape
- Source: remote file URL, REST endpoint for live mode, local file to upload, or existing Object Store key.
- Format: CSV, JSON, XML, ZIP, or line-based text. For JSON, decide whether the payload is one record, newline-delimited records, or an array that unfolds into many records.
- Scope: unlinked standalone signal, linked stream for an existing QC asset, one symbol per subscription, or custom universe with many symbols per date.
- Coverage: first date, last date, resolution, time zone, and whether live/backtest sources differ.
- Fields: timestamp, numeric fields, py`value`cs`Value`, plus string, bool, category, URL, and nullable fields that need typed properties or dynamic fields.
- Storage: for Cloud Object Store use `lean cloud object-store set <key> <path>` from an initialized Lean workspace; for local backtests use `lean object-store set` or copy into the workspace `storage` folder with the intended key path.
## 2. Choose the reader pattern
- Regular unlinked: standalone symbol such as weather or macro data; remote file or Object Store.
- Regular linked: data describes an existing security; subscribe to the security first, then pass its `Symbol` to py`add_data`cs`AddData`.
- Dual source: branch on py`is_live_mode`cs`isLiveMode` only when backtests use files and live trading polls REST.
- Unfolding collection: JSON array or one line yields many records; use py`FileFormat.UNFOLDING_COLLECTION`cs`FileFormat.UnfoldingCollection`.
- ZIP: use py`FileFormat.ZIP_ENTRY_NAME`cs`FileFormat.ZipEntryName` and `archive.zip#inner.csv`; works with remote files and Object Store.
- Universe: dated file emits symbols; verify selection counts instead of single-symbol history.
Do not use py`try`cs`try` / py`except`cs`catch` to hide parser errors. Return py`None`cs`null` only for known skipped records: blanks, headers, comments, or malformed optional rows the user explicitly wants ignored.
## 3. Minimal reader
Replace class name, key/URL, date parsing, fields, and value index. For remote files, switch to py`SubscriptionTransportMedium.REMOTE_FILE`cs`SubscriptionTransportMedium.RemoteFile`.
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
```csharp
using QuantConnect.Data;
using System;
using System.Globalization;
public class MyCustomData : BaseData
{
    public decimal Signal { get; set; }
    public override SubscriptionDataSource GetSource(SubscriptionDataConfig config, DateTime date, bool isLiveMode)
    {
        return new SubscriptionDataSource("custom-data/my-dataset.csv", SubscriptionTransportMedium.ObjectStore);
    }
    public override BaseData Reader(SubscriptionDataConfig config, string line, DateTime date, bool isLiveMode)
    {
        if (string.IsNullOrWhiteSpace(line) || !char.IsDigit(line[0]))
            return null;
        var csv = line.Split(',');
        var data = new MyCustomData { Symbol = config.Symbol };
        data.Time = DateTime.ParseExact(csv[0], "yyyy-MM-dd", CultureInfo.InvariantCulture);
        data.EndTime = data.Time.AddDays(1);
        data.Signal = decimal.Parse(csv[1], CultureInfo.InvariantCulture);
        data.Value = data.Signal;
        return data;
    }
}
```
## 4. Subscribe, trade, verify
Store custom symbols and read from the custom symbol, not the underlying asset. For non-universe readers, add one history row-count check in py`on_end_of_algorithm`cs`OnEndOfAlgorithm`.
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
```csharp
public class MyAlgorithm : QCAlgorithm
{
    private Symbol _equity;
    private Symbol _signal;
    public override void Initialize()
    {
        SetStartDate(2020, 1, 1);
        SetEndDate(2020, 2, 1);
        _equity = AddEquity("SPY", Resolution.Daily).Symbol;
        _signal = AddData<MyCustomData>("SIGNAL", Resolution.Daily).Symbol;
    }
    public override void OnData(Slice slice)
    {
        if (!slice.ContainsKey(_signal)) return;
        SetHoldings(_equity, slice.Get<MyCustomData>(_signal).Value > 0 ? 1 : 0);
    }
    public override void OnEndOfAlgorithm()
    {
        var history = History<MyCustomData>(_signal, StartDate, Time);
        Log($"History rows: {history.Count()}");
    }
}
```
For linked custom data, subscribe to the asset first, pass its `Symbol` to py`add_data`cs`AddData`, trade the asset symbol, and read the custom symbol only for signals.
```python
self._asset = self.add_equity("AAPL", Resolution.DAILY).symbol
self._signal = self.add_data(MyCustomData, self._asset, Resolution.DAILY).symbol
if self._signal in data and data[self._signal].value > 0:
    self.set_holdings(self._asset, 1)
```
```csharp
_asset = AddEquity("AAPL", Resolution.Daily).Symbol;
_signal = AddData<MyCustomData>(_asset, Resolution.Daily).Symbol;
if (slice.ContainsKey(_signal) && slice.Get<MyCustomData>(_signal).Value > 0)
    SetHoldings(_asset, 1);
```
## 5. JSON, ZIP, live, and universe notes
- JSON: use py`import json`cs`using Newtonsoft.Json.Linq;`, parse named fields, preserve non-numeric fields, set py`value`cs`Value` from the requested numeric signal, and fail loudly on unexpected shape.
- ZIP: point `SubscriptionDataSource` at `custom-data/signals.zip#signals.csv` with py`SubscriptionTransportMedium.OBJECT_STORE, FileFormat.ZIP_ENTRY_NAME`cs`SubscriptionTransportMedium.ObjectStore, FileFormat.ZipEntryName`; the reader receives extracted lines.
```python
def get_source(self, config, date, is_live_mode):
    return SubscriptionDataSource(
        "custom-data/signals.zip#signals.csv",
        SubscriptionTransportMedium.OBJECT_STORE,
        FileFormat.ZIP_ENTRY_NAME
    )
```
```csharp
public override SubscriptionDataSource GetSource(SubscriptionDataConfig config, DateTime date, bool isLiveMode)
{
    return new SubscriptionDataSource(
        "custom-data/signals.zip#signals.csv",
        SubscriptionTransportMedium.ObjectStore,
        FileFormat.ZipEntryName);
}
```
- Live/backtest split: branch in py`get_source`cs`GetSource` only when the source differs; return identical parsed objects from both paths.
- Arrays/unfolding: keep the requested JSON array shape. Do not convert it to JSONL or CSV unless the user explicitly asks. Store Object Store JSON array files as one-line/minified JSON under the requested key, and do not change Object Store keys during debugging unless you report the change. Use py`FileFormat.UNFOLDING_COLLECTION`cs`FileFormat.UnfoldingCollection`, parse the array, sort emitted objects by py`end_time`cs`EndTime`, and return py`BaseDataCollection(objects[-1].end_time, config.symbol, objects)`cs`new BaseDataCollection(objects.Last().EndTime, config.Symbol, objects)`.
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
```csharp
using Newtonsoft.Json.Linq;
using System.Collections.Generic;
using System.Globalization;
using System.Linq;

public override SubscriptionDataSource GetSource(SubscriptionDataConfig config, DateTime date, bool isLiveMode)
{
    return new SubscriptionDataSource(
        "custom-data/custom-news-releases.json",
        SubscriptionTransportMedium.ObjectStore,
        FileFormat.UnfoldingCollection);
}

public override BaseData Reader(SubscriptionDataConfig config, string line, DateTime date, bool isLiveMode)
{
    var objects = new List<BaseData>();
    foreach (var row in JArray.Parse(line))
    {
        var data = new MyCustomData { Symbol = config.Symbol };
        data.Time = DateTime.ParseExact(row.Value<string>("date"), "yyyy-MM-dd", CultureInfo.InvariantCulture);
        data.EndTime = data.Time.AddDays(1);
        data.Headline = row.Value<string>("headline");
        data.Impact = row.Value<decimal>("impact");
        data.Value = data.Impact;
        objects.Add(data);
    }
    objects = objects.OrderBy(x => x.EndTime).ToList();
    return objects.Count > 0 ? new BaseDataCollection(objects.Last().EndTime, config.Symbol, objects) : null;
}
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
