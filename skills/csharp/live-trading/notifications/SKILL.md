---
name: notifications
description: Use when a QuantConnect/LEAN live algorithm sends data out or receives external instructions. Triggers — code uses `Notify.Email`/`Notify.Sms`/`Notify.Telegram`/`Notify.Web`/`Notify.Ftp`/`Notify.Sftp`, `SignalExport.*`, `OnCommand`, `AddCommand`, `BroadcastCommand`, `Link()`, or a class implementing `ISignalExportTarget` / `Command`; phrases like "Discord/Slack alert on fills", "email on drawdown", "push portfolio targets to Collective2/Numerai/vBase/our endpoint", "custom signal export to my broker", "manual liquidate from outside", "parent algo signals child", "multi-algorithm arbitrage". Skip when — purely in-algo log/debug (use `logging` skill).
---

# External Communication in QuantConnect / LEAN

A live-trading algorithm has several primitives for talking to the outside world. The right choice depends on **direction** (out / in) and **content** (trade data / human alert / file / instruction). Two recurring decisions are easy to get wrong:

- **Trade data going out:** prefer **Signal Exports** over `notify.web`.
- **Instructions coming in:** prefer **Commands** over polling the Object Store.

## Decision matrix

| Direction | Content | Primitive |
| --- | --- | --- |
| Out | Portfolio targets / trade signals to a platform | **Signal Exports** (bundled Collective2 / Numerai / vBase, or custom `ISignalExportTarget`) |
| Out | Human-facing alert (fill, error, daily summary) | `notify.email` / `sms` / `telegram` / `web` |
| Out | Bulk file delivery to a counterparty | `notify.ftp` / `notify.sftp` |
| In | Manual instruction (liquidate, parameter change) | **Commands** (`OnCommand` or `Command` subclass) |
| In | Cross-algorithm coordination (parent → child, arbitrage) | **Commands** + `project_id` link, or `broadcast_command` |

## Critical: live-trading gating

`notify.*` and command-send primitives are **Cloud live trading only** — no-ops in backtests, local LEAN, and LEAN CLI live deployments. **Signal Exports are different**: they fire anywhere the algorithm runs, including backtests.

- For `notify.*` and command sends (`Link`, `BroadcastCommand`, `DownloadData`): gate every call site with `LiveMode`.
- For Signal Exports to a production endpoint: either skip `AddSignalExportProvider` when not in live mode, or short-circuit at the top of `send` / `Send`.

# Sending OUT

## Trade signals / portfolio targets → Signal Exports

For portfolio targets to a fund, allocator, or trading platform: use **Signal Exports**, not `notify.web`. The signal-export manager:

- Debounces fills into one batched call (default 5s window).
- Passes a standardized `PortfolioTarget` list, not an ad-hoc payload.
- Has bundled providers for Collective2, Numerai, vBase.
- Survives broker reconnects without duplicating the payload.

For Collective2/Numerai/vBase the bundled provider is enough — register via `SignalExport.AddSignalExportProviders(...)` in `initialize`, then call `SignalExport.SetTargetPortfolioFromPortfolio()` to push.

For any other destination, write a custom `ISignalExportTarget`. Don't reach for `notify.web` to roll your own.

**Why not `notify.web` for trade data?**

- `notify.web` is fire-and-forget HTTP POST with a 300s receiver timeout, no retry, no debouncing. Every fill triggers a separate call → quota burn, race conditions, partial state at the receiver.
- Signal Exports send a single coherent portfolio snapshot per debounce window — the receiver sees the *intended* state, not a stream of deltas.
- Trade-data payloads tend to drift (per-symbol weights, metadata). A signal exporter encapsulates the schema; ad-hoc webhook bodies drift across versions.

Reach for `notify.web` only when the recipient is a notification consumer (Discord channel, monitoring webhook), not a trading system that's about to act on the payload.

### Custom Signal Exports

Implement `ISignalExportTarget` — a class with `bool Send(SignalExportTargetParameters parameters)` and `void Dispose()`. Register in `initialize` via `SignalExport.AddSignalExportProvider(...)`. `parameters.Algorithm` is the algorithm; `parameters.Targets` is the list of `PortfolioTarget`s.

#### The only real question: what payload does the receiver expect?

QuantConnect doesn't define the wire format — the receiving service does. Before writing the class, get from the user (or the receiver's API docs):

1. **Endpoint URL and auth.** Bearer token, API key header, HMAC body? Lift to module-level constants or constructor args, not hard-coded inside `send`.
2. **Field names and types.** `{symbol, quantity}`? `{ticker, weight}`? `{asset, side, size}`? The receiver's contract drives every line of the body-build code.
3. **Per-target or per-batch?** Most APIs accept a list — `send` is called once per aggregated batch of fills, so build one request, not N.
4. **Quantity in shares or in weight?** `parameters.Targets[i].Quantity` is a **portfolio weight** (a fraction like `0.10`), not a share count. If the receiver wants share counts, round-trip via `PortfolioTarget.Percent(parameters.Algorithm, x.Symbol, x.Quantity, x.Tag)` — the returned target's quantity is the share count.

Pass `tag` through — it's the only carrier of caller-provided context (signal id, regime, confidence) and is empty unless the calling code created targets with a tag.

#### Three things that bite

- **Reuse one HTTP client.** Allocate `HttpClient` once in the constructor, close in `dispose`. Building a fresh client per `send` exhausts ephemeral ports under load.
- **Return a `bool`, don't raise.** Catch network and JSON errors and return `False`. An exception escaping `send` aborts the algorithm.
- **`send` fires in backtests too.** Either skip `add_signal_export_provider` when not in live mode, or short-circuit at the top of `send` with a `live_mode` check.

## Human-facing alerts → `notify.*`

Six channels for messages a human (not a trading system) will read:

```
notify.email(address, subject, message, data=None, headers=None)
notify.sms(phone_number, message)
notify.telegram(id, message, token=None)
notify.web(address, data, headers=None)
notify.ftp(hostname, username, password, file_path, file_content, port=None)
notify.sftp(hostname, username, password|private_key + private_key_passphrase, file_path, file_content, port=None)
```

| Channel | Body limit | Per-channel notes |
| --- | --- | --- |
| Email | 10 KB body, optional named attachment | `headers={'filename': '...'}` to override default `attachment.txt` |
| SMS | 1,600 chars; E.164 phone (`+1...`) | Only channel with no free quota; per-message QCC cost (1 US/CA, 10 international) |
| Telegram | Plain text via bot in a group | Group ID is a negative integer; emojis must be UTF-32 escapes (`'\U0001f680'`); `token` is optional only if `@quantconnect_notifications_bot` is in the group |
| Web (HTTP POST) | 300s receiver timeout | No retry. Discord receivers expect a `content`-keyed JSON envelope, not a plain string |
| FTP / SFTP | Sends file content under `file_path` | SFTP accepts `password=` OR `private_key=` (with optional passphrase), not both. FTP is plaintext |

### Hourly free quota by tier

| Tier | Notifications per hour |
| --- | --- |
| Free | N/A |
| Quant Researcher | 20 |
| Team | 60 |
| Trading Firm | 240 |
| Institution | 3,600 |

The hourly quota covers email, FTP, Telegram, and webhook combined; overage costs 1 QCC per notification. **SMS is excluded** and always billed per message regardless of tier.

### Wrap repeated calls in a helper

When the same channel fires from more than one place, route every call through a private helper (e.g. `_notify_email`, `_notify_sms`) so the recipient/credentials and the `live_mode` gate live in one place. Lift recipients to module-level constants. One-off notifications can stay inline.

### Fire on events, not per-bar

Putting `notify.*` inside `OnData` on minute or tick resolution saturates the hourly quota in seconds and burns QCC. Notifications belong on events:

- `OnOrderEvent` — filled / canceled / rejected.
- `OnBrokerageMessage` — broker errors, disconnects.
- `OnWarmupFinished`.
- Scheduled events (daily summary, threshold alerts) — see the `scheduled-events` skill.
- Signal / state transitions — only on the *change*, not every bar the condition is true.

### Don't send raw subscribed-dataset content

The notification system **can't be used for data distribution** — that's a terms-of-use violation, not a quota concern. Send derived information (signal value, portfolio value, fill summary), not raw bars/quotes/trades from QuantConnect-subscribed datasets.

# Receiving IN — Commands

Commands inject data **into** a running live algorithm. Typical uses: a manual "panic close-all", a click-to-confirm grey-box trade, or coordinating sibling algorithms (arbitrage between exchanges, child strategies offloading execution to a brokerage-connected parent).

Commands give you authenticated delivery routed through QC infrastructure, real-time pickup (no polling latency), and both REST/API broadcast and project-targeted send (via `project_id` + `Link().DownloadData()`) for parent/child coordination.

**Why not poll the Object Store?**

You *can* technically have one algorithm write a JSON blob to the Object Store and another read it. Don't:

- Object Store reads aren't real-time. You'd need a per-bar or scheduled poll — which adds latency and burns quota.
- Concurrent-write semantics are undefined: two writers can clobber each other silently.
- No delivery acknowledgement. A skipped read fails open, not closed.
- Object Store is for persistent state (trained models, accumulated features, end-of-run CSVs); Commands are the queue.

## Before writing any code: ask the user about the payload

Handler signature, `Command` subclass fields, and the sender script all depend on the payload shape. Before generating code, ask:

1. **What fields will the command carry?** (e.g. `ticker` + `quantity`, or `action` + `target_weight`.) Use those exact names — they become attribute accesses inside the handler.
2. **One command shape, or several?** One → generic `OnCommand(dynamic data)`. Several → encapsulated `Command` subclasses dispatched by `$type`.
3. **How will it be sent?** REST API, email click-link, broadcast from a sibling, or LEAN CLI. This decides whether to add a `live_mode`-guarded `Link(...)` / `BroadcastCommand(...)` inside the algorithm.

## Always link the user to the sender-script docs

Don't inline auth/REST boilerplate in the algorithm. Add a comment with a pointer to https://www.quantconnect.com/docs/v2/writing-algorithms/live-trading/commands#06-Send-Commands-by-API (or `#07-Broadcast-Commands-by-API` for org-wide broadcasts), plus the payload shape this handler reads.

## Two handler styles: generic vs encapsulated

The dispatcher routes on whether the payload contains a `$type` key.

- **Generic** (single command shape): override `OnCommand(dynamic data)`. Payload without `$type` arrives here. Keys become attributes (py`data.ticker` / `data["ticker"]`cs`data.Ticker`).
- **Encapsulated** (multiple distinct shapes): subclass `Command` (Python: class with class-level attributes; C#: properties), register with `AddCommand<MyCommand>()`. Payload with `$type` → matching registered `Command` subclass; other keys populate the instance fields. With `$type` set but the class not registered on the receiver, the command is dropped silently — relevant for `broadcast_command`.

**Subclass-method access inside `run` / `Run`.** `algorithm` is typed as `IAlgorithm`. Methods on base `QCAlgorithm` (`log`, `set_holdings`, `portfolio`, …) work, but **methods you defined on your own subclass aren't reachable through it**.

- Python: set `MyCommand.ALGORITHM = self` in `initialize` and call through the static (e.g. `MyCommand.ALGORITHM.do_something()`).
- C#: cast — `((MyAlgorithm)algorithm).DoSomething()`.

The return value (`bool?`) surfaces in the live commands log and in `DownloadData()`'s response body — always return `True` on success or `False` on rejection rather than implicit `None`.

## Sending commands: four mechanisms

| Mechanism | Reaches | Use it for |
| --- | --- | --- |
| `Link(...)` | One project (caller's by default; another via `project_id`) when the URL is opened | Email/Slack click-to-run, grey-box confirmations |
| `BroadcastCommand(...)` | Every live deployment in the org **except** the caller, **except** algos without the `$type` registered | Sibling-algorithm coordination |
| REST `POST /live/commands/create` | One project, by `projectId` | External orchestration (script, webhook, dashboard) |
| REST `POST /live/commands/broadcast` | Whole organization (with optional `excludeProjectId`) | External fan-out |

LEAN CLI wraps the same REST endpoints. The receiver doesn't know or care which mechanism delivered the payload — all four funnel into the same `OnCommand` / `Command.Run` dispatcher.

The `OrderCommand` `$type` is built-in — every live deployment understands it without `add_command` and places the order described by the payload (`symbol`, `order_type`, `quantity`, `limit_price`, `stop_price`, `tag`).

### Cross-project send for parent/child

Set `algorithm.ProjectId` to the target project before calling `link`. `link.DownloadData()` executes synchronously and returns the response body — use it for the parent-child execution-offload pattern (paper-trading child sends commands to a brokerage-connected parent that places the actual orders).

### Broadcast scope rules

- **The sender does not receive its own broadcast.** If you need fan-out *plus* local execution, run the local path first, then broadcast.
- A typed command is delivered only to algorithms that registered the same `Command` class via `add_command`; others drop it silently.
- A generic command (no `$type`) reaches every live algorithm in the org that defines `on_command`.
- LEAN does **not** add a sender field — always include `sender = ProjectId` so receivers can disambiguate which sibling sent it.

## Multi-algorithm coordination

Arbitrage and parent-child execution are the same pattern with different mechanisms:

- **Symmetric N-to-N (e.g. arbitrage between exchanges):** `broadcast_command` — each algorithm broadcasts fills from `OnOrderEvent` (gated on `live_mode` and `OrderStatus.Filled`) and reacts to siblings' broadcasts in `on_command`.
- **One-to-one with response (e.g. paper-trading child offloading execution to a brokerage-connected parent):** `link` + `project_id` + `link.DownloadData()` — the child sets `project_id` to the parent and calls `download_data` from `on_order_event` to fire synchronously and read the parent's return value.

For a parent that just translates payloads into orders, set `Settings.SeedInitialPrices = true` in `initialize` so newly-added symbols can be traded on the first command.

## Command gotchas

- **LEAN uppercases the first character of every payload key when delivering to C#.** A payload sent as `{"ticker": "AAPL"}` reads as `data.Ticker` in C# but `data.ticker` in Python. When defining an encapsulated `Command` subclass, name C# properties PascalCase (`Ticker`, `Quantity`).
- **`dynamic` defers field-resolution to runtime.** `data.Ticker` compiles even if the field doesn't exist; missing fields throw at runtime. Validate before use.
- **Cast numeric fields explicitly** — `var qty = (decimal)data.Quantity;` — JSON deserializes integers as `int`/`long` and arithmetic mixing types throws.
- **`Link(new { ... })` uses an anonymous object whose property names go on the wire verbatim.** `new { Ticker = "AAPL" }` produces `{"Ticker": "AAPL"}`, which a Python sibling reads as `data.Ticker` (not `data.ticker`). For lowercase keys, use `Dictionary<string, object>`.

# Universal rules

- **`LiveMode` gate every `notify.*` and command-send site.** Notifications and command-send primitives are no-ops outside Cloud live trading.
- **Signal Exports run everywhere (incl. backtests).** Either skip provider registration when not in live mode, or short-circuit at the top of `send`.
- **Fire on events, not per-bar.** Every channel here has a quota or rate-limit; per-bar firing exhausts it.
- **No raw subscribed-dataset content in any payload.** Derived values only.

# Checklist

## Direction & primitive

1. **Direction?** Out → notify or Signal Export. In → Command. Don't poll Object Store as a queue.
2. **Out, payload is portfolio targets / trade signals?** Signal Exports, not `notify.web`. Bundled provider for Collective2/Numerai/vBase; custom class for anything else.
3. **Out, recipient is a human / monitoring system?** Pick the right `notify.*` channel.
4. **In, instruction or coordination?** Commands.

## Notifications

5. Every `notify.*` call gated with `LiveMode`?
6. Channel used more than once → routed through a `_notify_*` helper with credentials at module scope?
7. Call site is an event handler, not per-bar `on_data`? If per-bar, can it be gated on a real state change?
8. **SMS:** urgent enough to justify per-message QCC? Phone in E.164? Body under 1,600 chars?
9. **Email:** attachment filename via `headers={'filename': ...}`? Body under 10 KB?
10. **Telegram:** group ID a negative integer? Bot in the group (or `token` passed)? Emojis as UTF-32 escapes?
11. **Webhook:** receiver responds within 300s? Discord targets wrapped in `{"content": ...}`?
12. **FTP vs SFTP:** auth method matches the server (password vs SSH key, not both)?
13. Payload contains raw subscribed-dataset content? Swap for a derived value.

## Custom Signal Exports

14. Reuse one HTTP client via the constructor; close in `dispose`.
15. Return a `bool` — never raise out of `send`.
16. Live-mode gated if the endpoint is production.
17. Quantity is a weight, not a share count — use `PortfolioTarget.Percent` to convert if needed.
18. Pass `tag` through — it's the only carrier of caller-provided context.

## Commands

19. Asked the user about payload fields, single vs multi-shape, and send mechanism?
20. Comment in the algorithm pointing at the sender-script docs + the payload shape this handler reads?
21. Send sites gated with `LiveMode`?
22. Encapsulated commands registered with `add_command` on every receiver?
23. `Command.Run` returns `True`/`False`, not implicit `None`?
24. Broadcast payloads include `sender = ProjectId`?
25. Python `Command.run` calling subclass methods? Use the static-reference pattern (`MyCommand.ALGORITHM = self`).
26. Broadcast sites filter `on_order_event` to `OrderStatus.Filled`?
27. C# payload field access uses PascalCase? Numeric fields explicitly cast?
