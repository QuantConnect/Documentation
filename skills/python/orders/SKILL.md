---
name: orders
description: Use whenever placing orders, sizing positions to target weights, exiting/flattening, choosing an order type (incl. market-on-close), or deciding on fee/slippage/fill models. Prefer QuantConnect built-ins over hand-rolled order math.
---

# Orders, sizing, exits, and execution models — use QC built-ins, don't reinvent

## Sizing to a target weight with MARKET orders (the common case)
- Equities/ETFs and most assets: `self.set_holdings(symbol, weight)`. For a basket, pass a list in ONE call so QC sizes them together:
  `self.set_holdings([PortfolioTarget(uso, 0.5), PortfolioTarget(uga, 0.5)])` (add `liquidate_existing_holdings=True` to also flatten anything not in the list).
- Let QC convert weights to share counts — do NOT hand-compute quantities or place raw `market_order`s to hit a target weight.
- FUTURES are the exception: `set_holdings` sizes off (already-leveraged) buying power and over-levers a futures position, so size futures by integer contracts from notional — see the futures skill.

## Small target weights silently dropped — the minimum-order filter
`set_holdings` / `PortfolioTarget` sizing runs every target through minimum-order thresholds (`settings.min_absolute_portfolio_target_percentage`, `settings.minimum_order_margin_portfolio_percentage`). On a broad book — hundreds of names, value-weighted legs whose tail weights are a few basis points — targets below the threshold are SILENTLY skipped: selected constituents never get an order and nothing errors. When the method's portfolio legitimately contains very small weights, zero the thresholds in `initialize`:
```python
self.settings.min_absolute_portfolio_target_percentage = 0
self.settings.minimum_order_margin_portfolio_percentage = 0
```
Never "fix" this by pruning tiny weights in strategy code — that silently changes the method's portfolio. And check starting cash first: if the smallest intended position is only a few hundred dollars, minimum per-order fees dominate returns and high-priced names round to zero shares — scale `set_cash` to the book's breadth (smallest weight × cash ≥ roughly $10,000) rather than dropping names.

## Sizing to a target weight AT THE CLOSE (market-on-close)
`set_holdings` sends MARKET orders (next-bar fill), so it does NOT give a close fill. When the method requires entering/exiting at the official close, compute the quantity for the target weight, then place a market-on-close order:
```python
qty = self.calculate_order_quantity(symbol, weight)   # decimal shares for the target %, lot-size-rounded
self.market_on_close_order(symbol, qty)
```

Do this per symbol for a basket. `calculate_order_quantity` accounts for current holdings, buying power, and fees, so it is the correct way to turn a target weight into a share count without hand math.

## Market-on-close submission timing — do NOT lower the buffer
QC rejects a MarketOnClose order submitted within `MarketOnCloseOrder.SubmissionTimeBuffer` of the close (default 15.5 minutes); it errors `MarketOnCloseOrderTooLate` and nothing fills. Do NOT lower this buffer to submit closer to the close: the 15.5-minute default reflects a real constraint (with minute data the 15:44->15:45 bar only arrives at 15:45 — too late to act on and still submit — and real exchange MOC entry cutoffs are ~10-15 min before the close), so a lowered buffer fills orders that are not achievable live.
(It cannot be lowered anyway — `MarketOnCloseOrder.DEFAULT_SUBMISSION_TIME_BUFFER` is read-only in Python; assigning to it raises `field is read-only` and fails the algorithm at init. Do not try to set it.)
When a method enters or exits at the close, sample the signal and submit the MOC order at least ~15.5 minutes before the close, e.g. via a scheduled event at `before_market_close(symbol, 16)`. `before_market_close` (and `after_market_open`) resolve to the security's ACTUAL session boundary for each date, so they handle early-close and holiday sessions automatically — anchor close-of-day logic to them, not to a hardcoded clock time (a literal 16:00 is wrong on early-close days). A too-late MOC fails QUIETLY — `MarketOnCloseOrderTooLate` does not reliably surface in a generic error-log search; the visible symptom is positions surviving past the close (held overnight) and being flattened at the next session's open. So when you use `market_on_close_order`, VERIFY the position actually goes flat at the close: if anything carries to the next session, the MOC is being rejected and you must submit it earlier (≥15.5 min before the close). When the method says exit AT the close, the MOC submitted ≥15.5 min early (`before_market_close(symbol, 16)`) is the FAITHFUL instrument — it fills at the official closing auction, which is what "at the session close" means. Do NOT "fix" a too-late MOC by switching to a plain market order at `before_market_close(symbol, 1)`: that fills ~1 minute BEFORE the close (on the 15:59 bar), not at the close — a less-faithful exit. A market order at the bell is acceptable only when the method does not require the official-close price (or the asset has no closing auction, e.g. crypto).

## Market-on-close is for exchange-traded assets only — NOT crypto
Market-on-close fills at an exchange's official closing auction. Cryptocurrencies trade 24/7 and have no closing auction, so `market_on_close_order` and its submission-buffer timing do NOT apply to crypto. For a daily crypto rebalance, fire a scheduled event at your chosen daily boundary (e.g. midnight UTC) and rebalance with `set_holdings` / market orders. Do not deliberate market-on-close for crypto — it is not a thing there.

## MarketOnOpen fills on daily resolution
A market order submitted while the market is closed converts to MarketOnOpen; on daily resolution it is not processed until the session's daily bar arrives, and fills at that bar's open. Nothing can observe the fill before then — never schedule an event to check whether it filled, and never `self.quit()` earlier: the order still reads `Submitted` with flat equity, which looks like a platform bug but is just a truncated run. Verify fills from the completed backtest's order records instead.

## Exiting / flattening
- Market-order exit: `self.liquidate()` closes ALL holdings, `self.liquidate(symbol)` closes one.
- Exit AT THE CLOSE: `liquidate` sends a market order, so for a close fill flatten with an MOC order for the negative of the current position:
  `self.market_on_close_order(symbol, -self.portfolio[symbol].quantity)`.

## Engine-generated delisting liquidations in the order records
When a held security delists, LEAN automatically closes the position with a market order tagged `Liquidate from delisting` — outside the strategy's own schedule. These are platform processing, not strategy orders: expect a few in any long backtest that holds many names. When verifying order records against the strategy's timing invariants ("orders only at rebalances", "flat by close", ...), count these engine liquidations as compliant platform behavior, not violations — and do not add special delisting handling unless the method explicitly requires it.

## Reversing a position
A reversal needs only ONE order, not a `liquidate` then a separate entry. With `set_holdings` this is automatic — `set_holdings(symbol, new_weight)` (e.g. the opposite sign) computes and places the single net order for you, so prefer it and write nothing extra. Manage it by hand ONLY when the spec mandates a manual share count; then place one order for the NET delta:
```python
target = side * shares                              # new desired signed position
delta = target - self.portfolio[symbol].quantity
if delta != 0:
    self.market_order(symbol, delta)               # one order carries the close + the new entry
```

Two manual orders (close then open) double the fees/slippage, and — because the closing order's margin is not released until it fills — a same-bar entry right after it can hit `InsufficientBuyingPower` even though the net position is affordable. One order (or `set_holdings`) avoids both.

## Order type
- Default to `set_holdings` / `liquidate` (market orders) unless the method calls for a specific order type. Use `market_on_close_order` only when the method requires a fill at the official close.

## Order tags
Pass the tag by name. The parameter after the prices is `asynchronous`, so a positional tag fails because the string fills the `bool` parameter.
```python
self.market_order(symbol, quantity, tag="rebalance")
self.limit_order(symbol, quantity, limit_price, tag="entry")
self.stop_market_order(symbol, -quantity, stop_price, tag="stop")
```
## Order events and status
Read the tag, fee, and order id from the event: `order_event.tag`, `order_event.order_fee.amount`, and `order_event.order_id` (`ticket.order_id` on a ticket). Check the status with `is_open()`, `is_closed()`, and `is_fill()`.
`OrderStatus` has nine members: `NEW, SUBMITTED, PARTIALLY_FILLED, FILLED, CANCELED, NONE, INVALID, CANCEL_PENDING, UPDATE_SUBMITTED`. It has no `OPEN` or `REJECTED` member; a rejected order is `INVALID`.
To cancel, call `cancel_open_orders` (plural) or `ticket.cancel()`.
```python
def on_order_event(self, order_event: OrderEvent) -> None:
    tag = order_event.tag
    fee = order_event.order_fee.amount
    is_open = order_event.status.is_open()
    is_closed = order_event.status.is_closed()
    is_fill = order_event.status.is_fill()
```
## Event times and fill order
`order_event.utc_time` and `order_event.time` are the same UTC time. `self.time` is in the algorithm time zone, and `bar.end_time` is in the exchange time zone of its security. Compare the event time with `self.utc_time`, or convert it to the time zone of the time you compare it with.
The event times are timezone-aware, and `self.time` and bar times are naive. Subtracting them or ordering them with `<` or `>` raises a `TypeError`, but `==` returns `False` without an error, even for the same clock time.
```python
age = self.utc_time - order_event.utc_time
local_time = Extensions.convert_from_utc(order_event.utc_time, self.time_zone)
exchange_time = Extensions.convert_from_utc(order_event.utc_time, self.securities[symbol].exchange.time_zone)
```
In a backtest, `on_order_event` can run inside the order call, before `market_order` returns, even with `asynchronous=True`. Assign the state the handler reads before you place the order. The handler can't read the returned ticket yet; use `order_event.ticket`. The order can also stay open after the call returns, for example while the fill waits for fresh data, so read the fill from the order event.
```python
# Wrong: on_order_event reads the old self._entry_reason.
self.market_order(symbol, 100)
self._entry_reason = "breakout"
# Right
self._entry_reason = "breakout"
self.market_order(symbol, 100)
```
## Take-profit and stop-loss pairs
Place the pair with one call instead of managing a stop and a limit order by hand. Use one of the following, not both, for the same position.

For a new entry, `bracket_order` places the entry and, once it fills, a take-profit limit order and a stop-loss stop market order. It returns the tickets of the entry, the take profit, and the stop loss.
```python
price = self.securities[symbol].price
tickets = self.bracket_order(symbol, 100, take_profit_price=price * 1.02, stop_loss_price=price * 0.99)
```
For a position you already hold, pass order factory requests to `one_cancels_other_order`.
```python
price = self.securities[symbol].price
quantity = self.portfolio[symbol].quantity
tickets = self.one_cancels_other_order([
    self.order_factory.limit_order(symbol, -quantity, price * 1.02),
    self.order_factory.stop_market_order(symbol, -quantity, price * 0.99)
])
```
In a backtest, when one leg fills, even partially, LEAN cancels the other, so the algorithm doesn't cancel the sibling by hand. Live, the brokerage manages the pair and can behave differently. For example, it can reduce the other leg after a partial fill, or fill both legs in a fast market before the cancel arrives. Brokerage support also varies. Some brokerages reject contingent orders, and others accept only some order types, such as a limit entry with limit or stop limit exits. LEAN rejects the whole group when the brokerage can't take it, which leaves the position without exits, so check the order types your brokerage supports and handle an `INVALID` status.

## Set the security leverage to match the method's sizing
If the method sizes positions above 1× notional (any use of leverage/margin — e.g. a 4× volatility target), pass `leverage=` on the subscription: `self.add_equity("SPY", Resolution.MINUTE, leverage=4)`. The default equity margin is ~2× (Reg-T 50% initial), so without this every order targeting more than 2× is rejected for `InsufficientBuyingPower`. Set it to the maximum leverage the method needs — it is a ceiling, not a target; the sizing formula still decides the actual exposure.

## Order rejected for buying power (manual sizing at the leverage cap)
A market order's buying-power check runs at SUBMISSION and values the order at the security's CURRENT price. If you hand-compute a share count off a DIFFERENT price — e.g. the day's open — and the price has risen by the time you submit, the order's required margin can exceed available buying power → `InsufficientBuyingPower`, and the order is REJECTED (it never fills) = a missed trade. This bites at the leverage cap: at max leverage there is no headroom for the price to rise above the price you sized off, so any up-move makes the order unaffordable.
- **`free_portfolio_value_percentage` does NOT help a manual `market_order`** — that buffer only shrinks the size the auto-sizing helpers (`set_holdings` / `PortfolioTarget` / `calculate_order_quantity`) compute; a hand-computed share count is submitted at full size regardless.
- **Fix: prefer `set_holdings`, which sizes AND clamps to buying power for you.** If the position can be expressed as a target weight, `set_holdings(symbol, weight)` converts it to an affordable, lot-rounded order — no manual math, no rejection. Drop to a manual order only when the spec mandates a manual share count; then size it with `calculate_order_quantity` (never raw arithmetic):
```python
# affordable, lot-rounded count for a signed leverage/weight target (e.g. side * min(4, 0.02/sigma)):
qty = self.calculate_order_quantity(symbol, target_weight)
if qty != 0:
    self.market_order(symbol, qty)
```

  A spec's `floor(AUM · leverage / price)` reduces to exactly this once buying power is respected, so reach for `calculate_order_quantity` rather than hand-rolling the margin math (`InitialMarginParameters` / `get_initial_margin_requirement` / `margin_remaining`). When the full intended size cannot fit (max leverage + an up-move since you sized), you then hold the largest size that fits rather than skipping — a capped position beats a missed trade. Treat a rejected order in the logs as a missed trade and a bug, not an acceptable outcome.
- **Compute the cap at the moment of placement, NOT earlier.** `calculate_order_quantity` (and any affordable-count check) reflects price and buying power AT THE INSTANT IT IS CALLED. If the spec fixes a daily share count off the open, keep that count as the TARGET — but cap each order to what fits at the CURRENT price *when you place it*:
```python
affordable = self.calculate_order_quantity(symbol, side * leverage)   # evaluated ON the placement bar
qty = side * min(abs(daily_count), abs(affordable))
```

  Precomputing the cap once at the open and holding it fixed does NOT protect a later order: at the open it simply returns the open-based count (= the spec count, no headroom), and by the time you submit at a higher price the buying-power check uses that new price and rejects anyway. The cap is only meaningful when computed at the same bar you submit on. **Never SKIP an entry for buying power and never let it ERROR — always cap to the affordable count and trade the capped size.** A skipped order and a rejected order are both missed trades; a buying-power gate that returns early is the same bug as a rejection. Even when the spec fixes a daily count, clipping the rare bar where the full count will not fit (entering a few fewer shares) is the correct platform realization — not a violation of "the same count", just the count clipped to what the account can actually hold.

## Fee / slippage / fill / buying-power models
- Do NOT override these models. QuantConnect's defaults are realistic and already charge commissions and slippage. Only set a custom model if the spec EXPLICITLY names a specific one. "Account for realistic costs" / "don't assume zero costs" means keep the defaults — it does NOT mean add a custom model.
- **Never fake or disable transaction costs.** Do not zero/replace the fee model to emulate a paper's cost assumptions (`set_fee_model(ConstantFeeModel(0))` or similar), and NEVER mutate `portfolio.cash_book` / cash balances directly — that corrupts accounting and bypasses the platform's reality models. A paper's "κ bps per trade / net-of-transaction-cost" formula is a *reporting convention*, not something to implement. If a spec appears to mandate a bps-style cost model, flag it and build without it — the platform defaults are the only cost baseline.
- When the spec does name a slippage model, use a built-in one: `ConstantSlippageModel(0.0005)` (a fraction of price), `VolumeShareSlippageModel()`, or `NullSlippageModel.INSTANCE`. For a custom model, implement `get_slippage_approximation`, which returns slippage in price units:
```python
class HalfSpreadSlippageModel:  # plain class with no ISlippageModel base
    def get_slippage_approximation(self, asset: Security, order: Order) -> float:
        return (asset.ask_price - asset.bid_price) / 2

self.securities[symbol].set_slippage_model(HalfSpreadSlippageModel())
```
