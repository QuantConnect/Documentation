---
name: charting
description: Use when adding or reviewing custom charts in a QuantConnect/LEAN algorithm (self.plot, self.plot_indicator, Chart/Series, CandlestickSeries). Covers SeriesType selection, series index for overlay vs subchart, and three silent-failure rules: (1) don't reuse reserved chart names (Assets Sales Volume, Exposure, Portfolio Margin) or reserved series names inside default charts (Equity, Return, Equity Drawdown, Benchmark, Portfolio Turnover, Strategy Capacity); (2) count every series against the tier cap before adding (10 on Free/QR, 25 on Team/Trading Firm, including built-ins); (3) stay under the per-series data-points cap — `plot_indicator` is preferred at daily/hourly resolution, but on minute/second/tick switch to `plot` from `on_end_of_day`. Also covers `CandlestickSeries` needing OHLC/TradeBar, mixing `Indicator` vs `TradeBarIndicator` in `PlotIndicators`, and when to route bulk data to the Object Store instead.
---

# Charting in QuantConnect / LEAN

The API is small (`Chart`, `Series`, `CandlestickSeries`, `plot`, `plot_indicator`) but the quota system is strict and silent: go over and charts are quietly truncated with only a runtime-terminal message. Plot density has to be designed, not improvised.

## Basic pattern

```python
class ChartExampleAlgorithm(QCAlgorithm):

    def initialize(self):
        self._spy = self.add_equity("SPY", Resolution.DAILY).symbol
        self._ema = self.ema(self._spy, 20)

        # Explicit declaration — only needed for non-default type/unit/color/index/marker
        # or a CandlestickSeries. Otherwise plot(...) auto-creates a line series, "$" unit, index 0.
        chart = Chart("Signals")
        self.add_chart(chart)
        chart.add_series(Series("EMA", SeriesType.LINE, "$", Color.ORANGE))

    def on_end_of_day(self, symbol):
        if symbol == self._spy and self._ema.is_ready:
            self.plot("Signals", "EMA", self._ema.current.value)
```

**Don't plot from `initialize`** — there's no algorithm time yet, so the point is either dropped or lands at the wrong x.

## Series types

| Type | Data shape | Typical use |
| --- | --- | --- |
| `Line` | Scalar per x | Indicators, running averages. **Default** when auto-created. |
| `Scatter` | Scalar per x, no line (takes `ScatterMarkerSymbol`) | Entry/exit markers, signal flags |
| `Candle` (`CandlestickSeries`) | OHLC per x | Price bars — pass a `TradeBar` or four scalars, not one |
| `Bar` | Scalar per x, vertical bars | Volume, discrete counts |
| `StackedArea` | Multiple series summing to a total | Allocation %, decomposition — needs ≥2 series on the chart |
| `Treemap` | One scalar per series, one tile per series | Per-asset snapshot (weight, sales volume) |

## Critical rule: don't reuse reserved chart or series names

The engine reserves these names and silently attaches its own data to them. Collisions overwrite your plots.

**Reserved chart names** (never pass to `Chart(...)`): `Assets Sales Volume`, `Exposure`, `Portfolio Margin`.

**Reserved series names within default charts** (you *can* add other series to these charts, just not these names):

| Chart | Reserved series |
| --- | --- |
| Strategy Equity | Equity, Return |
| Capacity | Strategy Capacity |
| Drawdown | Equity Drawdown |
| Benchmark | Benchmark |
| Portfolio Turnover | Portfolio Turnover |

## Critical rule: count your series against the tier cap before adding

Quotas (cloud; local is configurable via `maximum-chart-series` / `maximum-data-points-per-chart-series`):

| Tier | Max series (all charts combined) | Max points per series |
| --- | --- | --- |
| Free / Quant Researcher | 10 | 4,000 / 8,000 |
| Team / Trading Firm | 25 | 16,000 / 32,000 |
| Institution | 100 | 96,000 |

Over-cap fails silently with a runtime-terminal warning; the remainder of the run drops points or series.

The series cap counts **every** series across **every** chart, including built-ins — `Strategy Equity`'s Equity+Return, `Drawdown`'s Equity Drawdown, `Benchmark`'s Benchmark, `Portfolio Turnover`'s Portfolio Turnover already use 5 slots before you add anything. Before constructing any `Chart`/`Series` (or relying on `plot` auto-creation):

1. Count built-in series in play + every custom series you plan to add, including loops.
2. Compute the **worst-case** loop size — never emit one series per symbol on an unbounded universe; bound by the algorithm's structure, not day-one universe size.
3. If over cap, collapse (N PnL lines → one total, N per-symbol series → a top-5 `StackedArea` + "Other") or push per-symbol detail to the Object Store.
4. If you still can't fit, ask the user which charts matter most — the first N registered win, the rest vanish.

## Critical rule: stay under the per-series data-points cap

Once a series hits its points cap, it stops accepting points and the chart becomes a stub.

**Only a concern at minute/second/tick resolution or when a handler plots many times per day.** Daily plotting once per bar is ~250 points/year (2,500 over 10 years, well under Free's 4,000). Minute plotting once per bar is ~100,000/year — fills Free's cap in ~10 trading days. The example at the top is fine because it's daily.

Before writing a `plot` call, estimate `plots-per-day × trading-days` against the per-series cap. If comfortably under, no action. If close or over:

1. **Plot on events, not bars** — plot the EMA when it crosses, not every minute.
2. **Drop plotting resolution even when underlying data is dense** — plot from `on_end_of_day` or a scheduled event; use minute/tick `plot` only if every update truly matters.
3. **Never plot the price of the asset you're trading** — the built-in asset plot already does that.
4. **For dense structured data you'll analyze in Research, use the Object Store** — accumulate rows in memory and `object_store.save(key, content)` in `on_end_of_algorithm`, then read from a notebook. See the logging skill's "Object Store logging for Research analysis" section for the pattern. Charts are for a handful of series a user will eyeball in the backtest/live UI.

## Series index: overlay vs subchart

```python
chart.add_series(Series("Price", SeriesType.LINE, "$", Color.BLACK, index=0))
chart.add_series(Series("Volume", SeriesType.BAR, "", Color.GRAY, index=1))
```

All series at the same index share one y-axis (overlay — right for comparable scales). Different indices stack as separate subcharts (right for unrelated units like `$` vs volume). Default is 0, so series with different units all overlay unless you set `index`.

## Indicator plotting: `plot` vs `plot_indicator`

| Call | Fires | Use for |
| --- | --- | --- |
| `plot(chart, indicator, ...)` | Only when called | Periodic snapshots — from `on_end_of_day`, a scheduled event, or a branch |
| `plot_indicator(chart, indicator, ...)` (alias `plot_indicators`) | Every indicator update, automatic | Full indicator history |

**`plot_indicator` is the right default at daily or hourly resolution** — update frequency naturally fits under the cap (daily: ~250/yr; US Equities hourly: ~7/day; 24h Crypto hourly: 24/day — run the points-cap check but usually fine). **At minute/second/tick, switch to `plot(chart, indicator)` from `on_end_of_day`** unless you genuinely need every update (minute → ~400 updates/day, fills Free's cap in ~10 days).

**Don't mix `Indicator` and `TradeBarIndicator` in one call.** `Plot`/`PlotIndicator` require a single base type. `SMA`/`EMA`/`RSI` are `Indicator`; `ATR`/`AROON` are `TradeBarIndicator`. Split into separate calls, one per base type.

## Candlestick plotting

`CandlestickSeries` needs OHLC or a `TradeBar` — a scalar is silently dropped.

```python
chart.add_series(CandlestickSeries("SPY", "$"))

bar = slice.bars.get(self._spy)
if bar is not None:
    self.plot("Candles", "SPY", bar)
    # or: self.plot("Candles", "SPY", bar.open, bar.high, bar.low, bar.close)
```

For `QuoteBar`, collapse first: `slice.quote_bars[symbol].collapse()`. Same per-series points cap applies, so consolidate to daily+ over multi-year backtests.
