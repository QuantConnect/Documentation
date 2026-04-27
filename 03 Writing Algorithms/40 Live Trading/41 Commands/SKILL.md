---
name: live-commands
description: Use when adding or reviewing live-trading commands in a QuantConnect/LEAN algorithm — anything involving `on_command`, `add_command`, `link`, `broadcast_command`, or sending commands via the REST API / LEAN CLI. Covers the two handler styles (generic `on_command` vs encapsulated `Command` subclass with `$type` routing), the Python static-algorithm-reference pattern needed inside `Command.run`, gating sends on `live_mode`, broadcast scope (org-wide, excludes sender, drops unregistered `$type`), cross-project send via `project_id` + `link().download_data()`, and the multi-algorithm arbitrage / parent-child coordination patterns.
---

# Live Commands in QuantConnect / LEAN

Commands inject data **into** a running live algorithm — the inverse of notifications. Typical uses: a manual "panic close-all", a click-to-confirm grey-box trade, or coordinating sibling algorithms (arbitrage between exchanges, child strategies offloading execution to a brokerage-connected parent).

## Before writing any code: ask the user about the payload

Handler signature, `Command` subclass fields, and the sender script all depend on the payload shape. Before generating code, ask:

1. **What fields will the command carry?** (e.g. `ticker` + `quantity`, or `action` + `target_weight`.) Use those exact names — they become attribute accesses inside the handler.
2. **One command shape, or several?** One → generic `on_command(data)`. Several → encapsulated `Command` subclasses dispatched by `$type`.
3. **How will it be sent?** REST API, email click-link, broadcast from a sibling, or LEAN CLI. This decides whether to add a `live_mode`-guarded `link(...)` / `broadcast_command(...)` inside the algorithm.

## Always link the user to the sender-script docs

Don't inline auth/REST boilerplate in the algorithm — the docs page is the source of truth and stays in sync with API changes. Add a comment with a pointer plus the payload shape this handler reads:

```python
# To send this command, follow the sender-script guide at
# https://www.quantconnect.com/docs/v2/writing-algorithms/live-trading/commands#06-Send-Commands-by-API
# (use #07-Broadcast-Commands-by-API for organization-wide broadcasts).
#
# This handler reads: {"ticker": "...", "quantity": ...}
# For a $type-routed command, also include "$type": "MyCommand".
```

## Critical rule: commands only run live

`on_command` never fires in a backtest. `link`, `broadcast_command`, and `download_data()` only act against live deployments. **Gate every *send* site with `self.live_mode`** — unguarded calls in `on_data` / `on_order_event` waste backtest cycles producing nothing. The handler itself doesn't need a guard; it isn't invoked in backtests at all.

## Two handler styles: generic vs encapsulated

The dispatcher routes on whether the payload contains a `$type` key.

### Generic — override `on_command(data)`

For a single command shape (or a small ad-hoc set discriminated by your own field):

```python
class BasicCommandAlgorithm(QCAlgorithm):
    def initialize(self):
        self.set_benchmark(lambda x: 1)  # No asset data needed.

    def on_command(self, data):
        self.log(f"Got command at {self.time} with data: {data}")
        return True   # True = success, False = failed, None = no-op / unrecognized
```

Payload without `$type` → `on_command`. Keys become attributes (`data.ticker` or `data["ticker"]`).

### Encapsulated — extend `Command` and register with `add_command`

For multiple distinct shapes:

```python
class EncapsulatedCommandAlgorithm(QCAlgorithm):
    def initialize(self):
        MyCommand.ALGORITHM = self          # See "static reference" below.
        self.add_command(MyCommand)

    def do_something(self):
        self.log("Something was done!")

class MyCommand(Command):
    ALGORITHM = None
    ticker = None
    quantity = None

    def run(self, algorithm):
        algorithm.log(f"ticker: {self.ticker}; quantity: {self.quantity}")
        MyCommand.ALGORITHM.do_something()  # Reach the concrete algorithm via the static.
        return True
```

Payload with `$type` → matching registered `Command` subclass (other keys populate the instance fields). With `$type` set but the class not registered on the receiver, the command is dropped silently — relevant for `broadcast_command`.

**Static reference (Python-only):** `algorithm` inside `run` is typed as `IAlgorithm`. Methods on base `QCAlgorithm` (`log`, `set_holdings`, `portfolio`, …) work, but **methods you defined on your own subclass aren't reachable through it**. Set `MyCommand.ALGORITHM = self` in `initialize` and call through the static for any custom method.

The return value (`Optional[bool]`) surfaces in the live commands log and in `download_data()`'s response body — always return `True` on success or `False` on rejection rather than implicit `None`.

## Sending commands: four mechanisms

| Mechanism | Reaches | Use it for |
| --- | --- | --- |
| `self.link(...)` | One project (caller's by default; another via `project_id`) when the URL is opened | Email/Slack click-to-run, grey-box confirmations |
| `self.broadcast_command(...)` | Every live deployment in the org **except** the caller, **except** algos without the `$type` registered | Sibling-algorithm coordination |
| REST `POST /live/commands/create` | One project, by `projectId` | External orchestration (script, webhook, dashboard) |
| REST `POST /live/commands/broadcast` | Whole organization (with optional `excludeProjectId`) | External fan-out |

LEAN CLI wraps the same REST endpoints. The receiver doesn't know or care which mechanism delivered the payload — all four funnel into the same `on_command` / `Command.run` dispatcher.

The `OrderCommand` `$type` is built-in — every live deployment understands it without `add_command` and places the order described by the payload (`symbol`, `order_type`, `quantity`, `limit_price`, `stop_price`, `tag`).

### Link → email confirmation (grey-box)

```python
def initialize(self):
    self.set_benchmark(lambda x: 1)
    link = self.link({"ticker": "AAPL", "quantity": 1})
    self.notify.email("you@example.com", "Run Command?", f"Click here to run: {link}")
```

For an encapsulated command, build the instance and pass it in — `$type` is added automatically:

```python
potential = MyCommand()
potential.ticker, potential.quantity = "AAPL", 1
link = self.link(potential)
```

### Cross-project send — set `project_id` before `link`

`link` defaults to the caller's project. Repointing it sends to **another** live deployment, and `Extensions.download_data(link)` executes synchronously and returns the response body:

```python
algorithm.project_id = 30123456                       # Target project.
link = algorithm.link({"ticker": ticker, "quantity": quantity})
return Extensions.download_data(link)
```

This is the parent-child execution-offload pattern: a paper-trading child sends commands to a brokerage-connected parent that places the actual orders.

### Broadcast — fan-out across the organization

```python
self.broadcast_command({"sender": self.project_id, "ticker": "AAPL", "quantity": 1})
# or, with an encapsulated command:
self.broadcast_command(potential_command)
```

Scope rules:

- **The sender does not receive its own broadcast.** If you need fan-out *plus* local execution, run the local path first, then broadcast.
- A typed command is delivered only to algorithms that registered the same `Command` class via `add_command`; others drop it silently.
- A generic command (no `$type`) reaches every live algorithm in the org that defines `on_command`.
- LEAN does **not** add a sender field — always include `"sender": self.project_id` so receivers can disambiguate which sibling sent it (and filter on `data.sender` if needed).

## Multi-algorithm coordination

Arbitrage and parent-child execution are the same pattern with different mechanisms.

- **Symmetric N-to-N (e.g. arbitrage between exchanges):** `broadcast_command` — each algorithm broadcasts fills from `on_order_event` (gated on `live_mode` and `OrderStatus.FILLED`) and reacts to siblings' broadcasts in `on_command`.
- **One-to-one with response (e.g. paper-trading child offloading execution to a brokerage-connected parent):** `link` + `project_id` + `Extensions.download_data(link)` — the child sets `project_id` to the parent and calls `download_data` from `on_order_event` to fire synchronously and read the parent's return value.

Typical broadcast-from-fill site:

```python
def on_order_event(self, order_event):
    if not self.live_mode or order_event.status != OrderStatus.FILLED:
        return
    self.broadcast_command({
        "sender": self.project_id,
        "ticker": order_event.symbol.value,
        "quantity": self.portfolio[order_event.symbol].quantity,
    })
```

For a parent that just translates payloads into orders, set `self.settings.seed_initial_prices = True` in `initialize` so newly-added symbols can be traded on the first command.

## Common mistakes to avoid

- **Sending commands without a `live_mode` guard.** `link`, `broadcast_command`, and `download_data` are no-ops in backtests. Wrap any *send* with `if self.live_mode:`.
- **Calling a custom algorithm method on `algorithm` inside `Command.run`.** That parameter is `IAlgorithm`; your method isn't on it. Use `MyCommand.ALGORITHM` instead.
- **Forgetting `add_command`.** A payload with `$type: "MyCommand"` is silently dropped on every algorithm that hasn't registered `MyCommand`. Easy to miss when broadcasting — register the same class on every receiver.
- **Mixing `$type` and generic `on_command`.** With `$type` present, `on_command` is **not** called. For "log every command", log inside `Command.run` or omit `$type`.
- **Expecting the broadcaster to receive its own broadcast.** It doesn't.
- **No `sender` field on broadcast payloads.** Receivers can't tell who sent it. Include `"sender": self.project_id`.
- **Returning implicit `None` from a handler.** Reads as "no-op" in the live commands log even on success. Return `True` / `False`.
- **Not filtering `on_order_event` to `FILLED` before broadcasting.** Otherwise you broadcast on every partial / submitted / canceled status update.

## C# equivalents

Every concept above applies identically to C# — only the syntax differs. Mapping:

| Python                                                 | C#                                                       |
| ------------------------------------------------------ | -------------------------------------------------------- |
| `on_command(self, data)`                               | `public override bool? OnCommand(dynamic data)`          |
| `self.add_command(MyCommand)`                          | `AddCommand<MyCommand>()`                                |
| `class MyCommand(Command):` + class-level attributes   | `public class MyCommand : Command { public string Ticker { get; set; } ... }` |
| `def run(self, algorithm):`                            | `public override bool? Run(IAlgorithm algorithm)`        |
| `MyCommand.ALGORITHM = self` (static-reference pattern) | `((MyAlgorithm)algorithm).DoSomething()` (cast pattern — no static needed) |
| `self.link({...})` / `self.link(potential_command)`    | `Link(new { ... })` / `Link(potentialCommand)`           |
| `Extensions.download_data(link)`                       | `link.DownloadData()`                                    |
| `self.broadcast_command({...})`                        | `BroadcastCommand(new { ... })`                          |
| `self.live_mode`                                       | `LiveMode`                                               |
| `self.project_id`                                      | `ProjectId`                                              |
| `data.ticker` / `data["ticker"]`                       | `data.Ticker` (only — see uppercasing note below)        |
| `OrderStatus.FILLED`                                   | `OrderStatus.Filled`                                     |
| `self.settings.seed_initial_prices = True`             | `Settings.SeedInitialPrices = true`                      |
| `self.market_order(s, q, asynchronous=True, tag=...)`  | `MarketOrder(s, q, asynchronous: true, tag: ...)`        |
| `self.notify.email(...)`                               | `Notify.Email(...)`                                      |

C#-only gotchas:

- **LEAN uppercases the first character of every payload key when delivering to C#.** A payload sent as `{"ticker": "AAPL"}` reads as `data.Ticker` in C# but `data.ticker` in Python — same wire format, language-appropriate access. When defining an encapsulated `Command` subclass, name C# properties PascalCase (`Ticker`, `Quantity`) so they match what LEAN injects.
- **`dynamic` defers field-resolution to runtime.** `data.Ticker` compiles even if the field doesn't exist; missing fields throw at runtime. Validate before use.
- **Cast numeric fields explicitly** — `var qty = (decimal)data.Quantity;` — JSON deserializes integers as `int`/`long` and arithmetic mixing types throws.
- **`Link(new { ... })` uses an anonymous object whose property names go on the wire verbatim.** `new { Ticker = "AAPL" }` produces `{"Ticker": "AAPL"}`, which a Python sibling reads as `data.Ticker` (not `data.ticker`). If cross-language siblings need lowercase keys, use `Dictionary<string, object>` with the keys you want.
