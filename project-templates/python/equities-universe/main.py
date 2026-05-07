# region imports
from AlgorithmImports import *
# endregion

class EmaCrossUniverseSelectionAlgorithm(QCAlgorithm):
    _averages: dict[Symbol, 'SelectionData'] = {}
    _count = 10
    _tolerance = 0.01
    _target_percent = 0.09
    
    def initialize(self) -> None:
        self.set_start_date(2021, 1, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100000)
        self.settings.seed_initial_prices = True
        self.universe_settings.leverage = 2
        self.universe_settings.resolution = Resolution.DAILY

        def _filter(fundamentals: List[Fundamental]) -> List[Symbol]:
            selected = {}
            for f in fundamentals:
                # grab the SelectionData instance for this symbol
                self._averages[f.symbol] = avg = self._averages.get(f.symbol, SelectionData())
                # Update returns true when the indicators are ready, so don't accept until they are
                # and only pick symbols who have their market cap above 1Bi and 100 day ema over their 300 day ema
                if (avg.update(f.end_time, f.adjusted_price) and
                    f.market_cap > 10_000_000_000 and
                    avg.fast.current.value > avg.slow.current.value * (1 + self._tolerance)):
                    selected[f.symbol] = avg
            if len(selected) > self._count:
                #  Prefer symbols with a larger delta by percentage between the two averages
                selected = dict(sorted(selected.items(), key=lambda x: x[1].scaled_delta(), reverse=True)[:self._count])
            return list(selected.keys())

        self._universe = self.add_universe(_filter)
        self.set_warm_up(400, Resolution.DAILY)

    def on_data(self, data: Slice) -> None:
        # we'll simply go long each security in the universe
        targets = [PortfolioTarget(x, self._target_percent) 
            for x in self._universe.selected if self.securities[x].has_data]
        self.set_holdings(targets, True)
        

# class used to improve readability of the fundamental selection function
class SelectionData:
    def __init__(self) -> None:
        self.fast = ExponentialMovingAverage(100)
        self.slow = ExponentialMovingAverage(300)
        
    # Updates the EMA100 and EMA300 indicators, returning true when they're both ready
    def update(self, time: datetime, value: float) -> bool:
        return self.fast.update(time, value) & self.slow.update(time, value)

    def scaled_delta(self) -> float:
        fast, slow = self.fast.current.value, self.slow.current.value
        return (fast-slow)/((fast + slow)/2)
