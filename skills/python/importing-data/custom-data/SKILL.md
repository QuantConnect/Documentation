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
- **Subscription variable**: Store the `Security` object returned by `add_data` / `add_equity` directly -- never append `.symbol` at assignment. Pass the variable directly everywhere (`history()`, `set_holdings()`, `liquidate()`, dict key). Use `x in data` to check presence.
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
- Set `value` to the field the user indicated as primary.
- If dual readers: add the `is_live_mode` branch in both `get_source` and `reader`.

### History verification in `on_end_of_algorithm`

Add this method to the algorithm class (after `on_data`):

```python
def on_end_of_algorithm(self):
    result = self.history(self._custom_ticker, self.start_date, self.time)
    self.log(f"History rows: {len(result)}")
```

Universe algorithms: omit this method.

---

## Step 4 -- Write Files via MCP

Two separate MCP writes are always required:

1. **Data reader file** -- use `quantconnect:create_file` to create the new file (e.g., `bitcoin_data.py` / `BitcoinData.cs`) containing the custom data class.
2. **Algorithm file** -- use `quantconnect:update_file_contents` to update `main.py` (or `Main.cs`) with the algorithm class only.

For Python projects, a third write is required: add the reader class import to `main.py` after `from AlgorithmImports import *`. For example, class `BitcoinData` in file `bitcoin_data.py` requires:

```python
from AlgorithmImports import *
from bitcoin_data import BitcoinData
```

C# projects: skip this step.

---

## Step 5 -- Object Store Upload (if requested)

If `USE_OBJECT_STORE = true`:
1. Follow the `/upload-object-store` skill to upload the local file.
   Use `custom-data/<filename>` as the object store key (e.g. `custom-data/nifty.json`).
2. After a successful upload, update the `get_source` method to use:

```python
return SubscriptionDataSource(
    "custom-data/my-dataset.csv",
    SubscriptionTransportMedium.OBJECT_STORE
)
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
     2. Audit every stdlib call in the reader (`json.loads`, `csv.reader`, `xml.parse`, `zipfile.open`, etc.) and verify the module is explicitly imported. If any are missing, add the import and loop back to Step 6.
     3. Verify `set_start_date` / `set_end_date` fall within the data file's actual date range. If not, fix the dates.
     4. Recompile (Step 6) and re-backtest. Repeat until N > 0.

   **Condition B: At least one trade placed (non-flat equity curve)**
   - If N > 0 but no orders appear in the backtest:
     1. Add `self.log(f"on_data fired: {point.value}")` as the first line of `on_data`. Re-backtest.
     2. If the log line appears, data is flowing but the entry condition never triggers; simplify or relax the trading condition and re-backtest.
     3. If the log line does not appear, `on_data` is never called; check subscription resolution and data normalization settings.
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

```python
class MyAlgorithm(QCAlgorithm):

    def initialize(self):
        self._custom_ticker = self.add_data(MyCustomData, "TICKER", Resolution.DAILY)

    def on_data(self, data):
        if self._custom_ticker not in data:
            return
        custom = data[self._custom_ticker]

    def on_end_of_algorithm(self):
        result = self.history(self._custom_ticker, self.start_date, self.time)
        self.log(f"History rows: {len(result)}")
```

---

### REGULAR_UNLINKED_REMOTE

```python
# region imports
from AlgorithmImports import *
# endregion


class MyCustomData(PythonData):

    def get_source(self, config, date, is_live_mode):
        return SubscriptionDataSource(
            "BACKTEST_URL",
            SubscriptionTransportMedium.REMOTE_FILE
        )

    def reader(self, config, line, date, is_live_mode):
        if not line.strip() or not line[0].isdigit():
            return None
        data = line.split(',')
        obj = MyCustomData()
        obj.symbol = config.symbol
        obj.time = datetime.strptime(data[0], "%Y-%m-%d")
        obj.end_time = obj.time + timedelta(days=1)
        obj["PROPERTY1"] = float(data[1])
        obj["PROPERTY2"] = float(data[2])
        obj.value = float(data[VALUE_INDEX])
        return obj
```

---

### REGULAR_UNLINKED_OBJSTORE

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
        data = line.split(',')
        obj = MyCustomData()
        obj.symbol = config.symbol
        obj.time = datetime.strptime(data[0], "%Y-%m-%d")
        obj.end_time = obj.time + timedelta(days=1)
        obj.value = float(data[VALUE_INDEX])
        return obj
```

---

### REGULAR_LINKED

Data tied to an existing QC asset. The custom data symbol references the parent equity's ticker via `config.symbol.value`.

```python
# region imports
from AlgorithmImports import *
# endregion


class MyLinkedData(PythonData):

    def get_source(self, config, date, is_live_mode):
        ticker = config.symbol.value
        return SubscriptionDataSource(
            f"BACKTEST_URL/{ticker}.csv",
            SubscriptionTransportMedium.REMOTE_FILE
        )

    def reader(self, config, line, date, is_live_mode):
        if not line.strip() or not line[0].isdigit():
            return None
        data = line.split(',')
        obj = MyLinkedData()
        obj.symbol = config.symbol
        obj.time = datetime.strptime(data[0], "%Y-%m-%d")
        obj.end_time = obj.time + timedelta(days=1)
        obj["EventType"] = data[1].strip()
        obj.value = float(data[VALUE_INDEX])
        return obj


class MyAlgorithm(QCAlgorithm):

    def initialize(self):
        self._equity = self.add_equity("AAPL", Resolution.DAILY)
        self._custom_linked = self.add_data(MyLinkedData, self._equity)

    def on_data(self, data):
        if self._custom_linked not in data:
            return
        linked = data[self._custom_linked]
        self.log(f"Linked event: {linked['EventType']}, value: {linked.value}")

    def on_end_of_algorithm(self):
        result = self.history(self._custom_linked, self.start_date, self.time)
        self.log(f"History rows: {len(result)}")
```

---

### DUAL_READER

Separate data sources for backtesting (CSV file) and live trading (REST API).

```python
# region imports
from AlgorithmImports import *
import json
# endregion


class MyCustomData(PythonData):

    def get_source(self, config, date, is_live_mode):
        if is_live_mode:
            return SubscriptionDataSource(
                "LIVE_URL",
                SubscriptionTransportMedium.REST
            )
        return SubscriptionDataSource(
            "BACKTEST_URL",
            SubscriptionTransportMedium.REMOTE_FILE
        )

    def reader(self, config, line, date, is_live_mode):
        if not line.strip():
            return None
        obj = MyCustomData()
        obj.symbol = config.symbol
        if is_live_mode:
            raw = json.loads(line)
            obj.value = float(raw["last"])
            obj.end_time = Extensions.convert_from_utc(
                datetime.utcnow(), config.exchange_time_zone
            )
            obj.time = obj.end_time - timedelta(days=1)
            return obj
        if not line[0].isdigit():
            return None
        data = line.split(',')
        obj.time = datetime.strptime(data[0], "%Y-%m-%d")
        obj.end_time = obj.time + timedelta(days=1)
        obj.value = float(data[VALUE_INDEX])
        return obj
```

Add `import json` after `from AlgorithmImports import *` in any reader that calls `json.loads()`.

---

### UNIVERSE

Custom data file with multiple tickers per file -- builds a dynamic universe.
Universe selectors must return `Symbol` objects; `.symbol` is correct in that context only.

```python
# region imports
from AlgorithmImports import *
# endregion


class MyUniverseData(PythonData):

    def get_source(self, config, date, is_live_mode):
        return SubscriptionDataSource(
            f"BACKTEST_URL/{date:%Y%m%d}.csv",
            SubscriptionTransportMedium.REMOTE_FILE
        )

    def reader(self, config, line, date, is_live_mode):
        if not line.strip() or line.startswith("date"):
            return None
        data = line.split(',')
        obj = MyUniverseData()
        obj.symbol = Symbol.create(data[1].strip(), SecurityType.EQUITY, Market.USA)
        obj.time = date
        obj.end_time = date + timedelta(days=1)
        obj["Rank"] = float(data[2])
        obj["Score"] = float(data[3])
        obj.value = float(data[VALUE_INDEX])
        return obj


class MyAlgorithm(QCAlgorithm):

    def initialize(self):
        self.add_universe(MyUniverseData, self._selector)

    def _selector(self, data):
        sorted_data = sorted(
            [x for x in data if x["Rank"] > 0],
            key=lambda x: x["Rank"],
            reverse=True
        )
        return [x.symbol for x in sorted_data[:10]]

    def on_securities_changed(self, changes):
        for security in changes.added_securities:
            self.set_holdings(security, 1 / 10)
        for security in changes.removed_securities:
            self.liquidate(security)

    def on_data(self, data):
        pass
```

---

### UNFOLDING

JSON file where the entire file is one JSON array. The reader returns `BaseDataCollection`.

```python
# region imports
from AlgorithmImports import *
import json
# endregion


class MyJsonData(PythonData):

    def get_source(self, config, date, is_live_mode):
        return SubscriptionDataSource(
            f"BACKTEST_URL/{date:%Y%m%d}.json",
            SubscriptionTransportMedium.REMOTE_FILE,
            FileFormat.UNFOLDING_COLLECTION
        )

    def reader(self, config, line, date, is_live_mode):
        if not line.strip():
            return None
        records = json.loads(line)
        objects = []
        for record in records:
            obj = MyJsonData()
            obj.symbol = config.symbol
            obj.time = datetime.strptime(record["date"], "%Y-%m-%d")
            obj.end_time = obj.time + timedelta(days=1)
            obj["Field1"] = float(record.get("field1", 0))
            obj.value = float(record.get("VALUE_FIELD", 0))
            objects.append(obj)
        if not objects:
            return None
        return BaseDataCollection(objects[-1].end_time, config.symbol, objects)
```

Add `import json` after `from AlgorithmImports import *` in any reader that calls `json.loads()`.

---

### ZIP

Data distributed in ZIP archives containing CSV files.

```python
# region imports
from AlgorithmImports import *
# endregion


class MyZipData(PythonData):

    def get_source(self, config, date, is_live_mode):
        return SubscriptionDataSource(
            f"BACKTEST_URL/{date:%Y%m%d}.zip",
            SubscriptionTransportMedium.REMOTE_FILE,
            FileFormat.CSV
        )

    def reader(self, config, line, date, is_live_mode):
        if not line.strip() or not line[0].isdigit():
            return None
        data = line.split(',')
        obj = MyZipData()
        obj.symbol = config.symbol
        obj.time = datetime.strptime(data[0], "%Y-%m-%d %H:%M:%S")
        obj.end_time = obj.time + timedelta(hours=1)
        obj.value = float(data[VALUE_INDEX])
        return obj
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
