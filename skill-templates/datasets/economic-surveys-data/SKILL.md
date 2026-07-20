---
name: economic-surveys-data
description: Use whenever a strategy needs US macroeconomic survey data — CPI/inflation, nonfarm payrolls and earnings (CES), producer prices (PPI), or job openings (JOLTS). Covers the point-in-time BLS Economic Surveys datasets, their release-date semantics, the field tables for each survey, and the non-seasonally-adjusted caveat and how to handle it.
---

# US economic-survey data — point-in-time BLS Economic Surveys

For macro signals built on US economic releases (inflation, employment, producer prices, labor turnover), use the **BLS Economic Surveys** datasets. They are delivered **point-in-time** — each data point is stamped with its actual release date — so a backtest sees a number only once it was really published, exactly as live trading would. This is the property that makes a macro-timing strategy reproducible live; do not substitute a revised/latest-vintage macro series, which would leak data a live strategy could never see.

## The four integrated surveys

Subscribe in py`initialize`cs`Initialize` with py`add_data(<Class>, "<TICKER>")`cs`AddData<Class>("<TICKER>")` and keep the returned symbol. The class lives in `QuantConnect.DataSource`.

| Survey | Class | Ticker | Coverage starts | Released |
|---|---|---|---|---|
| Consumer Price Index | `BLSEconomicSurveysCpi` | `"CPI"` | Jan 2000 | ~monthly, 08:30 ET |
| Current Employment Statistics | `BLSEconomicSurveysCes` | `"CES"` | Jan 2000 | ~monthly, 08:30 ET |
| Producer Price Index | `BLSEconomicSurveysPpi` | `"PPI"` | Jan 2000 | ~monthly, 08:30 ET |
| Job Openings & Labor Turnover | `BLSEconomicSurveysJolts` | `"JOLTS"` | Nov 2007 | ~monthly, 10:00 ET |

```python
self._cpi = self.add_data(BLSEconomicSurveysCpi, "CPI").symbol
```

```csharp
_cpi = AddData<BLSEconomicSurveysCpi>("CPI").Symbol;
```

The **coverage start bounds your backtest** — a CPI strategy cannot start before Jan 2000 on this data, and a signal that needs N years of history to warm up effectively begins that much later. If the backtest start precedes coverage, hold the defensive/cash leg until the data exists rather than idling on empty signals.

## Accessing the data — live and history (verified by probe)

Live, in py`on_data`cs`OnData`, read the named property off the data point and key off py`end_time`cs`EndTime`:
```python
def on_data(self, data):
    if self._cpi in data:
        point = data[self._cpi]
        all_items = point.all_items      # headline CPI index level
        release_date = point.end_time    # the real BLS release date (point-in-time)
```

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

<!-- python-only -->
To seed a signal from history, **prefer the typed (dataset-object) history overload** — it returns the actual data-point objects, so you read the SAME `.all_items` / `.end_time` properties as in `on_data`, with no DataFrame column-name surprises:
<!-- /python-only -->
<!-- csharp-only -->
To seed a signal from history, **prefer the typed (dataset-object) history overload** — it returns the actual data-point objects, so you read the SAME `.AllItems` / `.EndTime` properties as in `OnData`:
<!-- /csharp-only -->
```python
for point in self.history[BLSEconomicSurveysCpi](self._cpi, 2000, Resolution.DAILY):
    level = point.all_items            # same property name as live
    release_date = point.end_time      # real release date
```

```csharp
foreach (var point in History<BLSEconomicSurveysCpi>(_cpi, 2000, Resolution.Daily))
{
    var level = point.AllItems;          // same property name as live
    var releaseDate = point.EndTime;     // real release date
}
```

<!-- python-only -->
The plain DataFrame overload also works, but it **renames the columns** — the property `all_items` becomes column `allitems`, `core_cpi` → `corecpi`, `final_demand` → `finaldemand` (lower-cased, underscores stripped); its index is the release date. Prefer the typed overload above to avoid the rename:
```python
df = self.history(self._cpi, 2000, Resolution.DAILY)   # then row["allitems"], NOT row["all_items"]
```

<!-- /python-only -->
Notes:
- These are **monthly** releases, so a count-based daily history request must be large — a few-day window returns nothing; request enough trading days to span the months you need.
- A history request returns points from before the start date, so seed once at startup rather than idling.
- The value each point carries describes the **prior** month (a CPI point released mid-May is April's CPI). Every named field is an **index level or a count** (not a rate of change) — compute MoM/YoY/etc. yourself.

## Common fields — every survey
These base fields are present on every BLS Economic Surveys data point (CPI, CES, PPI, JOLTS).

| Field | Description |
|---|---|
| py`end_time`cs`EndTime` | The actual BLS **release date** of this observation (point-in-time). Date every observation by this. |
| py`time`cs`Time` | LEAN timestamp — **unreliable** for these datasets; do not use it to date the observation (use py`end_time`cs`EndTime`). |
| py`value`cs`Value` | Generic BaseData value — not a specific named series here; read one of the named fields below instead. |

## CPI fields — `BLSEconomicSurveysCpi`
All series non-seasonally-adjusted, base period 1982-84=100.

| Field | Description |
|---|---|
| py`all_items`cs`AllItems` | Headline All-Items CPI index (series CUUR0000SA0) |
| py`core_cpi`cs`CoreCpi` | All items less food and energy — Core CPI (CUUR0000SA0L1E) |
| py`food`cs`Food` | Food |
| py`food_at_home`cs`FoodAtHome` | Food at home |
| py`food_away_from_home`cs`FoodAwayFromHome` | Food away from home |
| py`energy`cs`Energy` | Energy |
| py`gasoline`cs`Gasoline` | Gasoline (all types) |
| py`shelter`cs`Shelter` | Shelter |
| py`rent_of_primary_residence`cs`RentOfPrimaryResidence` | Rent of primary residence |
| py`medical_care`cs`MedicalCare` | Medical care |
| py`apparel`cs`Apparel` | Apparel |
| py`education_and_communication`cs`EducationAndCommunication` | Education and communication |
| py`new_vehicles`cs`NewVehicles` | New vehicles |
| py`used_cars_and_trucks`cs`UsedCarsAndTrucks` | Used cars and trucks |
| py`college_tuition_and_fees`cs`CollegeTuitionAndFees` | College tuition and fees |

## CES (employment) fields — `BLSEconomicSurveysCes`
All series non-seasonally-adjusted, from the monthly Employment Situation report.

| Field | Description |
|---|---|
| py`total_nonfarm`cs`TotalNonfarm` | Total nonfarm payrolls, all employees (thousands) |
| py`total_private`cs`TotalPrivate` | Total private, all employees (thousands) |
| py`manufacturing`cs`Manufacturing` | Manufacturing, all employees (thousands) |
| py`goods_producing`cs`GoodsProducing` | Goods-producing, all employees (thousands) |
| py`private_service_providing`cs`PrivateServiceProviding` | Private service-providing, all employees (thousands) |
| py`construction`cs`Construction` | Construction, all employees (thousands) |
| py`retail_trade`cs`RetailTrade` | Retail trade, all employees (thousands) |
| py`financial_activities`cs`FinancialActivities` | Financial activities, all employees (thousands) |
| py`education_and_health_services`cs`EducationAndHealthServices` | Education and health services, all employees (thousands) |
| py`leisure_and_hospitality`cs`LeisureAndHospitality` | Leisure and hospitality, all employees (thousands) |
| py`mining_and_logging`cs`MiningAndLogging` | Mining and logging, all employees (thousands) |
| py`average_hourly_earnings`cs`AverageHourlyEarnings` | Average hourly earnings, total private (dollars) |
| py`average_weekly_hours`cs`AverageWeeklyHours` | Average weekly hours, total private (hours) |
| py`average_weekly_earnings`cs`AverageWeeklyEarnings` | Average weekly earnings, total private (dollars) |
| py`production_hourly_earnings`cs`ProductionHourlyEarnings` | Average hourly earnings, production & nonsupervisory (dollars) |
| py`production_employees`cs`ProductionEmployees` | Production & nonsupervisory employees, total private (thousands) |

## PPI fields — `BLSEconomicSurveysPpi`
All series non-seasonally-adjusted. Base date varies by series (noted below).

| Field | Description |
|---|---|
| py`final_demand`cs`FinalDemand` | Headline Final Demand PPI index, base Nov 2009=100 (WPUFD4) |
| py`core_ppi`cs`CorePpi` | Final demand less foods and energy — Core PPI, base Oct 2004=100 |
| py`final_demand_less_food_energy_trade`cs`FinalDemandLessFoodEnergyTrade` | Final demand less foods, energy, and trade services, base Aug 2013=100 |
| py`final_demand_goods`cs`FinalDemandGoods` | Final demand goods, base Nov 2009=100 |
| py`final_demand_services`cs`FinalDemandServices` | Final demand services, base Nov 2009=100 |
| py`final_demand_construction`cs`FinalDemandConstruction` | Final demand construction, base Nov 2009=100 |
| py`final_demand_goods_less_foods`cs`FinalDemandGoodsLessFoods` | Final demand goods less foods |
| py`all_commodities`cs`AllCommodities` | All commodities, base 1982=100 |
| py`farm_products`cs`FarmProducts` | Farm products, base 1982=100 |
| py`processed_foods_and_feeds`cs`ProcessedFoodsAndFeeds` | Processed foods and feeds, base 1982=100 |
| py`crude_petroleum`cs`CrudePetroleum` | Crude petroleum (domestic), base 1982=100 |

## JOLTS fields — `BLSEconomicSurveysJolts`
All series non-seasonally-adjusted, total nonfarm.

| Field | Description |
|---|---|
| py`job_openings`cs`JobOpenings` | Job openings, level (thousands) |
| py`job_openings_rate`cs`JobOpeningsRate` | Job openings rate (percent) |
| py`hires`cs`Hires` | Hires, level (thousands) |
| py`hires_rate`cs`HiresRate` | Hires rate (percent) |
| py`quits`cs`Quits` | Quits, level (thousands) |
| py`quits_rate`cs`QuitsRate` | Quits rate (percent) |
| py`total_separations`cs`TotalSeparations` | Total separations, level (thousands) |
| py`layoffs_and_discharges`cs`LayoffsAndDischarges` | Layoffs and discharges, level (thousands) |

## The series are non-seasonally-adjusted (NSA)

Every series here is **non-seasonally-adjusted (NSA)**. This is a property of the data to be aware of — not something to "fix" by default. **Whether to seasonally adjust is the strategy spec's decision, not this skill's**; use the NSA series as-is unless the spec calls for a seasonally-adjusted signal.

What the NSA property means for a signal:
- **Year-over-year (12-month) change is seasonality-free on NSA** — comparing the same calendar month across years cancels the seasonal pattern: `yoy_t = level_t / level_{t-12} − 1`.
- **Month-over-month change on NSA carries a strong seasonal component** (gasoline up every spring, post-holiday drops every January), mixing the seasonal calendar with the underlying trend.

**Only if the spec calls for a seasonally-adjusted signal**, build it point-in-time from the NSA series (no point-in-time SA series is available, so you must construct it using only past data): for the calendar month `c` of date `t`, subtract the expanding-window mean of that same calendar month's values observed **strictly before `t`** (excluding the current one). Never use a centered/two-sided filter or any data dated at or after `t`.
