---
name: custom-data
description: >
  Use when adding a custom or external data source to a QuantConnect/LEAN
  algorithm. Triggers: "add custom data", "custom data reader", "external
  dataset", "BaseData", "PythonData", CSV, JSON, XML, ZIP, REST endpoint,
  Object Store data, linked data for an existing asset, standalone custom data,
  custom universe files, or a local file that should feed a QC strategy. Skip
  when the data is an existing QuantConnect dataset subscribed with add_data or
  AddData, unless the task is to write a custom reader.
---

# Custom Data in QuantConnect / LEAN

Use this skill to build a custom data reader, wire it into the algorithm, and
verify that the subscription actually produces rows. Keep the data reader in its
own file, keep the algorithm class in py`main.py`cs`Main.cs`, and iterate
compile plus backtest until the data loads and the strategy trades.

## 1. Identify the shape before coding

Ask or inspect enough input to answer all of these before generating code:

- Source: remote file URL, REST endpoint for live mode, local file staged for
  Object Store, or an existing Object Store key.
- Format: CSV, JSON, XML, ZIP, or another line-based format. For JSON, determine
  whether each payload is one record, newline-delimited records, or an array that
  must unfold into multiple records.
- Scope: one symbol per subscription, a linked stream for an existing QC asset,
  or a custom universe file with many symbols per date.
- Date coverage and resolution: first date, last date, daily, minute, or other.
- Fields: timestamp column, numeric fields, and which field should be
  py`value`cs`Value`.
- Live behavior: same source in backtests and live trading, or separate
  backtest and live sources.

If the user gives a local file and wants Object Store, read the
`upload-object-store` skill. Prepare a local staging folder where relative file
paths map to intended Object Store keys, then use those keys in
py`SubscriptionTransportMedium.OBJECT_STORE`cs`SubscriptionTransportMedium.ObjectStore`.

## 2. Choose the reader pattern

Use the simplest pattern that matches the data:

| Pattern | Use when | Source medium |
| --- | --- | --- |
| Regular unlinked | The data is its own standalone symbol. | Remote file or Object Store |
| Regular linked | The data describes an existing security such as AAPL. | Remote file or Object Store |
| Dual source | Backtests use a file and live trading polls an endpoint. | Branch on `is_live_mode` |
| Unfolding collection | One input line or JSON array yields many records. | Override collection behavior |
| ZIP | The source is an archive that must be opened and parsed. | Remote file |
| Universe | A dated file selects symbols instead of emitting one symbol stream. | Remote file or Object Store |

Do not use py`try` / `except`cs`try` / `catch` to hide parser errors. Return
py`None`cs`null` only for known skippable records such as blank lines, headers,
or comments.

## 3. File and naming rules

Derive a descriptive class name from the dataset:

| Language | Class | Reader file |
| --- | --- | --- |
| Python | `BitcoinData` | `bitcoin_data.py` |
| C# | `BitcoinData` | `BitcoinData.cs` |

In Python, import the reader in `main.py` after `from AlgorithmImports import *`:

```python
# region imports
from AlgorithmImports import *
from bitcoin_data import BitcoinData
# endregion
```

C# project files share the same namespace, so no extra import is needed for a
reader class in another project file.

Before writing code, apply the `code-quality` skill rules for imports,
subscription variables, comments, blank lines, and reader error handling.

## 4. Regular single-symbol reader

Use this pattern for a CSV-like single-symbol stream. Replace the URL or key,
class name, date parsing, fields, and value index.

```python
# region imports
from AlgorithmImports import *
# endregion


class MyCustomData(PythonData):

    def get_source(self, config, date, is_live_mode):
        return SubscriptionDataSource(
            "custom-data/my-dataset.csv",
            SubscriptionTransportMedium.OBJECT_STORE
        )

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

    public override SubscriptionDataSource GetSource(
        SubscriptionDataConfig config, DateTime date, bool isLiveMode)
    {
        return new SubscriptionDataSource(
            "custom-data/my-dataset.csv",
            SubscriptionTransportMedium.ObjectStore);
    }

    public override BaseData Reader(
        SubscriptionDataConfig config, string line, DateTime date, bool isLiveMode)
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

For remote files, use
py`SubscriptionTransportMedium.REMOTE_FILE`cs`SubscriptionTransportMedium.RemoteFile`
and the remote URL instead of the Object Store key.

## 5. Subscribe and verify history

Store the subscription and use it consistently. Add a history check in
py`on_end_of_algorithm`cs`OnEndOfAlgorithm` for non-universe custom data.

```python
class MyAlgorithm(QCAlgorithm):

    def initialize(self):
        self.set_start_date(2020, 1, 1)
        self.set_end_date(2023, 1, 1)
        self.set_cash(100000)
        self._equity = self.add_equity("SPY", Resolution.DAILY)
        self._custom_signal = self.add_data(MyCustomData, "SIGNAL", Resolution.DAILY)

    def on_data(self, data):
        if self._custom_signal not in data:
            return
        point = data[self._custom_signal]
        if point.value > 0:
            self.set_holdings(self._equity, 1)

    def on_end_of_algorithm(self):
        history = self.history(MyCustomData, self._custom_signal, self.start_date, self.time)
        self.log(f"History rows: {len(history)}")
```

```csharp
public class MyAlgorithm : QCAlgorithm
{
    private Symbol _equity;
    private Symbol _customSignal;

    public override void Initialize()
    {
        SetStartDate(2020, 1, 1);
        SetEndDate(2023, 1, 1);
        SetCash(100000);
        _equity = AddEquity("SPY", Resolution.Daily).Symbol;
        _customSignal = AddData<MyCustomData>("SIGNAL", Resolution.Daily).Symbol;
    }

    public override void OnData(Slice slice)
    {
        if (!slice.ContainsKey(_customSignal)) return;
        var point = slice.Get<MyCustomData>(_customSignal);
        if (point.Value > 0)
            SetHoldings(_equity, 1);
    }

    public override void OnEndOfAlgorithm()
    {
        var history = History<MyCustomData>(_customSignal, StartDate, Time);
        Log($"History rows: {history.Count()}");
    }
}
```

For linked custom data, subscribe to the underlying security first, then pass the
linked ticker to the custom data subscription. In the trading logic, trade the
underlying security and read the custom symbol only for signals.

## 6. Dual backtest and live sources

Use `is_live_mode` only when the source genuinely differs between backtests and
live trading. Keep parser output identical in both paths.

```python
def get_source(self, config, date, is_live_mode):
    if is_live_mode:
        return SubscriptionDataSource("https://example.com/live", SubscriptionTransportMedium.REST)
    return SubscriptionDataSource("custom-data/history.csv", SubscriptionTransportMedium.OBJECT_STORE)
```

```csharp
public override SubscriptionDataSource GetSource(
    SubscriptionDataConfig config, DateTime date, bool isLiveMode)
{
    if (isLiveMode)
        return new SubscriptionDataSource("https://example.com/live", SubscriptionTransportMedium.Rest);
    return new SubscriptionDataSource("custom-data/history.csv", SubscriptionTransportMedium.ObjectStore);
}
```

## 7. JSON, ZIP, and universe notes

- JSON readers must explicitly import or use the JSON library for the target
  language. Parse known fields directly and fail loudly on unexpected shape.
- ZIP readers must open the archive, select the intended inner file, and parse
  that file with the same explicit guards used for plain files.
- For JSON arrays or files that contain many records for the same time, use the
  QuantConnect collection or unfolding pattern so every record becomes its own
  data point.
- For custom universes, emit symbols from the universe reader and omit the
  single-symbol history check. Verify by logging the selected count at each
  rebalance, not by logging every symbol.

## 8. Compile and backtest loop

1. Compile the project and fix every build error before running a backtest.
2. Backtest over dates covered by the custom data file.
3. Confirm the final log contains `History rows: N` with `N > 0` for
   non-universe readers.
4. If `N == 0`, fetch or open the first real record and manually walk through
   the reader logic. Check date parsing, source path, transport medium,
   resolution, and start/end dates.
5. If data loads but no orders appear, add one temporary event-level log in
   py`on_data`cs`OnData` to prove the handler fires, then adjust the strategy
   condition. Remove noisy diagnostic logs before finishing.
6. Report the compile result, backtest result, loaded row count, order count,
   and Object Store key or remote URL used.
