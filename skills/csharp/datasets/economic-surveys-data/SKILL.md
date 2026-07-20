---
name: economic-surveys-data
description: Use whenever a strategy needs US macroeconomic survey data — CPI/inflation, nonfarm payrolls and earnings (CES), producer prices (PPI), or job openings (JOLTS). Covers the point-in-time BLS Economic Surveys datasets, their release-date semantics, the field tables for each survey, and the non-seasonally-adjusted caveat and how to handle it.
---

# US economic-survey data — point-in-time BLS Economic Surveys

For macro signals built on US economic releases (inflation, employment, producer prices, labor turnover), use the **BLS Economic Surveys** datasets. They are delivered **point-in-time** — each data point is stamped with its actual release date — so a backtest sees a number only once it was really published, exactly as live trading would. This is the property that makes a macro-timing strategy reproducible live; do not substitute a revised/latest-vintage macro series, which would leak data a live strategy could never see.

## The four integrated surveys

Subscribe in `Initialize` with `AddData<Class>("<TICKER>")` and keep the returned symbol. The class lives in `QuantConnect.DataSource`.

| Survey | Class | Ticker | Coverage starts | Released |
|---|---|---|---|---|
| Consumer Price Index | `BLSEconomicSurveysCpi` | `"CPI"` | Jan 2000 | ~monthly, 08:30 ET |
| Current Employment Statistics | `BLSEconomicSurveysCes` | `"CES"` | Jan 2000 | ~monthly, 08:30 ET |
| Producer Price Index | `BLSEconomicSurveysPpi` | `"PPI"` | Jan 2000 | ~monthly, 08:30 ET |
| Job Openings & Labor Turnover | `BLSEconomicSurveysJolts` | `"JOLTS"` | Nov 2007 | ~monthly, 10:00 ET |

```csharp
_cpi = AddData<BLSEconomicSurveysCpi>("CPI").Symbol;
```

The **coverage start bounds your backtest** — a CPI strategy cannot start before Jan 2000 on this data, and a signal that needs N years of history to warm up effectively begins that much later. If the backtest start precedes coverage, hold the defensive/cash leg until the data exists rather than idling on empty signals.

## Accessing the data — live and history (verified by probe)

Live, in `OnData`, read the named property off the data point and key off `EndTime`:
```csharp
public override void OnData(Slice slice)
{
    if (slice.ContainsKey(_cpi))
    {
        var point = slice[_cpi];
        var allItems = point.AllItems;       // headline CPI index level
        var releaseDate = point.EndTime;     // the real BLS release date (point-in-time)
    }
}
```

To seed a signal from history, **prefer the typed (dataset-object) history overload** — it returns the actual data-point objects, so you read the SAME `.AllItems` / `.EndTime` properties as in `OnData`:
```csharp
foreach (var point in History<BLSEconomicSurveysCpi>(_cpi, 2000, Resolution.Daily))
{
    var level = point.AllItems;          // same property name as live
    var releaseDate = point.EndTime;     // real release date
}
```

Notes:
- These are **monthly** releases, so a count-based daily history request must be large — a few-day window returns nothing; request enough trading days to span the months you need.
- A history request returns points from before the start date, so seed once at startup rather than idling.
- The value each point carries describes the **prior** month (a CPI point released mid-May is April's CPI). Every named field is an **index level or a count** (not a rate of change) — compute MoM/YoY/etc. yourself.

## Common fields — every survey
These base fields are present on every BLS Economic Surveys data point (CPI, CES, PPI, JOLTS).

| Field | Description |
|---|---|
| `EndTime` | The actual BLS **release date** of this observation (point-in-time). Date every observation by this. |
| `Time` | LEAN timestamp — **unreliable** for these datasets; do not use it to date the observation (use `EndTime`). |
| `Value` | Generic BaseData value — not a specific named series here; read one of the named fields below instead. |

## CPI fields — `BLSEconomicSurveysCpi`
All series non-seasonally-adjusted, base period 1982-84=100.

| Field | Description |
|---|---|
| `AllItems` | Headline All-Items CPI index (series CUUR0000SA0) |
| `CoreCpi` | All items less food and energy — Core CPI (CUUR0000SA0L1E) |
| `Food` | Food |
| `FoodAtHome` | Food at home |
| `FoodAwayFromHome` | Food away from home |
| `Energy` | Energy |
| `Gasoline` | Gasoline (all types) |
| `Shelter` | Shelter |
| `RentOfPrimaryResidence` | Rent of primary residence |
| `MedicalCare` | Medical care |
| `Apparel` | Apparel |
| `EducationAndCommunication` | Education and communication |
| `NewVehicles` | New vehicles |
| `UsedCarsAndTrucks` | Used cars and trucks |
| `CollegeTuitionAndFees` | College tuition and fees |

## CES (employment) fields — `BLSEconomicSurveysCes`
All series non-seasonally-adjusted, from the monthly Employment Situation report.

| Field | Description |
|---|---|
| `TotalNonfarm` | Total nonfarm payrolls, all employees (thousands) |
| `TotalPrivate` | Total private, all employees (thousands) |
| `Manufacturing` | Manufacturing, all employees (thousands) |
| `GoodsProducing` | Goods-producing, all employees (thousands) |
| `PrivateServiceProviding` | Private service-providing, all employees (thousands) |
| `Construction` | Construction, all employees (thousands) |
| `RetailTrade` | Retail trade, all employees (thousands) |
| `FinancialActivities` | Financial activities, all employees (thousands) |
| `EducationAndHealthServices` | Education and health services, all employees (thousands) |
| `LeisureAndHospitality` | Leisure and hospitality, all employees (thousands) |
| `MiningAndLogging` | Mining and logging, all employees (thousands) |
| `AverageHourlyEarnings` | Average hourly earnings, total private (dollars) |
| `AverageWeeklyHours` | Average weekly hours, total private (hours) |
| `AverageWeeklyEarnings` | Average weekly earnings, total private (dollars) |
| `ProductionHourlyEarnings` | Average hourly earnings, production & nonsupervisory (dollars) |
| `ProductionEmployees` | Production & nonsupervisory employees, total private (thousands) |

## PPI fields — `BLSEconomicSurveysPpi`
All series non-seasonally-adjusted. Base date varies by series (noted below).

| Field | Description |
|---|---|
| `FinalDemand` | Headline Final Demand PPI index, base Nov 2009=100 (WPUFD4) |
| `CorePpi` | Final demand less foods and energy — Core PPI, base Oct 2004=100 |
| `FinalDemandLessFoodEnergyTrade` | Final demand less foods, energy, and trade services, base Aug 2013=100 |
| `FinalDemandGoods` | Final demand goods, base Nov 2009=100 |
| `FinalDemandServices` | Final demand services, base Nov 2009=100 |
| `FinalDemandConstruction` | Final demand construction, base Nov 2009=100 |
| `FinalDemandGoodsLessFoods` | Final demand goods less foods |
| `AllCommodities` | All commodities, base 1982=100 |
| `FarmProducts` | Farm products, base 1982=100 |
| `ProcessedFoodsAndFeeds` | Processed foods and feeds, base 1982=100 |
| `CrudePetroleum` | Crude petroleum (domestic), base 1982=100 |

## JOLTS fields — `BLSEconomicSurveysJolts`
All series non-seasonally-adjusted, total nonfarm.

| Field | Description |
|---|---|
| `JobOpenings` | Job openings, level (thousands) |
| `JobOpeningsRate` | Job openings rate (percent) |
| `Hires` | Hires, level (thousands) |
| `HiresRate` | Hires rate (percent) |
| `Quits` | Quits, level (thousands) |
| `QuitsRate` | Quits rate (percent) |
| `TotalSeparations` | Total separations, level (thousands) |
| `LayoffsAndDischarges` | Layoffs and discharges, level (thousands) |

## The series are non-seasonally-adjusted (NSA)

Every series here is **non-seasonally-adjusted (NSA)**. This is a property of the data to be aware of — not something to "fix" by default. **Whether to seasonally adjust is the strategy spec's decision, not this skill's**; use the NSA series as-is unless the spec calls for a seasonally-adjusted signal.

What the NSA property means for a signal:
- **Year-over-year (12-month) change is seasonality-free on NSA** — comparing the same calendar month across years cancels the seasonal pattern: `yoy_t = level_t / level_{t-12} − 1`.
- **Month-over-month change on NSA carries a strong seasonal component** (gasoline up every spring, post-holiday drops every January), mixing the seasonal calendar with the underlying trend.

**Only if the spec calls for a seasonally-adjusted signal**, build it point-in-time from the NSA series (no point-in-time SA series is available, so you must construct it using only past data): for the calendar month `c` of date `t`, subtract the expanding-window mean of that same calendar month's values observed **strictly before `t`** (excluding the current one). Never use a centered/two-sided filter or any data dated at or after `t`.
