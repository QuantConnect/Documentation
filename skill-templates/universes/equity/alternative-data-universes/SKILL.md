---
name: alternative-data-universes
description: Use when selecting a dynamic Equity universe from an alternative-data class in a QuantConnect/LEAN algorithm — calling py`add_universe(<AltClass>, selector)`cs`AddUniverse<AltClass>(selector)` with Brain, CoinGecko, EOD Historical Data, Quiver Quantitative, or Smart Insider universe classes. Triggers — questions like "which class do I pass to add_universe for Brain sentiment / Quiver insider trades / Smart Insider buybacks / EODHD upcoming earnings / CoinGecko market cap", missing-class compile errors on names like `BrainSentimentIndicatorUniverse` or `EODHDUpcomingEarnings`. Skip when — the universe is Morningstar Fundamental (use `fundamental-universes`), ETF constituents (use py`self.universe.etf(...)`cs`Universe.ETF(...)`), or pure indicator-driven selection (use `indicator-universes`).
---

# Available Alternative Data Universes

The snippets below show how to call py`add_universe(<AltClass>, selector)`cs`AddUniverse<AltClass>(selector)` with each alternative-data universe class. Replace py`Universe.UNCHANGED`cs`Universe.Unchanged` with your selection logic.

## Brain Language Metrics on Company Filings

```python
def initialize(self) -> None:
    self._universe = self.add_universe(BrainCompanyFilingLanguageMetricsUniverseAll, self.universe_selection)

def universe_selection(self, alt_coarse: List[BrainCompanyFilingLanguageMetricsUniverseAll]) -> List[Symbol]:
    return Universe.UNCHANGED
```

```csharp
private Universe _universe;

public override void Initialize()
{
    _universe = AddUniverse<BrainCompanyFilingLanguageMetricsUniverseAll>(UniverseSelection);
}

private IEnumerable<Symbol> UniverseSelection(IEnumerable<BrainCompanyFilingLanguageMetricsUniverseAll> altCoarse)
{
    return Universe.Unchanged;
}
```

## Brain ML Stock Ranking

```python
def initialize(self) -> None:
    self._universe = self.add_universe(BrainStockRankingUniverse, self.universe_selection)

def universe_selection(self, alt_coarse: List[BrainStockRankingUniverse]) -> List[Symbol]:
    return Universe.UNCHANGED
```

```csharp
private Universe _universe;

public override void Initialize()
{
    _universe = AddUniverse<BrainStockRankingUniverse>(UniverseSelection);
}

private IEnumerable<Symbol> UniverseSelection(IEnumerable<BrainStockRankingUniverse> altCoarse)
{
    return Universe.Unchanged;
}
```

## Brain Sentiment Indicator

```python
def initialize(self) -> None:
    self._universe = self.add_universe(BrainSentimentIndicatorUniverse, self.universe_selection)

def universe_selection(self, alt_coarse: List[BrainSentimentIndicatorUniverse]) -> List[Symbol]:
    return Universe.UNCHANGED
```

```csharp
private Universe _universe;

public override void Initialize()
{
    _universe = AddUniverse<BrainSentimentIndicatorUniverse>(UniverseSelection);
}

private IEnumerable<Symbol> UniverseSelection(IEnumerable<BrainSentimentIndicatorUniverse> altCoarse)
{
    return Universe.Unchanged;
}
```

## Crypto Market Cap

```python
def initialize(self) -> None:
    self._universe = self.add_universe(CoinGeckoUniverse, self.universe_selection)

def universe_selection(self, alt_coarse: List[CoinGeckoUniverse]) -> List[Symbol]:
    return Universe.UNCHANGED
```

```csharp
private Universe _universe;

public override void Initialize()
{
    _universe = AddUniverse<CoinGeckoUniverse>(UniverseSelection);
}

private IEnumerable<Symbol> UniverseSelection(IEnumerable<CoinGeckoUniverse> altCoarse)
{
    return Universe.Unchanged;
}
```

## Upcoming Dividends

```python
def initialize(self) -> None:
    self._universe = self.add_universe(EODHDUpcomingDividends, self.universe_selection)

def universe_selection(self, alt_coarse: List[EODHDUpcomingDividends]) -> List[Symbol]:
    return Universe.UNCHANGED
```

```csharp
private Universe _universe;

public override void Initialize()
{
    _universe = AddUniverse<EODHDUpcomingDividends>(UniverseSelection);
}

private IEnumerable<Symbol> UniverseSelection(IEnumerable<EODHDUpcomingDividends> altCoarse)
{
    return Universe.Unchanged;
}
```

## Upcoming Earnings

```python
def initialize(self) -> None:
    self._universe = self.add_universe(EODHDUpcomingEarnings, self.universe_selection)

def universe_selection(self, alt_coarse: List[EODHDUpcomingEarnings]) -> List[Symbol]:
    return Universe.UNCHANGED
```

```csharp
private Universe _universe;

public override void Initialize()
{
    _universe = AddUniverse<EODHDUpcomingEarnings>(UniverseSelection);
}

private IEnumerable<Symbol> UniverseSelection(IEnumerable<EODHDUpcomingEarnings> altCoarse)
{
    return Universe.Unchanged;
}
```

## Upcoming IPOs

```python
def initialize(self) -> None:
    self._universe = self.add_universe(EODHDUpcomingIPOs, self.universe_selection)

def universe_selection(self, alt_coarse: List[EODHDUpcomingIPOs]) -> List[Symbol]:
    return Universe.UNCHANGED
```

```csharp
private Universe _universe;

public override void Initialize()
{
    _universe = AddUniverse<EODHDUpcomingIPOs>(UniverseSelection);
}

private IEnumerable<Symbol> UniverseSelection(IEnumerable<EODHDUpcomingIPOs> altCoarse)
{
    return Universe.Unchanged;
}
```

## Upcoming Splits

```python
def initialize(self) -> None:
    self._universe = self.add_universe(EODHDUpcomingSplits, self.universe_selection)

def universe_selection(self, alt_coarse: List[EODHDUpcomingSplits]) -> List[Symbol]:
    return Universe.UNCHANGED
```

```csharp
private Universe _universe;

public override void Initialize()
{
    _universe = AddUniverse<EODHDUpcomingSplits>(UniverseSelection);
}

private IEnumerable<Symbol> UniverseSelection(IEnumerable<EODHDUpcomingSplits> altCoarse)
{
    return Universe.Unchanged;
}
```

## CNBC Trading

```python
def initialize(self) -> None:
    self._universe = self.add_universe(QuiverCNBCsUniverse, self.universe_selection)

def universe_selection(self, alt_coarse: List[QuiverCNBCsUniverse]) -> List[Symbol]:
    return Universe.UNCHANGED
```

```csharp
private Universe _universe;

public override void Initialize()
{
    _universe = AddUniverse<QuiverCNBCsUniverse>(UniverseSelection);
}

private IEnumerable<Symbol> UniverseSelection(IEnumerable<QuiverCNBCsUniverse> altCoarse)
{
    return Universe.Unchanged;
}
```

## Corporate Lobbying

```python
def initialize(self) -> None:
    self._universe = self.add_universe(QuiverLobbyingUniverse, self.universe_selection)

def universe_selection(self, alt_coarse: List[QuiverLobbyingUniverse]) -> List[Symbol]:
    return Universe.UNCHANGED
```

```csharp
private Universe _universe;

public override void Initialize()
{
    _universe = AddUniverse<QuiverLobbyingUniverse>(UniverseSelection);
}

private IEnumerable<Symbol> UniverseSelection(IEnumerable<QuiverLobbyingUniverse> altCoarse)
{
    return Universe.Unchanged;
}
```

## Insider Trading

```python
def initialize(self) -> None:
    self._universe = self.add_universe(QuiverInsiderTradingUniverse, self.universe_selection)

def universe_selection(self, alt_coarse: List[QuiverInsiderTradingUniverse]) -> List[Symbol]:
    return Universe.UNCHANGED
```

```csharp
private Universe _universe;

public override void Initialize()
{
    _universe = AddUniverse<QuiverInsiderTradingUniverse>(UniverseSelection);
}

private IEnumerable<Symbol> UniverseSelection(IEnumerable<QuiverInsiderTradingUniverse> altCoarse)
{
    return Universe.Unchanged;
}
```

## US Congress Trading

```python
def initialize(self) -> None:
    self._universe = self.add_universe(QuiverQuantCongressUniverse, self.universe_selection)

def universe_selection(self, alt_coarse: List[QuiverQuantCongressUniverse]) -> List[Symbol]:
    return Universe.UNCHANGED
```

```csharp
private Universe _universe;

public override void Initialize()
{
    _universe = AddUniverse<QuiverQuantCongressUniverse>(UniverseSelection);
}

private IEnumerable<Symbol> UniverseSelection(IEnumerable<QuiverQuantCongressUniverse> altCoarse)
{
    return Universe.Unchanged;
}
```

## US Government Contracts

```python
def initialize(self) -> None:
    self._universe = self.add_universe(QuiverGovernmentContractUniverse, self.universe_selection)

def universe_selection(self, alt_coarse: List[QuiverGovernmentContractUniverse]) -> List[Symbol]:
    return Universe.UNCHANGED
```

```csharp
private Universe _universe;

public override void Initialize()
{
    _universe = AddUniverse<QuiverGovernmentContractUniverse>(UniverseSelection);
}

private IEnumerable<Symbol> UniverseSelection(IEnumerable<QuiverGovernmentContractUniverse> altCoarse)
{
    return Universe.Unchanged;
}
```

## WallStreetBets

```python
def initialize(self) -> None:
    self._universe = self.add_universe(QuiverWallStreetBetsUniverse, self.universe_selection)

def universe_selection(self, alt_coarse: List[QuiverWallStreetBetsUniverse]) -> List[Symbol]:
    return Universe.UNCHANGED
```

```csharp
private Universe _universe;

public override void Initialize()
{
    _universe = AddUniverse<QuiverWallStreetBetsUniverse>(UniverseSelection);
}

private IEnumerable<Symbol> UniverseSelection(IEnumerable<QuiverWallStreetBetsUniverse> altCoarse)
{
    return Universe.Unchanged;
}
```

## Corporate Buybacks

```python
def initialize(self) -> None:
    self._universe = self.add_universe(SmartInsiderIntentionUniverse, self.universe_selection)

def universe_selection(self, alt_coarse: List[SmartInsiderIntentionUniverse]) -> List[Symbol]:
    return Universe.UNCHANGED
```

```csharp
private Universe _universe;

public override void Initialize()
{
    _universe = AddUniverse<SmartInsiderIntentionUniverse>(UniverseSelection);
}

private IEnumerable<Symbol> UniverseSelection(IEnumerable<SmartInsiderIntentionUniverse> altCoarse)
{
    return Universe.Unchanged;
}
```

```python
def initialize(self) -> None:
    self._universe = self.add_universe(SmartInsiderTransactionUniverse, self.universe_selection)

def universe_selection(self, alt_coarse: List[SmartInsiderTransactionUniverse]) -> List[Symbol]:
    return Universe.UNCHANGED
```

```csharp
private Universe _universe;

public override void Initialize()
{
    _universe = AddUniverse<SmartInsiderTransactionUniverse>(UniverseSelection);
}

private IEnumerable<Symbol> UniverseSelection(IEnumerable<SmartInsiderTransactionUniverse> altCoarse)
{
    return Universe.Unchanged;
}
```
