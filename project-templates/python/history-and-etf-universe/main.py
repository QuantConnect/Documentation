# region imports
from AlgorithmImports import *
# endregion


class ChainedUniverseAlgorithm(QCAlgorithm):
    _weight_by_symbol: dict[Symbol, float] = {}

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100_000)
        self.settings.seed_initial_prices = True
        # Select QQQ constituents first, then by fundamental data.
        self._universe = self.add_universe(self.universe.etf("QQQ", self._etf_constituents_filter))
        self.schedule.on(
            self.date_rules.every_day("QQQ"),
            self.time_rules.before_market_open("QQQ", 30),
            self._place_orders
        )

    def _etf_constituents_filter(self, constituents: List[ETFConstituentUniverse]) -> List[Symbol]:
        def get_atr(symbol: Symbol) -> float:
            atr = AverageTrueRange(14)
            self.warm_up_indicator(symbol, atr, Resolution.DAILY)
            return atr.current.value
        # Select all QQQ constituents by high ATR value.
        atr_by_symbol = {c.symbol: get_atr(c.symbol) for c in constituents if c.weight}
        atr_by_symbol = dict(sorted(atr_by_symbol.items(), key=lambda x: x[1], reverse=True)[:10])
        self._weight_by_symbol = {c.symbol: c.weight for c in constituents if c.symbol in atr_by_symbol}
        return list(atr_by_symbol.keys())

    def _place_orders(self) -> None:
        # We will keep the ETF weights by scale it up to sum 1.
        sum_of_weight = sum([self._weight_by_symbol[x] for x in self._universe.selected])
        self.plot("Universe", "Sum Of Weight (%)", sum_of_weight * 100)
        targets = [PortfolioTarget(x, self._weight_by_symbol[x] / sum_of_weight) for x in self._universe.selected]
        self.set_holdings(targets, True)
