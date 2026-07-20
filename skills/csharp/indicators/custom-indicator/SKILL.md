---
name: custom-indicator
description: >
  Creates a custom indicator class in QuantConnect/LEAN. Invoke for "create a custom indicator", "implement [name] indicator", "I need an indicator LEAN doesn't have", `ComputeNextValue`, "custom indicator class". Skip for LEAN built-ins (SMA, EMA, RSI).
---

# /custom-indicator -- QuantConnect Custom Indicator

Creates a custom indicator class and wires it into an algorithm.

## Step 1 -- Gather Indicator Information

Ask the user: (1) indicator name; (2) formula/logic; (3) input type -- `IndicatorDataPoint` (single price), `TradeBar` (OHLCV, default), `QuoteBar` (bid/ask); (4) period; (5) internal LEAN indicators (SMA, EMA, RSI) -- construct and update inside `ComputeNextValue`; (6) scope -- single symbol or universe; (7) warm-up -- manual (recommended) or automatic.

## Step 2 -- Generate the Code

C#: inherit `Indicator` (or `BarIndicator` for OHLCV). Declare a `WarmUpPeriod` property (implement `IIndicatorWarmUpPeriodProvider`). File: `PascalCase.cs`.
Manual warm-up (default): loop `History<TradeBar>(symbol, period + 1, Resolution.Daily)`, call `indicator.Update(bar)` before `RegisterIndicator`.
Automatic warm-up: `Settings.AutomaticIndicatorWarmUp = true` in `Initialize`.

### Indicator class
```csharp
public class CustomVolatility : Indicator, IIndicatorWarmUpPeriodProvider
{
    private readonly RollingWindow<decimal> _window;
    public int WarmUpPeriod => _window.Size;
    public CustomVolatility(int period) : base("CustomVolatility")
    {
        _window = new RollingWindow<decimal>(period);
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
```csharp
var symbol = AddEquity("SPY", Resolution.Daily).Symbol;
_indicator = new CustomVolatility(period);
foreach (var bar in History<TradeBar>(symbol, period + 1, Resolution.Daily))
    _indicator.Update(bar);
RegisterIndicator(symbol, _indicator);
```

### Universe integration
In `OnSecuritiesChanged`, create and warm the indicator for each added security, then `RegisterIndicator`; on removal, `DeregisterIndicator` and `Liquidate`. Track them in a `Dictionary<Symbol, CustomVolatility>` and gate reads on `_indicators[symbol].IsReady`.

## Step 3 -- Write Files via MCP

1. `quantconnect:create_file` with the indicator class.
2. `quantconnect:update_file_contents` for `Main.cs`.

## Step 4 -- Compile

1. `quantconnect:create_compile`. Poll `quantconnect:read_compile` until `BuildSuccess` or `BuildError`. Fix errors and loop.

## Step 5 -- Backtest and Verify

1. `quantconnect:create_backtest`. Poll `quantconnect:read_backtest` until complete.
2. Confirm `IsReady` becomes true and at least one order placed (if the algorithm has entry conditions).
