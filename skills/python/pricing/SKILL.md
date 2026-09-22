---
name: pricing
description: >-
  Use when someone asks what QuantConnect costs or which purchase they need:
  plan tiers and seats (Free, Quant Researcher, Team, Trading Firm,
  Institution), the recommended packs, backtesting / research / live trading /
  agent node prices, support seats, Object Store capacity, QCC credit, monthly
  vs annual billing, adding or removing nodes mid-cycle, and dataset purchases:
  what is free in the cloud, Cloud Access subscriptions to alternative data,
  and downloading data for local LEAN with `lean data download` (by ticker in
  QCC or in bulk, prerequisites such as the Security Masters, cost estimates).
  Triggers: "how much is", "price of", "cheapest plan for", "do I need to buy
  data to backtest", "cost to download a year of SPY minute data". Skip for
  refunds, invoices, cancellations and account changes, which support handles.
---

# Pricing

Answer with the real list price, for the right tier, plus the one rule that
makes the number make sense.

| File | Holds |
| --- | --- |
| [references/plan-prices.md](references/plan-prices.md) | Every seat, pack, node, support seat, storage plan and module; data packages sold at checkout, priced for all four tiers. Generated from quantconnect.com/pricing. |
| [references/price-book.md](references/price-book.md) | Every dataset's SKUs (Cloud Access, bulk packages) and the per-file QCC prices `lean data download` charges, priced for all four tiers. Generated from the API. |
| [references/plans.md](references/plans.md) | How a bill is built, what each tier includes, nodes, QCC, support seats, Object Store, discounts. |
| [references/datasets.md](references/datasets.md) | Free vs Cloud Access vs Download, prerequisites, what one file covers, how to estimate a download, bulk totals. |
| [references/docs.md](references/docs.md) | Pages to link. |

## Answer

- **Know the tier.** Data packages cost more on higher tiers, node caps and
  features differ, and a Free organization must upgrade before it can buy
  anything. Use the tier the person has; when it is unknown, quote the Quant
  Researcher price and say it is the lowest tier's, or show all tiers when the
  difference matters.
- **The number, its unit and its terms**: "$14 a month, or $144 a year billed
  annually", "5 QCC ($0.05) per file", "$11,760 for the first year of
  history, then $600 a year for updates".
- **Cloud first.** Market data is free in the cloud on every tier. Someone
  about to pay for data to backtest in the cloud does not need to; say so
  before any download price.
- **Show the arithmetic of an estimate** in one line (files x QCC per file),
  with a range where data types are optional (trade only to all types), the
  prerequisite subscriptions and the ongoing cost. The CLI prints the exact
  cost before it charges.
- **The cheapest way to do X** is the smallest set of lines that does it. Live
  trading through an external brokerage: Quant Researcher seat $10 + L-MICRO
  $24 = $34 a month.
- **Dataset purchases are not refundable**: say so when someone is about to
  buy data.
- **Link** the page with more detail from `docs.md`; a complete answer needs
  none.
- The generated files carry a `Generated` date. When it is old, or the person
  quotes a different price, check quantconnect.com/pricing or the dataset's
  pricing tab.

## Don't

- Promise a discount, coupon, trial, refund, credit or custom price. Point to
  support.
- Answer tax or VAT questions, or interpret the data licence beyond its
  documented terms.
- Say support seats or QCC pay for AI usage. Neither does.
- Offer a way to get cloud data out of the platform. A Download licence is the
  only route to local data.
