---
name: custom-data
description: >
  Integrates custom or external data sources into a QuantConnect algorithm -- from data reader
  implementation through compile, backtest, and history verification. Use this skill whenever
  the user wants to add custom data to a QC strategy, including phrases like:
  "add custom data [to my algorithm]", "Generate a strategy using custom data [link/file]",
  "implement a custom data reader for [source]", "create a [CSV/JSON/XML/ZIP] data reader",
  "integrate this dataset into QC", "I have a data file or URL I want to use in my algorithm",
  "set up streaming data", "link [dataset] to my QC strategy", or any mention of importing
  external datasets, BaseData, PythonData, or non-standard data sources into QuantConnect.
  Also trigger for: "add custom data", "use external data", "custom data reader", "stream data",
  "import data from URL", "universe from custom file".
---

# /custom-data -- QuantConnect Custom Data Integration

Guides Claude through the full workflow: gather requirements -> generate code -> compile -> backtest -> verify history.

---

## Step 1 -- Gather Data Information

Ask the user these questions (use AskUserQuestion where you can; otherwise ask sequentially):

1. **Source**: Where does the data come from?
   - Remote URL (HTTP/HTTPS file download)
   - REST endpoint (live polling -- returns one data point per call)
   - Local file path (will be uploaded to Object Store)
   - Already in the QuantConnect Object Store (provide the key)

2. **Format**: CSV / JSON / XML / ZIP
   - If ZIP: what format is inside the archive?
   - If JSON: is each file a single object/line, or a JSON array of multiple records?
     - Array per file -> flag as **UnfoldingCollection**

3. **Ticker scope**:
   - Does the file/endpoint cover **one ticker** (e.g., one row per date for BTC)?
     -> Regular custom security data
   - Does the file cover **multiple tickers** in one file (e.g., a daily snapshot with rows for many stocks)?
     -> Custom Universe

4. **Asset linkage** (skip for universes):
   - Is this data describing an **existing QC asset** (e.g., C-suite data tied to AAPL)?
     -> **Linked** -- the custom data symbol references the parent equity symbol
   - Is this data **completely standalone** (e.g., weather data, economic macro)?
     -> **Unlinked** -- creates its own new symbol

5. **Dual readers**:
   - Do you need **different data sources** for backtesting vs. live trading?
     -> Yes: prompt for backtest source first, then live source separately.
     -> No: single source; use `is_live_mode` branching only if needed.

6. **Ticker name**: What ticker/symbol string should represent this data? (e.g., `"BTC"`, `"WEATHER"`)

7. **Data properties**: What numeric fields does each record have? Which field is the primary `Value`?

8. **Date coverage**: What date range does the data file cover?

---

## Step 2 -- Object Store Decision

Ask: "Would you like to upload this data file to the QuantConnect Object Store?"

- **Yes** -> record `USE_OBJECT_STORE = true`. After generating code, upload the file in Step 5.
- **No** -> `USE_OBJECT_STORE = false`. Use `RemoteFile` or `Rest` transport.

---

## Step 3 -- Generate the Code

### Style rules

Apply these rules to all generated code:

- **Imports (Python)**: `from AlgorithmImports import *` only. For `json`, `csv`, `xml.etree.ElementTree`, `zipfile`, and `io`: add an explicit import after `from AlgorithmImports import *` when the reader uses it.
- **Imports (C#)**: Leave all project `using` statements as-is.
- **Subscription variable**: Store the `Security` object returned by `AddData` / `AddEquity` directly -- never append `.Symbol` at assignment. Pass the variable directly everywhere (`History()`, `SetHoldings()`, `Liquidate()`, dict key). Use `slice.ContainsKey(x)` to check presence.
- **Comments**: Capital first letter, space after `#` / `//`, ends with a period.
- **Blank lines (Python)**: 2 blank lines before each class, 1 before each method, none inside method bodies.
- **Blank lines (C#)**: 1 blank line between methods, none inside method bodies.
- **Error handling**: No `try`/`except` or `try`/`catch`. Use explicit guards (`if not line.strip()`, `if string.IsNullOrWhiteSpace(line)`) for skippable lines. Let real parse errors propagate.

Use the templates at the bottom of this file. Pick the tag from the decision matrix:

| Data type | Linkage | Transport | Template tag |
|---|---|---|---|
| Regular security | Unlinked | Remote URL | `REGULAR_UNLINKED_REMOTE` |
| Regular security | Unlinked | Object Store | `REGULAR_UNLINKED_OBJSTORE` |
| Regular security | Linked | Remote URL | `REGULAR_LINKED` |
| Regular security | Any | REST (live only) | `DUAL_READER` |
| Universe | -- | Remote URL | `UNIVERSE` |
| JSON array per file | Unlinked | Any | `UNFOLDING` |
| ZIP archive | Any | Remote URL | `ZIP` |

### Naming and file conventions

Derive the class name from the dataset, then apply language conventions for the file name:

| Language | Class name | File name |
|---|---|---|
| Python | `BitcoinData` | `bitcoin_data.py` |
| C# | `BitcoinData` | `BitcoinData.cs` |

The data reader class always goes in its own file -- never inline in `main.py` / `Main.cs`.

### Customize the template

- Replace `MyCustomData` with the descriptive class name chosen above.
- Replace `"TICKER"` with the user's ticker string.
- Add the user's data property fields.
- Set `Value` to the field the user indicated as primary.
- If dual readers: add the `is_live_mode` branch in both `GetSource` and `Reader`.

### History verification in `OnEndOfAlgorithm`

Add this method to the algorithm class (after `OnData`):

```csharp
public override void OnEndOfAlgorithm()
{
    var result = History(_customSymbol, StartDate, Time);
    Log($"History rows: {result.Count()}");
}
```

Universe algorithms: omit this method.

---

## Step 4 -- Write Files via MCP

Two separate MCP writes are always required:

1. **Data reader file** -- use `quantconnect:create_file` to create the new file (e.g., `bitcoin_data.py` / `BitcoinData.cs`) containing the custom data class.
2. **Algorithm file** -- use `quantconnect:update_file_contents` to update `main.py` (or `Main.cs`) with the algorithm class only.

For Python projects, a third write is required: add the reader class import to `main.py` after `from AlgorithmImports import *`. For example, class `BitcoinData` in file `bitcoin_data.py` requires:

C# projects: skip this step.

---

## Step 5 -- Object Store Upload (if requested)

If `USE_OBJECT_STORE = true`:
1. Follow the `/upload-object-store` skill to upload the local file.
   Use `custom-data/<filename>` as the object store key (e.g. `custom-data/nifty.json`).
2. After a successful upload, update the `GetSource` method to use:

```csharp
return new SubscriptionDataSource(
    "custom-data/my-dataset.csv",
    SubscriptionTransportMedium.ObjectStore);
```

Then return to the main workflow (Step 6 -- compile).

---

## Step 6 -- Compile

1. Call `quantconnect:create_compile`.
2. Wait, then call `quantconnect:read_compile` until status is `BuildSuccess` or `BuildError`.
3. If `BuildError`:
   - Parse error messages (file, line, message).
   - Fix the code (update via MCP file tool).
   - Loop back to step 6 until clean.

---

## Step 7 -- Backtest and Verify (loop until working)

1. Call `quantconnect:create_backtest`.
2. Call `quantconnect:read_backtest` until status is complete.
3. Evaluate the results. Do not stop until both conditions below are met.

   **Condition A: History rows > 0**
   - Scan the log for `"History rows: N"`.
   - If N == 0, enter the mandatory diagnosis loop. Do not report success or stop:
     1. Fetch the data URL and parse the first record manually, step by step, to reproduce the reader logic. Identify exactly which line of the reader would fail.
     2. Audit every stdlib call in the reader (`JsonConvert.DeserializeObject`, `line.Split`, `XDocument.Parse`, `ZipFile`, etc.) and verify the module is explicitly imported. If any are missing, add the import and loop back to Step 6.
     3. Verify `SetStartDate` / `SetEndDate` fall within the data file's actual date range. If not, fix the dates.
     4. Recompile (Step 6) and re-backtest. Repeat until N > 0.

   **Condition B: At least one trade placed (non-flat equity curve)**
   - If N > 0 but no orders appear in the backtest:
     1. Add `Log($"OnData fired: {point.Value}")` as the first line of `OnData`. Re-backtest.
     2. If the log line appears, data is flowing but the entry condition never triggers; simplify or relax the trading condition and re-backtest.
     3. If the log line does not appear, `OnData` is never called; check subscription resolution and data normalization settings.
     4. Keep iterating until at least one order is placed.

4. Only after both conditions are met, report final result to the user:
   - Compilation: clean
   - Backtest: complete (show any key stats)
   - History verification: N rows loaded
   - Trades placed: M orders
   - Object store: uploaded at `key` (if applicable)

---

## Templates

Replace placeholders throughout:
- `MyCustomData` -> descriptive class name
- `"TICKER"` -> user's ticker string
- `PROPERTIES` -> user's data fields
- `VALUE_FIELD` -> the primary value field
- `BACKTEST_URL` / `LIVE_URL` -> actual source URLs

---

### Common algorithm class

`REGULAR_UNLINKED_REMOTE`, `REGULAR_UNLINKED_OBJSTORE`, `DUAL_READER`, `UNFOLDING`, and `ZIP` all use this algorithm class. Add it to `main.py` / `Main.cs` alongside the reader file.

```csharp
public class MyAlgorithm : QCAlgorithm
{
    private Symbol _customSymbol;

    public override void Initialize()
    {
        _customSymbol = AddData<MyCustomData>("TICKER", Resolution.Daily).Symbol;
    }

    public override void OnData(Slice slice)
    {
        if (!slice.ContainsKey(_customSymbol)) return;
        var custom = slice.Get<MyCustomData>(_customSymbol);
    }

    public override void OnEndOfAlgorithm()
    {
        var result = History(_customSymbol, StartDate, Time);
        Log($"History rows: {result.Count()}");
    }
}
```

---

### REGULAR_UNLINKED_REMOTE

```csharp
using QuantConnect;
using QuantConnect.Data;
using System;
using System.Globalization;

public class MyCustomData : BaseData
{
    public decimal Property1 { get; set; }
    public decimal Property2 { get; set; }

    public override SubscriptionDataSource GetSource(
        SubscriptionDataConfig config, DateTime date, bool isLiveMode)
    {
        return new SubscriptionDataSource(
            "BACKTEST_URL",
            SubscriptionTransportMedium.RemoteFile);
    }

    public override BaseData Reader(
        SubscriptionDataConfig config, string line, DateTime date, bool isLiveMode)
    {
        if (string.IsNullOrWhiteSpace(line) || !char.IsDigit(line[0]))
            return null;

        var data = line.Split(',');
        var obj = new MyCustomData { Symbol = config.Symbol };
        obj.Time = DateTime.Parse(data[0], CultureInfo.InvariantCulture);
        obj.EndTime = obj.Time.AddDays(1);
        obj.Property1 = decimal.Parse(data[1], CultureInfo.InvariantCulture);
        obj.Property2 = decimal.Parse(data[2], CultureInfo.InvariantCulture);
        obj.Value = decimal.Parse(data[VALUE_INDEX], CultureInfo.InvariantCulture);
        return obj;
    }
}
```

---

### REGULAR_UNLINKED_OBJSTORE

```csharp
using QuantConnect;
using QuantConnect.Data;
using System;
using System.Globalization;

public class MyCustomData : BaseData
{
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

        var data = line.Split(',');
        var obj = new MyCustomData { Symbol = config.Symbol };
        obj.Time = DateTime.Parse(data[0], CultureInfo.InvariantCulture);
        obj.EndTime = obj.Time.AddDays(1);
        obj.Value = decimal.Parse(data[VALUE_INDEX], CultureInfo.InvariantCulture);
        return obj;
    }
}
```

---

### REGULAR_LINKED

Data tied to an existing QC asset. The custom data symbol references the parent equity's ticker via `config.Symbol.Value`.

```csharp
using QuantConnect;
using QuantConnect.Data;
using System;
using System.Globalization;

public class MyLinkedData : BaseData
{
    public string EventType { get; set; }

    public override SubscriptionDataSource GetSource(
        SubscriptionDataConfig config, DateTime date, bool isLiveMode)
    {
        var ticker = config.Symbol.Value;
        return new SubscriptionDataSource(
            $"BACKTEST_URL/{ticker}.csv",
            SubscriptionTransportMedium.RemoteFile);
    }

    public override BaseData Reader(
        SubscriptionDataConfig config, string line, DateTime date, bool isLiveMode)
    {
        if (string.IsNullOrWhiteSpace(line) || !char.IsDigit(line[0]))
            return null;

        var data = line.Split(',');
        var obj = new MyLinkedData { Symbol = config.Symbol };
        obj.Time = DateTime.Parse(data[0], CultureInfo.InvariantCulture);
        obj.EndTime = obj.Time.AddDays(1);
        obj.EventType = data[1].Trim();
        obj.Value = decimal.Parse(data[VALUE_INDEX], CultureInfo.InvariantCulture);
        return obj;
    }
}

public class MyAlgorithm : QCAlgorithm
{
    private Symbol _equity;
    private Symbol _customSymbol;

    public override void Initialize()
    {
        _equity = AddEquity("AAPL", Resolution.Daily).Symbol;
        _customSymbol = AddData<MyLinkedData>(_equity).Symbol;
    }

    public override void OnData(Slice slice)
    {
        if (!slice.ContainsKey(_customSymbol)) return;
        var linked = slice.Get<MyLinkedData>(_customSymbol);
        Log($"Event: {linked.EventType}, Value: {linked.Value}");
    }

    public override void OnEndOfAlgorithm()
    {
        var result = History(_customSymbol, StartDate, Time);
        Log($"History rows: {result.Count()}");
    }
}
```

---

### DUAL_READER

Separate data sources for backtesting (CSV file) and live trading (REST API).

```csharp
using QuantConnect;
using QuantConnect.Data;
using Newtonsoft.Json;
using System;
using System.Globalization;

public class MyCustomData : BaseData
{
    [JsonProperty("last")]
    public decimal Last { get; set; }

    public override SubscriptionDataSource GetSource(
        SubscriptionDataConfig config, DateTime date, bool isLiveMode)
    {
        if (isLiveMode)
            return new SubscriptionDataSource(
                "LIVE_URL",
                SubscriptionTransportMedium.Rest);

        return new SubscriptionDataSource(
            "BACKTEST_URL",
            SubscriptionTransportMedium.RemoteFile);
    }

    public override BaseData Reader(
        SubscriptionDataConfig config, string line, DateTime date, bool isLiveMode)
    {
        if (string.IsNullOrWhiteSpace(line)) return null;

        var obj = new MyCustomData { Symbol = config.Symbol };

        if (isLiveMode)
        {
            obj = JsonConvert.DeserializeObject<MyCustomData>(line);
            obj.Symbol = config.Symbol;
            obj.EndTime = DateTime.UtcNow.ConvertFromUtc(config.ExchangeTimeZone);
            obj.Time = obj.EndTime.AddDays(-1);
            obj.Value = obj.Last;
            return obj;
        }

        if (!char.IsDigit(line[0])) return null;
        var data = line.Split(',');
        obj.Time = DateTime.Parse(data[0], CultureInfo.InvariantCulture);
        obj.EndTime = obj.Time.AddDays(1);
        obj.Value = decimal.Parse(data[VALUE_INDEX], CultureInfo.InvariantCulture);
        return obj;
    }
}
```

Add `import json` after `from AlgorithmImports import *` in any reader that calls `json.loads()`.

---

### UNIVERSE

Custom data file with multiple tickers per file -- builds a dynamic universe.
Universe selectors must return `Symbol` objects; `.Symbol` is correct in that context only.

```csharp
using QuantConnect;
using QuantConnect.Data;
using System;
using System.Collections.Generic;
using System.Globalization;
using System.Linq;

public class MyUniverseData : BaseData
{
    public decimal Rank { get; set; }
    public decimal Score { get; set; }

    public override SubscriptionDataSource GetSource(
        SubscriptionDataConfig config, DateTime date, bool isLiveMode)
    {
        return new SubscriptionDataSource(
            $"BACKTEST_URL/{date:yyyyMMdd}.csv",
            SubscriptionTransportMedium.RemoteFile);
    }

    public override BaseData Reader(
        SubscriptionDataConfig config, string line, DateTime date, bool isLiveMode)
    {
        if (string.IsNullOrWhiteSpace(line) || line.StartsWith("date"))
            return null;

        var data = line.Split(',');
        var obj = new MyUniverseData();
        obj.Symbol = Symbol.Create(data[1].Trim(), SecurityType.Equity, Market.USA);
        obj.Time = date;
        obj.EndTime = date.AddDays(1);
        obj.Rank = decimal.Parse(data[2], CultureInfo.InvariantCulture);
        obj.Score = decimal.Parse(data[3], CultureInfo.InvariantCulture);
        obj.Value = decimal.Parse(data[VALUE_INDEX], CultureInfo.InvariantCulture);
        return obj;
    }
}

public class MyAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        AddUniverse<MyUniverseData>(SelectorFunction);
    }

    private IEnumerable<Symbol> SelectorFunction(IEnumerable<BaseData> data)
    {
        return (from d in data.OfType<MyUniverseData>()
                where d.Rank > 0
                orderby d.Rank descending
                select d.Symbol).Take(10);
    }

    public override void OnSecuritiesChanged(SecurityChanges changes)
    {
        foreach (var added in changes.AddedSecurities)
            SetHoldings(added.Symbol, 1m / 10m);
        foreach (var removed in changes.RemovedSecurities)
            Liquidate(removed.Symbol);
    }

    public override void OnData(Slice slice) { }
}
```

---

### UNFOLDING

JSON file where the entire file is one JSON array. The reader returns `BaseDataCollection`.

```csharp
using QuantConnect;
using QuantConnect.Data;
using Newtonsoft.Json;
using System;

public class MyJsonData : BaseData
{
    [JsonProperty("date")]
    public string DateStr { get; set; }

    [JsonProperty("field1")]
    public decimal Field1 { get; set; }

    [JsonProperty("VALUE_FIELD")]
    public decimal ValueField { get; set; }

    public override SubscriptionDataSource GetSource(
        SubscriptionDataConfig config, DateTime date, bool isLiveMode)
    {
        return new SubscriptionDataSource(
            $"BACKTEST_URL/{date:yyyyMMdd}.json",
            SubscriptionTransportMedium.RemoteFile,
            FileExtension.Json,
            DataFeedEndpoint.Backtest);
    }

    public override BaseData Reader(
        SubscriptionDataConfig config, string line, DateTime date, bool isLiveMode)
    {
        if (string.IsNullOrWhiteSpace(line)) return null;
        var obj = JsonConvert.DeserializeObject<MyJsonData>(line);
        obj.Symbol = config.Symbol;
        obj.Time = DateTime.Parse(obj.DateStr);
        obj.EndTime = obj.Time.AddDays(1);
        obj.Value = obj.ValueField;
        return obj;
    }
}
```

Add `import json` after `from AlgorithmImports import *` in any reader that calls `json.loads()`.

---

### ZIP

Data distributed in ZIP archives containing CSV files.

```csharp
using QuantConnect;
using QuantConnect.Data;
using System;
using System.Globalization;

public class MyZipData : BaseData
{
    public override SubscriptionDataSource GetSource(
        SubscriptionDataConfig config, DateTime date, bool isLiveMode)
    {
        return new SubscriptionDataSource(
            $"BACKTEST_URL/{date:yyyyMMdd}.zip",
            SubscriptionTransportMedium.RemoteFile);
    }

    public override BaseData Reader(
        SubscriptionDataConfig config, string line, DateTime date, bool isLiveMode)
    {
        if (string.IsNullOrWhiteSpace(line) || !char.IsDigit(line[0]))
            return null;

        var data = line.Split(',');
        var obj = new MyZipData { Symbol = config.Symbol };
        obj.Time = DateTime.Parse(data[0], CultureInfo.InvariantCulture);
        obj.EndTime = obj.Time.AddHours(1);
        obj.Value = decimal.Parse(data[VALUE_INDEX], CultureInfo.InvariantCulture);
        return obj;
    }
}
```

---

## SubscriptionTransportMedium constants

| Medium | Python constant | C# constant |
|---|---|---|
| HTTP file download | `REMOTE_FILE` | `RemoteFile` |
| REST API poll | `REST` | `Rest` |
| Object Store | `OBJECT_STORE` | `ObjectStore` |
| Local disk | `LOCAL_FILE` | `LocalFile` |

## FileFormat constants

| Format | Python constant | C# constant | Use case |
|---|---|---|---|
| CSV (default) | `FileFormat.CSV` | `FileFormat.Csv` | Standard line-by-line. |
| JSON array | `FileFormat.UNFOLDING_COLLECTION` | `FileExtension.Json` + `DataFeedEndpoint.Backtest` | Entire file is one JSON array; reader receives full array and must return `BaseDataCollection`. |
| Zip of CSVs | `FileFormat.CSV` (with .zip URL) | `FileFormat.Csv` (with .zip URL) | ZIP auto-decompressed. |
