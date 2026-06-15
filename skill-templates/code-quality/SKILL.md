---
name: code-quality
description: >
  Use when writing, reviewing, or refactoring QuantConnect/LEAN algorithm code
  for style correctness. Triggers: new algorithm code, code review, cleanup,
  "fix code style", "review code quality", "clean up the algorithm", redundant
  imports, subscription variable naming, comment format, blank-line rules,
  multi-line boolean layout, or custom-data reader error handling. Skip when:
  the task is only debugging runtime behavior or performance, unless the fix
  also changes algorithm style.
---

# QuantConnect Algorithm Code Quality

Apply these rules to py`main.py`cs`main.cs` and to any custom data classes in a
QuantConnect / LEAN algorithm project. This skill is about code that compiles,
is idiomatic for the target language, and avoids patterns that hide real
failures.

## Imports

<!-- python-only -->
`AlgorithmImports` already re-exports the QuantConnect API and the common
Python types used in algorithms. Do not add redundant imports for `datetime`,
`timedelta`, `date`, `pandas`, `numpy`, or `math`.

```python
# Bad
from datetime import datetime, timedelta, date
import pandas as pd
import numpy as np
import math

# Good
# region imports
from AlgorithmImports import *
# endregion
```

Standard library modules used inside custom data readers are not re-exported.
Add modules such as `json`, `csv`, `xml.etree.ElementTree`, `zipfile`, or `io`
explicitly after `AlgorithmImports` when the reader actually uses them.

```python
# region imports
from AlgorithmImports import *
import json
# endregion
```
<!-- /python-only -->

<!-- csharp-only -->
Leave the `using` statements from the project template in place. Do not add or
remove them during algorithm style cleanup unless a compile error proves one is
needed.
<!-- /csharp-only -->

## Subscription variables

<!-- python-only -->
Store the `Security` object returned by `add_data`, `add_equity`, or
`add_crypto`. LEAN accepts the `Security` object directly anywhere a `Symbol` is
expected, so do not call `.symbol` on the stored variable.

```python
# Bad: .symbol at assignment discards the Security object.
self._custom_symbol = self.add_data(MyData, "TICKER").symbol

# Bad: .symbol after assignment is still unnecessary.
self._custom_btc = self.add_data(MyData, "TICKER")
data[self._custom_btc.symbol]
self.history(MyData, self._custom_btc.symbol, 10, Resolution.DAILY)

# Good: pass the Security object directly.
self._custom_btc = self.add_data(MyData, "TICKER")
if self._custom_btc not in data:
    return
bar = data[self._custom_btc]
self.history(MyData, self._custom_btc, 10, Resolution.DAILY)
self.set_holdings(self._custom_btc, 1)
self.liquidate(self._custom_btc)
```

Use `x in data` and `x not in data` instead of `data.contains_key(x)`.

Name stored custom data subscriptions as `self._custom_<concise_asset_name>`.
Match the asset, not the data type:

| Asset | Variable |
| --- | --- |
| Bitstamp BTC/USD | `self._custom_btc` |
| Weather data | `self._custom_weather` |
| C-suite events for AAPL | `self._custom_csuite` |
<!-- /python-only -->

<!-- csharp-only -->
Store the `Symbol` by appending `.Symbol` at assignment. Pass the `Symbol`
directly everywhere.

```csharp
// Bad
private Security _customBtc;
// Later, incorrectly using _customBtc where a Symbol is expected.

// Good
private Symbol _customSymbol;

public override void Initialize()
{
    _customSymbol = AddData<MyData>("TICKER", Resolution.Daily).Symbol;
}
```

Name custom data symbols as `_custom<AssetName>` with an underscore prefix and
PascalCase after `_custom`, such as `_customBtc` or `_customWeather`.
<!-- /csharp-only -->

## Comments

Every comment must start with a capital letter, have a space after the comment
marker, use sentence form, and end with a period.

```python
# Bad
# schedule the insights to happen monthly, same as the universe.
# add equity
# Fast SMA

# Good
# Schedule a monthly event to emit insights matching the universe.
# Add the equity subscription.
# Fast SMA period.
```

```csharp
// Bad
// schedule the monthly insight
var x = 1; //calculate price

// Good
// Schedule a monthly insight event.
var x = 1; // Calculate the weighted price.
```

## Multi-line boolean expressions

In multi-line boolean expressions, put the logical operator at the end of each
continued line, not the beginning.

```python
# Bad
if (
    close > ema_fast
    and open_ > ema_fast
    and rsi > 50
):
    return

# Good
if (
    close > ema_fast and
    open_ > ema_fast and
    rsi > 50
):
    return
```

```csharp
// Bad
if (close > emaFast
    && open > emaFast
    && rsi > 50)
{
    return;
}

// Good
if (close > emaFast &&
    open > emaFast &&
    rsi > 50)
{
    return;
}
```

The same rule applies to py`or`cs`||` and compound conditions.

## Blank lines

<!-- python-only -->
Use two blank lines before each top-level class definition, one blank line
before each method inside a class, and no blank lines inside method bodies.

```python
class MyData(PythonData):

    def get_source(self, config, date, is_live_mode):
        return SubscriptionDataSource("https://example.com/data.csv", SubscriptionTransportMedium.REMOTE_FILE)

    def reader(self, config, line, date, is_live_mode):
        csv = line.split(",")
        data = MyData()
        data.symbol = config.symbol
        data.time = datetime.strptime(csv[0], "%Y-%m-%d")
        data.value = float(csv[1])
        return data


class MyAlgorithm(QCAlgorithm):

    def initialize(self):
        self.set_start_date(2024, 1, 1)
        self.set_cash(100000)
        self._custom_btc = self.add_data(MyData, "BTCUSD", Resolution.DAILY)
```
<!-- /python-only -->

<!-- csharp-only -->
Use one blank line between methods inside a class, no blank lines inside method
bodies, and standard C# brace style.

```csharp
public class MyAlgorithm : QCAlgorithm
{
    private Symbol _customSymbol;

    public override void Initialize()
    {
        SetStartDate(2024, 1, 1);
        SetCash(100000);
        _customSymbol = AddData<MyData>("TICKER", Resolution.Daily).Symbol;
    }

    public override void OnData(Slice slice)
    {
        if (!slice.ContainsKey(_customSymbol)) return;
        var bar = slice.Get<MyData>(_customSymbol);
    }
}
```
<!-- /csharp-only -->

## Reader error handling

Do not wrap reader logic in py`try`/`except`cs`try`/`catch`. Exceptions reveal
parse failures, missing fields, and format mismatches. Catch-all handlers make
bugs invisible and slow debugging.

```python
# Bad: hides every parse error.
def reader(self, config, line, date, is_live_mode):
    try:
        data = MyData()
        csv = line.split(",")
        data.time = datetime.strptime(csv[0], "%Y-%m-%d")
        data.value = float(csv[1])
        return data
    except Exception:
        return None

# Good: skip only known non-data lines.
def reader(self, config, line, date, is_live_mode):
    if line.startswith("#") or not line.strip():
        return None
    data = MyData()
    csv = line.split(",")
    data.time = datetime.strptime(csv[0], "%Y-%m-%d")
    data.value = float(csv[1])
    return data
```

```csharp
// Bad: hides every parse error.
public override BaseData Reader(SubscriptionDataConfig config, string line, DateTime date, bool isLiveMode)
{
    try
    {
        var csv = line.Split(',');
        var obj = new MyData { Symbol = config.Symbol };
        obj.Time = DateTime.ParseExact(csv[0], "yyyy-MM-dd", null);
        obj.Value = decimal.Parse(csv[1]);
        return obj;
    }
    catch
    {
        return null;
    }
}

// Good: skip only known non-data lines.
public override BaseData Reader(SubscriptionDataConfig config, string line, DateTime date, bool isLiveMode)
{
    if (line.StartsWith("#") || string.IsNullOrWhiteSpace(line)) return null;
    var csv = line.Split(',');
    var obj = new MyData { Symbol = config.Symbol };
    obj.Time = DateTime.ParseExact(csv[0], "yyyy-MM-dd", null);
    obj.Value = decimal.Parse(csv[1]);
    return obj;
}
```

Return py`None`cs`null` only for genuinely skippable lines, such as headers,
blank lines, or comment lines. Use an explicit guard at the top.

## Checklist

1. py`Only from AlgorithmImports import * plus required standard library modules.`cs`Template using statements are preserved.`
2. py`Store the Security object returned by add_data or add_equity.`cs`Store the Symbol returned by AddData(...).Symbol or AddEquity(...).Symbol.`
3. Subscription variable names match the asset and language convention.
4. Comments start with a capital letter, include a space after py`#`cs`//`, and end with a period.
5. Multi-line py`and` / `or`cs`&&` / `||` expressions keep the operator at the end of each continued line.
6. Blank lines match the language rules, with no blank lines inside method bodies.
7. Reader code has no py`try` / `except`cs`try` / `catch`; it uses explicit guards for skippable lines.
