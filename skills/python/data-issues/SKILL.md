---
name: data-issues
description: Use when a backtest blows up or an asset shows obviously corrupted data (a price spiking orders of magnitude and snapping back within a few bars, or a long flat-line / missing-price stretch). Applies to ANY asset class (equities, futures, crypto). Covers confirming the glitch in the Research Environment and blocking the asset by its Security ID so it is never selected or traded.
---

# Suspected data issues — confirm in research, then block by Security ID

Applies to any asset class. When an asset shows obviously corrupted data that distorts a signal or blows up a backtest, do NOT silently hack around it (a guessed ticker blacklist, disabling margin calls, clipping returns). Confirm the glitch in a notebook, then block that specific asset and keep the notebook as evidence.

## A blow-up IS a trigger — investigate before reaching for risk knobs
A long/short backtest that goes catastrophically negative — a hugely negative return (e.g. −100,000%), `PortfolioValueIsNotPositiveAnalysis`, or equity far below zero — is almost never a position-sizing problem. A normal short loses at most ~100%; for equity to go many times negative, a SHORTED asset's price moved by orders of magnitude — the signature of a corrupted data tick (a price spiking and snapping back).
When you see a blow-up, do NOT reach for `min_position_weight`, free-cash buffers, leverage caps, or disabling margin calls — those cannot fix corrupted data.
Treat the blow-up as a data-issue trigger: find the asset with the impossible move (the largest single-name loss, or the biggest one-bar price jump in the logs), confirm the glitch in a research notebook (below), and block it by Security ID. One blocked glitch asset fixes the blow-up; risk knobs do not.

## What counts as an OBVIOUS data issue
Block only clear glitch patterns — the kind no real market produces:
- **Spike-and-revert:** a price jumps orders of magnitude and snaps back within a few bars (e.g. $1 → $10,000 → $1). A real move does not instantly return to the exact prior level.
- **Flat-line / missing data:** a long stretch with no price movement or absent bars (e.g. ~10 days of a dead flat line).

A real but extreme move (an asset that legitimately 5x's and stays up) is NOT a data issue. If it is not obviously one of the patterns above, do not block it — report it to the user instead.

## 1. Confirm the anomaly in the Research Environment
Create a Jupyter notebook in the project and pull the asset's history around the suspect date with QuantBook (use the `add_*` for the asset's class):

```python
qb = QuantBook()
sym = qb.add_equity("XYZ").symbol     # or add_crypto("MDOGUSD", market=Market.BITFINEX), add_future(...), etc.
hist = qb.history(sym, datetime(2022, 6, 1), datetime(2022, 6, 30), Resolution.DAILY)
print(hist[["open", "high", "low", "close", "volume"]])   # show the spike-and-revert or flat-line
print("SECURITY ID:", str(sym.id))                        # copy this to block the asset (step 2)
```

## 2. Block the asset by its Security ID
Block it by its stable **Security ID** (`str(symbol.id)`), not its ticker — a ticker can later be reused for a different asset, the Security ID will not:

```python
# Confirmed data issues — each entry is str(symbol.id), backed by the research notebook above.
self._blocked_assets = {
    "MDOGUSD 2XR",   # ILLUSTRATIVE — paste the real str(sym.id) you printed; spike-and-revert 2022-06-13, see data_issue.ipynb
}
```

Check it in your universe selector so the asset is never selected (and therefore never traded). This works in any selector — fundamental, ETF-constituent, or crypto:

```python
def _select_assets(self, data):
    return [x.symbol for x in data
            if str(x.symbol.id) not in self._blocked_assets
            # ... plus your normal universe filters ...
            ]
```

Remove the entry once QuantConnect resolves the data issue. Keep the notebook in the project — it is your evidence (and what a data-issue report would attach later).
