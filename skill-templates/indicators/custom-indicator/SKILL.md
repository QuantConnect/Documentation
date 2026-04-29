---
name: custom-indicator
description: >
  Creates a custom indicator class in QuantConnect/LEAN. Invoke for "create a custom indicator", "implement [name] indicator", "I need an indicator LEAN doesn't have", "PythonIndicator", "custom indicator class". Skip for LEAN built-ins (SMA, EMA, RSI).
---

# /custom-indicator -- QuantConnect Custom Indicator

Creates a custom indicator class and wires it into an algorithm.

## Step 1 -- Gather Indicator Information

Ask the user: (1) indicator name; (2) formula/logic; (3) input type -- `IndicatorDataPoint` (single price), `TradeBar` (OHLCV, default), `QuoteBar` (bid/ask); (4) period; (5) internal LEAN indicators (SMA, EMA, RSI) -- construct and update inside py`update`cs`ComputeNextValue`; (6) scope -- single symbol or universe; (7) warm-up -- manual (recommended) or automatic.

## Step 2 -- Generate the Code

Python: `from AlgorithmImports import *` only. Inherit `PythonIndicator`. File: `snake_case.py` in its own file.
C#: inherit `Indicator` (or `BarIndicator` for OHLCV). Set `WarmUpPeriod` in constructor. File: `PascalCase.cs`.
Manual warm-up (default): loop py`self.history[TradeBar](symbol, period + 1)`cs`History<TradeBar>(symbol, period + 1, Resolution.Daily)`, call py`indicator.update(bar)`cs`indicator.Update(bar)` before py`self.register_indicator`cs`RegisterIndicator`.
Automatic warm-up: py`self.settings.automatic_indicator_warm_up = True`cs`Settings.AutomaticIndicatorWarmUp = true` in py`initialize`cs`Initialize`.

### Indicator class
```python
class CustomVolatility(PythonIndicator):
    def __init__(self, period):
        super().__init__()
        self.value = 0
        self._window = RollingWindow[float](period)
    def update(self, input_: BaseData):
        self._window.add(input_.value)
        if self._window.is_ready:
            prices = np.array(list(self._window)[::-1])
            self.value = np.std(np.diff(np.log(prices))) * math.sqrt(252) * 100
        return self.is_ready
    @property
    def is_ready(self) -> bool:
        return self._window.is_ready
```
```csharp
public class CustomVolatility : Indicator
{
    private readonly RollingWindow<decimal> _window;
    public CustomVolatility(int period) : base("CustomVolatility")
    {
        _window = new RollingWindow<decimal>(period);
        WarmUpPeriod = period;
    }
    public override bool IsReady => _window.IsReady;
    protected override decimal ComputeNextValue(IndicatorDataPoint input)
    {
        _window.Add(input.Value);
        if (!IsReady) return 0m;
        // Compute from _window here.
        return 0m;
    }
}
```
For OHLCV in C#, inherit `BarIndicator` and use `IBaseDataBar` in `ComputeNextValue`.

### Single-symbol integration
```python
def initialize(self):
    symbol = self.add_equity("SPY", Resolution.DAILY).symbol
    self._indicator = CustomVolatility(period)
    for bar in self.history[TradeBar](symbol, period + 1):
        self._indicator.update(bar)
    self.register_indicator(symbol, self._indicator)
```
```csharp
var symbol = AddEquity("SPY", Resolution.Daily).Symbol;
_indicator = new CustomVolatility(period);
foreach (var bar in History<TradeBar>(symbol, period + 1, Resolution.Daily))
    _indicator.Update(bar);
RegisterIndicator(symbol, _indicator);
```

### Universe integration
```python
def on_securities_changed(self, changes):
    for security in changes.added_securities:
        security.indicator = CustomVolatility(period)
        for bar in self.history[TradeBar](security, period + 1):
            security.indicator.update(bar)
        self.register_indicator(security, security.indicator)
    for security in changes.removed_securities:
        self.deregister_indicator(security.indicator)
        self.liquidate(security)
```
C# mirrors Python: use `OnSecuritiesChanged`, `RegisterIndicator`, `DeregisterIndicator`, `Liquidate`. Gate reads on `_indicators[symbol].IsReady`.

## Step 3 -- Write Files via MCP

1. `quantconnect:create_file` with the indicator class.
2. `quantconnect:update_file_contents` for `main.py` / `Main.cs`.
3. Python only: add `from custom_volatility import CustomVolatility` after `from AlgorithmImports import *`.

## Step 4 -- Compile

1. `quantconnect:create_compile`. Poll `quantconnect:read_compile` until `BuildSuccess` or `BuildError`. Fix errors and loop.

## Step 5 -- Backtest and Verify

1. `quantconnect:create_backtest`. Poll `quantconnect:read_backtest` until complete.
2. Confirm py`is_ready`cs`IsReady` becomes true and at least one order placed (if the algorithm has entry conditions).
