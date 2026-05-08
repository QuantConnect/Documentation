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
        self._universe = self.add_universe(
            self.universe.etf("QQQ", Market.USA, self.universe_settings, self._etf_constituents_filter),
            self._fundamental_selection
        )
        self.schedule.on(
            self.date_rules.every_day("QQQ"),
            self.time_rules.before_market_open("QQQ", 30),
            self._place_orders
        )

    def _etf_constituents_filter(self, constituents: List[ETFConstituentUniverse]) -> List[Symbol]:
        # Select all QQQ constituents.
        self._weight_by_symbol = {c.symbol: c.weight or 0.0 for c in constituents}
        return [c.symbol for c in constituents]

    def _fundamental_selection(self, fundamental: List[Fundamental]) -> List[Symbol]:
        # Filter for the lowest PE Ratio stocks among QQQ constituents for undervalued stocks.
        filtered = [f for f in fundamental if not np.isnan(f.valuation_ratios.pe_ratio)]
        sorted_by_pe_ratio = sorted(filtered, key=lambda f: f.valuation_ratios.pe_ratio)
        return [f.symbol for f in sorted_by_pe_ratio[:10]]

    def _place_orders(self) -> None:
        if not self._universe.selected:
            return
        sum_of_weight = sum([self._weight_by_symbol[x] for x in self._universe.selected])
        self.plot("Universe", "Sum Of Weight (%)", sum_of_weight * 100)
        targets = [PortfolioTarget(x, self._weight_by_symbol[x] / sum_of_weight) for x in self._universe.selected]
        self.set_holdings(targets, True)
