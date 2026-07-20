---
name: orders
description: Use whenever placing orders, sizing positions to target weights, exiting/flattening, choosing an order type (incl. market-on-close), or deciding on fee/slippage/fill models. Prefer QuantConnect built-ins over hand-rolled order math.
---

# Orders, sizing, exits, and execution models — use QC built-ins, don't reinvent

## Sizing to a target weight with MARKET orders (the common case)
- Equities/ETFs and most assets: `SetHoldings(symbol, weight)`. For a basket, pass a list in ONE call so QC sizes them together:
  `SetHoldings(new List<PortfolioTarget> { new(uso, 0.5m), new(uga, 0.5m) })` (add `liquidateExistingHoldings: true` to also flatten anything not in the list).
- Let QC convert weights to share counts — do NOT hand-compute quantities or place raw `MarketOrder`s to hit a target weight.
- FUTURES are the exception: `SetHoldings` sizes off (already-leveraged) buying power and over-levers a futures position, so size futures by integer contracts from notional — see the futures skill.

## Sizing to a target weight AT THE CLOSE (market-on-close)
`SetHoldings` sends MARKET orders (next-bar fill), so it does NOT give a close fill. When the method requires entering/exiting at the official close, compute the quantity for the target weight, then place a market-on-close order:
```csharp
var qty = CalculateOrderQuantity(symbol, weight);   // decimal shares for the target %, lot-size-rounded
MarketOnCloseOrder(symbol, qty);
```

Do this per symbol for a basket. `CalculateOrderQuantity` accounts for current holdings, buying power, and fees, so it is the correct way to turn a target weight into a share count without hand math.

## Market-on-close submission timing — do NOT lower the buffer
QC rejects a MarketOnClose order submitted within `MarketOnCloseOrder.SubmissionTimeBuffer` of the close (default 15.5 minutes); it errors `MarketOnCloseOrderTooLate` and nothing fills. Do NOT lower this buffer to submit closer to the close: the 15.5-minute default reflects a real constraint (with minute data the 15:44->15:45 bar only arrives at 15:45 — too late to act on and still submit — and real exchange MOC entry cutoffs are ~10-15 min before the close), so a lowered buffer fills orders that are not achievable live.
(In C# the buffer field `MarketOnCloseOrder.SubmissionTimeBuffer` is assignable, but it must not be changed, for the same reasons. Do not set it.)
When a method enters or exits at the close, sample the signal and submit the MOC order at least ~15.5 minutes before the close, e.g. via a scheduled event at `TimeRules.BeforeMarketClose(symbol, 16)`. `BeforeMarketClose` (and `AfterMarketOpen`) resolve to the security's ACTUAL session boundary for each date, so they handle early-close and holiday sessions automatically — anchor close-of-day logic to them, not to a hardcoded clock time (a literal 16:00 is wrong on early-close days). A too-late MOC fails QUIETLY — `MarketOnCloseOrderTooLate` does not reliably surface in a generic error-log search; the visible symptom is positions surviving past the close (held overnight) and being flattened at the next session's open. So when you use `MarketOnCloseOrder`, VERIFY the position actually goes flat at the close: if anything carries to the next session, the MOC is being rejected and you must submit it earlier (≥15.5 min before the close). When the method says exit AT the close, the MOC submitted ≥15.5 min early (`TimeRules.BeforeMarketClose(symbol, 16)`) is the FAITHFUL instrument — it fills at the official closing auction, which is what "at the session close" means. Do NOT "fix" a too-late MOC by switching to a plain market order at `TimeRules.BeforeMarketClose(symbol, 1)`: that fills ~1 minute BEFORE the close (on the 15:59 bar), not at the close — a less-faithful exit. A market order at the bell is acceptable only when the method does not require the official-close price (or the asset has no closing auction, e.g. crypto).

## Market-on-close is for exchange-traded assets only — NOT crypto
Market-on-close fills at an exchange's official closing auction. Cryptocurrencies trade 24/7 and have no closing auction, so `MarketOnCloseOrder` and its submission-buffer timing do NOT apply to crypto. For a daily crypto rebalance, fire a scheduled event at your chosen daily boundary (e.g. midnight UTC) and rebalance with `SetHoldings` / market orders. Do not deliberate market-on-close for crypto — it is not a thing there.

## Exiting / flattening
- Market-order exit: `Liquidate()` closes ALL holdings, `Liquidate(symbol)` closes one.
- Exit AT THE CLOSE: `Liquidate` sends a market order, so for a close fill flatten with an MOC order for the negative of the current position:
  `MarketOnCloseOrder(symbol, -Portfolio[symbol].Quantity)`.

## Reversing a position
A reversal needs only ONE order, not a `Liquidate` then a separate entry. With `SetHoldings` this is automatic — `SetHoldings(symbol, newWeight)` (e.g. the opposite sign) computes and places the single net order for you, so prefer it and write nothing extra. Manage it by hand ONLY when the spec mandates a manual share count; then place one order for the NET delta:
```csharp
var target = side * shares;                         // new desired signed position
var delta = target - Portfolio[symbol].Quantity;
if (delta != 0)
{
    MarketOrder(symbol, delta);                    // one order carries the close + the new entry
}
```

Two manual orders (close then open) double the fees/slippage, and — because the closing order's margin is not released until it fills — a same-bar entry right after it can hit `InsufficientBuyingPower` even though the net position is affordable. One order (or `SetHoldings`) avoids both.

## Order type
- Default to `SetHoldings` / `Liquidate` (market orders) unless the method calls for a specific order type. Use `MarketOnCloseOrder` only when the method requires a fill at the official close.

## Set the security leverage to match the method's sizing
If the method sizes positions above 1× notional (any use of leverage/margin — e.g. a 4× volatility target), pass `leverage:` on the subscription: `AddEquity("SPY", Resolution.Minute, leverage: 4)`. The default equity margin is ~2× (Reg-T 50% initial), so without this every order targeting more than 2× is rejected for `InsufficientBuyingPower`. Set it to the maximum leverage the method needs — it is a ceiling, not a target; the sizing formula still decides the actual exposure.

## Order rejected for buying power (manual sizing at the leverage cap)
A market order's buying-power check runs at SUBMISSION and values the order at the security's CURRENT price. If you hand-compute a share count off a DIFFERENT price — e.g. the day's open — and the price has risen by the time you submit, the order's required margin can exceed available buying power → `InsufficientBuyingPower`, and the order is REJECTED (it never fills) = a missed trade. This bites at the leverage cap: at max leverage there is no headroom for the price to rise above the price you sized off, so any up-move makes the order unaffordable.
- **`Settings.FreePortfolioValuePercentage` does NOT help a manual `MarketOrder`** — that buffer only shrinks the size the auto-sizing helpers (`SetHoldings` / `PortfolioTarget` / `CalculateOrderQuantity`) compute; a hand-computed share count is submitted at full size regardless.
- **Fix: prefer `SetHoldings`, which sizes AND clamps to buying power for you.** If the position can be expressed as a target weight, `SetHoldings(symbol, weight)` converts it to an affordable, lot-rounded order — no manual math, no rejection. Drop to a manual order only when the spec mandates a manual share count; then size it with `CalculateOrderQuantity` (never raw arithmetic):
```csharp
// affordable, lot-rounded count for a signed leverage/weight target (e.g. side * Math.Min(4, 0.02/sigma)):
var qty = CalculateOrderQuantity(symbol, targetWeight);
if (qty != 0)
{
    MarketOrder(symbol, qty);
}
```

  A spec's `floor(AUM · leverage / price)` reduces to exactly this once buying power is respected, so reach for `CalculateOrderQuantity` rather than hand-rolling the margin math (`InitialMarginParameters` / `GetInitialMarginRequirement` / `MarginRemaining`). When the full intended size cannot fit (max leverage + an up-move since you sized), you then hold the largest size that fits rather than skipping — a capped position beats a missed trade. Treat a rejected order in the logs as a missed trade and a bug, not an acceptable outcome.
- **Compute the cap at the moment of placement, NOT earlier.** `CalculateOrderQuantity` (and any affordable-count check) reflects price and buying power AT THE INSTANT IT IS CALLED. If the spec fixes a daily share count off the open, keep that count as the TARGET — but cap each order to what fits at the CURRENT price *when you place it*:
```csharp
var affordable = CalculateOrderQuantity(symbol, side * leverage);   // evaluated ON the placement bar
var qty = side * Math.Min(Math.Abs(dailyCount), Math.Abs(affordable));
```

  Precomputing the cap once at the open and holding it fixed does NOT protect a later order: at the open it simply returns the open-based count (= the spec count, no headroom), and by the time you submit at a higher price the buying-power check uses that new price and rejects anyway. The cap is only meaningful when computed at the same bar you submit on. **Never SKIP an entry for buying power and never let it ERROR — always cap to the affordable count and trade the capped size.** A skipped order and a rejected order are both missed trades; a buying-power gate that returns early is the same bug as a rejection. Even when the spec fixes a daily count, clipping the rare bar where the full count will not fit (entering a few fewer shares) is the correct platform realization — not a violation of "the same count", just the count clipped to what the account can actually hold.

## Fee / slippage / fill / buying-power models
- Do NOT override these models. QuantConnect's defaults are realistic and already charge commissions and slippage. Only set a custom model if the spec EXPLICITLY names a specific one. "Account for realistic costs" / "don't assume zero costs" means keep the defaults — it does NOT mean add a custom model.
- **Never fake or disable transaction costs.** Do not zero/replace the fee model to emulate a paper's cost assumptions (`SetFeeModel(new ConstantFeeModel(0))` or similar), and NEVER mutate `Portfolio.CashBook` / cash balances directly — that corrupts accounting and bypasses the platform's reality models. A paper's "κ bps per trade / net-of-transaction-cost" formula is a *reporting convention*, not something to implement. If a spec appears to mandate a bps-style cost model, flag it and build without it — the platform defaults are the only cost baseline.
