# Datasets: what is free, what is bought, and how to estimate a download

Numbers come from [price-book.md](price-book.md) (every dataset, Quant
Researcher list prices, per-file QCC) and [plan-prices.md](plan-prices.md)
(the packages sold through the plan checkout, priced for every tier). Rules
come from the Dataset Licensing and LEAN CLI dataset docs listed in
[docs.md](docs.md), read 2026-09-22.

## Three things customers call "buying data"

| They want | What it is | What it costs |
| --- | --- | --- |
| Use market data in cloud backtests, research, live trading | Included | Nothing. Most price data (US equities, options, futures, index options, forex, CFD, crypto) and many alternative datasets are free in QuantConnect Cloud on every tier, Free included. |
| Use a paid alternative dataset in the cloud | **Cloud Access** licence | A monthly or yearly subscription on the organization's bill (for example Benzinga, ExtractAlpha, Brain, Quiver, Smart Insider, Kavout, RegAlytics). Needs a paid organization. One licence covers every member. |
| Run LEAN on their own machine with our data | **Download** licence via the LEAN CLI | Per file in QCC ("by ticker") or in bulk (billed annually: History the first year, then Updates). Needs a paid organization. |

Lead with the first row whenever a customer is about to buy something to
backtest in the cloud: they do not need it. A price on a dataset page is for
Cloud Access of a paid dataset or for downloading, never for cloud use of free
data.

## Rules that apply to every purchase

- **Dataset purchases are not refundable** (stated at the Pricing page
  checkout). Tell the customer to ask before buying when fit or coverage is in
  doubt. Never offer or promise a credit for a mistaken purchase.
- **The organization's tier sets the price.** Quant Researcher is the lowest;
  Team, Trading Firm and Institution pay more on many packages (US Equity
  minute history is $11,760 for Quant Researcher, $31,200 for Trading Firm).
  Quote the customer's own tier, or all tiers when it is unknown. A Free
  organization is quoted the Quant Researcher price but must upgrade first.
- **Per-file downloads are QCC:** 1 QCC = $0.01, the same on every tier. The
  CLI prints the file count and QCC cost and asks for confirmation before it
  charges; it skips files already on disk.
- **Download licence terms:** for the licensed organization's internal LEAN use
  only, not redistributed or converted into another format; charts may be
  shared if the data cannot be reconstructed from them. Terms beyond that are
  the CLI data agreement: point to it, do not interpret it.
- **Downloading a dataset is not exporting from the cloud.** Buying a Download
  licence is how data legitimately reaches a local machine. Getting cloud data
  or anything derived from it out of QuantConnect (Object Store download,
  logs, results as an export channel) is a different question with a flat
  answer: it is not possible, and there is no exception.
- **Not every dataset is downloadable.** Only SKUs listed as downloads in the
  price book are for sale. Morningstar US Fundamentals and most free-in-cloud
  datasets (crypto price data, FRED, EODHD, CoinGecko, ...) have Cloud Access
  only. "Request a quote" or "contact us" SKUs (US Future Options): the
  customer contacts us.
- **Cancel a dataset subscription** on the organization's Pricing page
  (Customize Plan > Build Your Own Pack > Data, click Added, then checkout);
  access continues to the end of the paid term.
- **Trials** of a paid dataset are granted case by case by a human; vendors
  can also grant them. Never promise one.
- **A dataset we do not carry:** data onboarding is a service where the
  customer pays the cost of the data plus updates and the data then serves the
  whole community in the cloud; it is never delivered to the customer. The
  customer contacts us for a quote.

## Downloading: bulk or by ticker

Needs a paid organization (Quant Researcher or above), the LEAN CLI
(`lean data download`), and billing permissions in the organization.

- **By ticker (or by date):** pay QCC per file for only the tickers and dates
  needed. Cheapest for a handful of tickers; for a whole dataset it usually
  costs more than bulk at the highest tier's price.
- **Bulk:** always billed annually, at two prices. The History package is the
  first year: the full dataset plus a year of daily updates. Every year after,
  the cheaper Updates package keeps it current. Bulk includes every data type. The History
  package must be bought before its Updates package.
- **Data types:** tick, second and minute data come as separate trade and quote
  files (options add open interest). To reproduce cloud results, download
  every type the dataset provides; a backtest-only customer may choose trade
  only, which is the low end of an estimate.

### Prerequisites (each is its own purchase)

| To download | Also needs |
| --- | --- |
| US Equities | US Equity Security Master (yearly) |
| US Equity Options | US Equity Security Master, US Equity Option Universe, the underlying US Equities |
| US Futures | US Futures Security Master (yearly), US Future Universe |
| US Future Options | Contact only; also Future Option Universe, Futures Security Master, Future Universe, US Futures |
| US Index Options | US Index Option Universe |
| US Equity Coarse Universe, US ETF Constituents | US Equity Security Master, plus US Equities for the selected tickers |
| Forex, CFD | nothing |

**A Security Master is metadata, not prices:** splits, dividends and symbol
changes (US Equity) or the data to build continuous contracts (US Futures).
Buying it alone gives no OHLCV. Customers often buy it expecting prices: say so
before they buy.

### What a file is

The QCC price of every file is the **Per-file download prices** table at the end
of `price-book.md`, generated from the rules `lean data download` itself
charges by. This table only says what one file covers, which the price table
does not (from the LEAN CLI price pages):

| Dataset | One file is |
| --- | --- |
| US Equities tick, second, minute | one security, one day, one type (trade or quote) |
| US Equities hour, daily | one security, whole history |
| US Equity Option Universe, US Index Option Universe | one underlying, one day |
| US Equity Options, US Index Options minute | one ticker (the whole chain), one day, one type (trade, quote or open interest) |
| US Equity Options, US Index Options hour, daily | one ticker, one type (equity options: one year) |
| US Future Universe | one future, one day |
| US Futures tick, second, minute | one ticker, one day, one type |
| US Futures hour, daily | one ticker, one type |
| US Equity Coarse Universe | one day |
| US ETF Constituents | one ETF, one day |
| Forex, CFD | one pair or contract, one day (hour and daily: whole history) |

### Estimating a by-ticker download

1. List the datasets: the one asked for plus its prerequisites.
2. Count trading days in the window (about 252 a year).
3. Files = tickers x days x data types (for per-day files); whole-history
   files are one per ticker (per type).
4. QCC = files x QCC per file (from `price-book.md`); dollars = QCC / 100. Add the Security Master's
   yearly price where it is a prerequisite.
5. Give a range when data types are optional: trade only (low) to every type
   (complete), and the ongoing daily cost to stay current.

Worked example, from the docs: one year of SPY minute data = 1 x 252 x 2 =
504 files x 5 QCC = 2,520 QCC = **$25.20**, plus the US Equity Security Master
($600 a year on Quant Researcher). Staying current costs 2 files x 5 QCC =
**$0.10 a day**.

**Option chains:** one ticker's whole chain is one file per day per type, not
one per contract, so a year of an index's options is hundreds of dollars, not
tens of thousands. Docs example: one year of SPX index options at minute
resolution with all three types = universe 252 x 100 QCC ($252) + options
756 x 15 QCC ($113.40) = **$365.40**, or $289.80 trade only (252 x 15 QCC = $37.80). SPX weeklies and
0DTE trade as SPXW; if the customer passes separate tickers to the CLI, each
is counted, so treat the estimate as a floor and say the CLI prints the exact
cost before charging.

### Estimating a bulk download

1. List the datasets: the one asked for plus its prerequisites.
2. First year = each dataset's History price for the resolution + each yearly
   prerequisite (Security Master), from the tier's column in `plan-prices.md`.
3. Every year after = each dataset's Updates price for the resolution + each
   yearly prerequisite.

Worked example, from the docs: US Equities minute on Quant Researcher =
Security Master $600 + minute history $11,760 = **$12,360** the first year,
then $600 + updates $600 = **$1,200 a year**. On Trading Firm: $1,200 +
$31,200 = $32,400, then $1,200 + $1,440 = $2,640 a year.

### Bulk totals (Quant Researcher, from the LEAN CLI price pages)

| Local setup | First year | Then per year |
| --- | --- | --- |
| US Equities minute (with Security Master) | $12,360 | $1,200 |
| US Equities minute + Coarse Universe | $12,960 | $1,440 |
| US Equities minute + ETF Constituents | $15,960 | $2,400 |
| US Equity Options minute (with Security Master, Option Universe, US Equities minute) | $46,320 | $3,600 |
| US Futures minute (with Futures Security Master, Future Universe) | $31,800 | $2,760 |
| US Index Options minute (with Index Option Universe) | $31,200 | $2,160 |
| Forex or CFD, any resolution | $800 | $200 |

## Live data from a brokerage or third party

Not ours to price. Interactive Brokers market data is bought from IB; Tradier,
Alpaca, Charles Schwab and personal tastytrade accounts provide data at no
extra charge from us; Bloomberg B-PIPE, Polygon, Databento, TradeStation and
others bill through the vendor. Link the brokerage's page under
`cloud-platform/datasets/<brokerage>` for their pricing section.

## What we do not sell

- **Level 3 / full depth of book:** not on any plan or dataset. We have trades
  (Level 1) and top of book (Level 2) down to tick resolution.
- **Custom data builds or integrations:** say what is supported and stop.
