# region imports
from AlgorithmImports import *
# endregion


class QuiverInsiderTradingUniverseAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100_000)
        self.settings.seed_initial_prices = True
        self.universe_settings.resolution = Resolution.MINUTE
        # Universe of the 10 US Equities with the largest insider-trading dollar volume.
        self._universe = self.add_universe(QuiverInsiderTradingUniverse, self._select_assets)
        # Rebalance before market open to trade today's universe.
        self.schedule.on(
            self.date_rules.every_day("SPY"),
            self.time_rules.at(9, 0),
            self._rebalance
        )

    def _select_assets(self, data: List[QuiverInsiderTradingUniverse]) -> List[Symbol]:
        # Aggregate insider dollar volume per ticker and keep the 10 largest.
        dollar_volume: dict[Symbol, float] = {}
        for d in data:
            if not d.price_per_share:
                continue
            dollar_volume[d.symbol] = dollar_volume.get(d.symbol, 0) + (d.shares or 0) * d.price_per_share
        return [s for s, _ in sorted(dollar_volume.items(), key=lambda kv: kv[1])[-10:]]

    def _rebalance(self) -> None:
        if not self._universe.selected:
            return
        weight = 1 / len(self._universe.selected)
        targets = [PortfolioTarget(symbol, weight) for symbol in self._universe.selected]
        self.set_holdings(targets, True)
