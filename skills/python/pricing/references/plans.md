# Plans: tiers, seats, nodes, support, QCC and billing

The rules behind the numbers. Every price comes from
[plan-prices.md](plan-prices.md), which is regenerated from the Pricing page;
this file never repeats a number that can go stale there, except where a rule
depends on it. Sources are the Pricing page (`quantconnect.com/pricing`,
including its catalog JSON and checkout script) and the Documentation pages
listed in [docs.md](docs.md), read 2026-09-22.

## How a bill is built

- **Billing is per organization, not per person.** Every account starts with a
  personal organization on the Free tier. Resources (nodes, storage, data,
  QCC) belong to the organization and are shared by its members.
- **The seat type sets the tier.** Buying Quant Researcher, Team, Trading Firm
  or Institution seats puts the organization on that tier. One seat per member.
  Seat limits: Quant Researcher exactly 1 (no collaboration), Team 2 to 10,
  Trading Firm 2 or more, Institution 5 or more.
- **A plan is the seat plus whatever is added to it.** The four plan cards on
  the Pricing page are *recommended packs*: a seat with a preselected set of
  nodes and a support seat. The customer can add or remove any line under
  Customize Plan > Build Your Own Pack (seats, compute nodes, add-ons, data,
  support). The cheapest paid organization is the Quant Researcher seat on its
  own.
- **A pack's price is the sum of its lines at the minimum seat count.** Every
  seat needs a support seat: the pack's support seat covers one and Bronze
  covers the rest. Check: Institution Pack = 5 seats $480 + 3 x R8-16 $288 +
  4 x B4-12 $192 + 4 x L1-2 $312 + A4-12 $96 + Gold $288 + 4 x Bronze $0 =
  $1,656. `plan-prices.md` prices each pack this way. For other seat counts,
  sum the lines and point the customer at the Pricing page, which shows the
  total before checkout.
- **Free Bronze Support.** When the non-support items cost more than **$40 per
  seat per month** (**$400 per seat per year** on annual billing), the Bronze
  Support seat is free. Below that, Bronze is charged at its list price. This
  is why the Researcher and Team packs list Bronze at no extra cost.
- **Monthly or annual.** Annual billing is 10 x monthly for every plan line
  and pack (two months off); the `Yearly` column of `plan-prices.md` is
  computed that way, not read from the catalog. Some data packages, bulk
  downloads included, are sold yearly only.
- **Coupons** are entered at checkout (+ Add Coupon). There is no public coupon
  list; never promise one.
- **Payment** is by credit card through Stripe; QuantConnect never stores the
  card. There is no other documented payment method. A dataset's Cloud Access
  can also be paid from the organization's QCC balance.
- **Invoices with company address and tax details** (Customize Invoice
  Details) are a Trading Firm and Institution feature. Nothing in the Pricing
  page or the docs states how sales tax or VAT is charged: send tax questions
  to support rather than answering them.
- **Billing delegation** (a member other than the owner manages billing) is a
  Trading Firm and Institution feature.

## What each tier includes

From the Pricing page's comparison table and the Tier Features docs. Node
caps are the number of nodes the organization may subscribe to.

| | Free | Quant Researcher | Team | Trading Firm | Institution |
| --- | --- | --- | --- | --- | --- |
| Backtest / research / live node caps | 1 / 1 / 0 | 2 / 1 / 2 | 10 / 10 / 10 | unlimited | unlimited |
| Projects | 200 | unlimited | unlimited | unlimited | unlimited |
| Files per project / max file size | 25 / 32 KB | 50 / 64 KB | 75 / 128 KB | 100 / 256 KB | 250 / 256 KB |
| Backtest log size / daily log reading | 10 KB / 3 MB | 100 KB / 3 MB | 1 MB / 10 MB | 5 MB / 50 MB | unlimited |
| Orders per backtest | 10K | 10M | unlimited | unlimited | unlimited |
| Free notifications per live algorithm per hour | - | 20 | 60 | 240 | 3,600 |
| Workspace capacity | 500 MB | 2 GB | 4 GB | 20 GB | 50 GB |
| Object Store | none | 50 MB, capped | expandable | expandable | expandable, download with the derived data agreement |
| Second and tick data, API, LEAN CLI | no | yes | yes | yes | yes |
| Live trading | QuantConnect Paper Trading only (one trial deployment) | yes | yes, plus Trading Technologies and the live Futures feed | plus IB Financial Advisor, prime and FIX brokerages | plus Bloomberg EMSX, on-premises LEAN Enterprise |

Notes that customers ask about:

- **Free tier:** one free B-MICRO backtest node (20-second launch delay, 200
  backtests a day), one free R1-4 research node and one free A-MICRO agent node
  (100,000 tokens a month). Free data in the cloud covers every asset class at
  minute to daily resolution. Free cannot buy anything: every add-on (nodes,
  storage, datasets, QCC-funded features) needs a paid organization first. The
  one trial live deployment works only with QuantConnect Paper Trading; any
  external brokerage, even in paper mode, needs a paid live node. Opening extra
  accounts to get more free deployments is against the terms.
- **Out-of-sample holdout:** Free organizations cannot backtest the most recent
  90 days, and cannot remove that holdout; any paid organization's manager can
  set No Holdout Period on the organization homepage.
- **Downgrading to Free deletes data:** backtest results and logs, projects
  over the Free limits, the Object Store data (Free has no Object Store) and
  running live algorithms with their logs. It takes effect at the end of the
  billing cycle. The cheaper alternative the docs recommend is the Quant
  Researcher tier with every subscription except the seat removed. Link
  `cloud-platform/organizations/billing#07-Change-Organization-Tiers`.
- **Pause:** 1 or 2 months from the Downgrade page, keeping everything as it
  is; resuming early gives a pro-rated credit.
- **More than 2 live nodes** means the Team tier (Quant Researcher caps live
  nodes at 2).

## Nodes

- One backtest node runs one backtest at a time: concurrency needs more nodes.
  One live node runs one live algorithm. Research nodes do not speed up
  backtests.
- **Changing a node model** is add the new one and remove the old one in the
  same Customize Plan session; there is no in-place upgrade button.
- **Adding** a node renews the whole subscription period and charges the
  difference between the old and new subscription. **Removing** a node gives a
  pro-rated credit applied to future invoices, not a cash refund. The checkout
  summary shows the new total plus a prorated adjustment, not the delta, which
  is why adding one $24 node can show $48.
- **Live node memory:** each security subscription needs about 5 MB, so the
  512 MB L-MICRO holds on the order of 100 subscriptions; size the node so the
  algorithm averages under 80% of its RAM.
- **GPU nodes** are shared by up to three members (two for live).
- **Dedicated live servers** (the `-D-` models) are leased as a block of 20
  nodes and need a 12-month commitment; the customer contacts us to reserve
  them.
- **Optimization nodes** are not subscriptions: they are rented by time and
  paid in QCC (next section).
- **Agent nodes** run the AI assistants. Paid agent nodes have a fair-use token
  allowance over a weekly rolling window; past it, the customer upgrades the
  agent node or brings their own key (BYOK needs a paid agent node). QCC does
  not pay for AI usage.

## QCC (QuantConnect Credit)

- **1 QCC = $0.01.** 100 QCC = $1. Bought on the Billing page or the Pricing
  page's QCC Tokens tab: $20, $50, $100, $250 or a custom amount above $10.
  Purchases of $100 or more get 10% bonus QCC. Auto-reload can top the balance
  up when it falls below a threshold.
- QCC belongs to the organization; every member can spend it. It is charged to
  the preferred organization's card.
- **What QCC pays for:** parameter optimization (optimization nodes rented by
  time: O2-8 $0.15, O4-12 $0.30, O8-16 $0.60 per node-hour as the LEAN CLI
  docs show them; deducted as each backtest finishes; the estimate can differ from the
  final cost, so hold about 50% more than the estimate), per-file dataset
  downloads with the LEAN CLI, notifications beyond the hourly free allowance
  (1 QCC each for email, Telegram or webhook; SMS 1 QCC in the US and Canada,
  10 QCC elsewhere), and forum gifts.
- **What QCC does not pay for:** seats, nodes, support, storage, AI assistant
  usage, and backtests (backtests never consume QCC).
- **Investor perk:** QuantConnect investors (wefunder.com/quantconnect) get 10%
  back in QCC on annual subscriptions.

## Support seats

Bronze, Silver and Gold support seats buy private email tickets, IP-protected
project attachments and live-trading debugging. They carry no AI tokens (older
notes and the catalog description say they do; that no longer applies). Tickets per rolling month:
Bronze 4 (best-effort response), Silver 8 (48 h), Gold 16 (24 h, plus phone
support). Silver and Gold add algorithm design suggestions. Institution adds
chat support. The ticket quota is an anti-abuse limit: a member with a real
issue who is out of tickets gets more on request.

## Object Store

Paid organizations get 50 MB and 1,000 files free and can subscribe to more
capacity. Capacity never enables downloading: downloading files from the
Object Store is only for permissioned Institution organizations that signed
the derived data agreement, and only derived data; see the data-export
skill. Prices are in `plan-prices.md`.

## Discounts, trials and services

- **No individual student discount.** Professors can arrange access for a
  class in return for marketing initiatives; the professor contacts
  support@quantconnect.com. The Quant Researcher tier is the tier for
  students, academics and self-directed traders.
- **Academic grant:** researchers publishing a paper can apply via the forum
  thread linked from the Pricing page
  (`/forum/discussion/16153/solving-the-replication-crisis-in-finance/p1`).
- **Trials** of a paid dataset, a node or a seat are granted case by case; the
  customer asks support. Never promise one.
- **Onboarding services** (a three-day consultation for a team) and **consulting**
  (a two-day consult with a senior engineer and a quant) start from $4,800;
  the customer emails sales@quantconnect.com.
- **Private Cloud Hosts** (run the QuantConnect stack on the customer's own
  hardware) are licensed per host per year and need a copy of the data; an
  Institution conversation.
- **Institution tier:** the Pricing page invites the customer to contact us for
  a demo.
