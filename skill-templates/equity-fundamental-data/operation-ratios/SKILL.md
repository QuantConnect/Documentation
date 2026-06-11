---
name: fundamental-data-point-attributes-operation-ratios
description: Use when you need the exact attribute name or meaning of an operation-ratio field on a QuantConnect/LEAN `Fundamental` object — gross/operating/net/EBITDA margins, ROE, ROA, ROIC, asset/inventory/receivable turnover, current/quick/cash ratios, leverage, and growth rates, plus the rest of py`f.operation_ratios.*`cs`f.OperationRatios.*`. Triggers — "what margin/turnover/liquidity fields exist", "path to ROE / net margin / current ratio", a missing-attribute error on an operation-ratio path. Skip when — you need valuation ratios, earnings-growth ratios, or statement-level fields (see the sibling fundamental-data-point-attributes-* skills).
---

# Operation Ratios attributes — QuantConnect / LEAN `Fundamental`

Reach these off a `Fundamental` snapshot `f` as py`f.operation_ratios.<attribute>`cs`f.OperationRatios.<Attribute>` — for example py`f.operation_ratios.roe.value`cs`f.OperationRatios.ROE.Value`. Get `f` from an py`add_universe(...)`cs`AddUniverse(...)` selection callback, from py`self.securities["SPY"].fundamentals`cs`Securities["SPY"].Fundamentals`, or from a history request.

Every attribute below is a `MultiPeriodField` — append a period accessor to read the number: py`.value`cs`.Value` for the most recent reported period (the usual choice for a ratio), or a longer window such as py`.twelve_months`cs`.TwelveMonths` where you want a smoother figure. Forgetting the accessor is silent — the wrapper compares as truthy and numeric inequalities give nonsense. For how to choose accessors and navigate the rest of the tree, see the **fundamental-universes** skill.

<!-- fundamental-attributes: OperationRatios -->
