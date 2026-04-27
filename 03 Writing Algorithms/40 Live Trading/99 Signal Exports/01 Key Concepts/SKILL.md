---
name: custom-signal-export
description: Use when implementing a **custom** `ISignalExportTarget` in a QuantConnect/LEAN algorithm — a class with `send(parameters) -> bool` and `dispose()` registered via `signal_export.add_signal_export_provider`. The class itself is trivial; the real work is shaping the outgoing payload to match what the receiving endpoint expects, which is not something QC defines. Skip this skill (use the bundled provider) if the destination is Collective2, Numerai, or vBase.
---

# Custom Signal Exports in QuantConnect / LEAN

A custom signal export is a class that implements `ISignalExportTarget` and forwards `PortfolioTarget` objects to an external endpoint. QuantConnect ships providers for Collective2, Numerai, and vBase — only write a custom provider when the destination is your own fund or a service without a built-in.

## The whole interface

```python
class CustomSignalExport:
    def send(self, parameters: SignalExportTargetParameters) -> bool: ...
    def dispose(self) -> None: ...
```

Register in `initialize`:

```python
self.signal_export.add_signal_export_provider(CustomSignalExport(...))
```

`parameters.algorithm` is the algorithm; `parameters.targets` is the list of `PortfolioTarget`s the engine wants you to send.

## The only real question: what payload does the receiver expect?

This is the gotcha. QuantConnect does not define the wire format — the receiving service does. Before writing the class, get from the user (or the receiver's API docs):

1. **Endpoint URL and auth.** Bearer token, API key header, HMAC body? Lift to module-level constants or constructor args, not hard-coded inside `send`.
2. **Field names and types.** `{symbol, quantity}`? `{ticker, weight}`? `{asset, side, size}`? The receiver's contract drives every line of the body-build code.
3. **Per-target or per-batch?** Most APIs accept a list — `send` is called once per aggregated batch of fills, so build one request, not N.
4. **Quantity in shares or in weight?** `parameters.targets[i].quantity` is a **portfolio weight** (a fraction like `0.10`), not a share count. If the receiver wants share counts, round-trip via `PortfolioTarget.Percent`:

   ```python
   share_target = PortfolioTarget.percent(parameters.algorithm, x.symbol, x.quantity, x.tag)
   # share_target.quantity is now the share count for that weight.
   ```

   Pass `x.tag` through — it's the only carrier of caller-provided context (signal id, regime, confidence) and is empty unless the calling code created targets with `PortfolioTarget(symbol, weight, tag="...")`.

## Reference shape

```python
from requests import Session

class CustomSignalExport:
    def __init__(self, endpoint: str, api_key: str) -> None:
        self._endpoint = endpoint
        self._session = Session()
        self._session.headers["Authorization"] = f"Bearer {api_key}"

    def send(self, parameters: SignalExportTargetParameters) -> bool:
        # Shape this body to what the receiver expects.
        payload = [{"symbol": x.symbol.value, "weight": x.quantity, "tag": x.tag}
                   for x in parameters.targets]
        try:
            response = self._session.post(self._endpoint, json=payload, timeout=30)
        except Exception as e:
            parameters.algorithm.error(f"Signal export failed: {e}")
            return False
        return response.ok

    def dispose(self) -> None:
        self._session.close()
```

C# is the same shape with `public bool Send(SignalExportTargetParameters parameters)` / `public void Dispose()`, a `private readonly HttpClient _httpClient = new();` field, and `_httpClient.PostAsync(url, content).Result` in `Send`.

## Three things that bite

- **Reuse one HTTP client.** Allocate `Session` / `HttpClient` once in the constructor, close in `dispose`. Building a fresh client per `send` exhausts ephemeral ports under load.
- **Return a `bool`, don't raise.** Catch network and JSON errors and return `False`. An exception escaping `send` aborts the algorithm.
- **`send` fires in backtests too.** Unlike notifications, signal exports run anywhere the algorithm runs. If the endpoint is production, either skip `add_signal_export_provider` when `not self.live_mode`, or short-circuit at the top of `send` with `if not parameters.algorithm.live_mode: return True`.
