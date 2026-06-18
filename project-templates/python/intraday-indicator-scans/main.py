# region imports
from AlgorithmImports import *
# endregion

class ETFUniverseAlgorithm(QCAlgorithm):
    _weight_by_symbol: dict[Symbol, float] = {}

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100000)
        self.settings.seed_initial_prices = True
        self.settings.automatic_indicator_warm_up = True
        # Select QQQ constituents first, then by fundamental data.
        self._universe = self.add_universe(self.universe.etf("QQQ", self._etf_constituents_filter))
        self.schedule.on(self.date_rules.every_day("QQQ"), self.time_rules.every(timedelta(minutes=15)), self._place_orders)

    def on_securities_changed(self, changes: SecurityChanges) -> None:
        for security in changes.added_securities:
            security.atr = self.atr(security, 60, resolution=Resolution.MINUTE)
            # Alternatively, use a manual indicator.
            # symbol = security.symbol
            # security.atr = AverageTrueRange(60)
            # self.warm_up_indicator(symbol, security.atr)
            # self.register_indicator(symbol, security.atr)
        for security in changes.removed_securities:
            self.deregister_indicator(security.atr)

    def _etf_constituents_filter(self, constituents: List[ETFConstituentUniverse]) -> List[Symbol]:
        # Select all QQQ constituents.
        self._weight_by_symbol = {c.symbol: c.weight or 0.0 for c in constituents}
        return [c.symbol for c in constituents]

    def _place_orders(self) -> None:
        if not (self.is_market_open('QQQ') and self._universe.selected):
            return
        # Select the stocks with the greatest ATR.
        securities = [self.securities[symbol] for symbol in self._universe.selected]
        selected = sorted([s for s in securities if s.atr.is_ready], key=lambda security: security.atr)[-10:]
        # We will keep the ETF weights by scale it up to sum 1
        sum_of_weight = sum([self._weight_by_symbol[security.symbol] for security in selected])
        self.plot("Universe", "Sum Of Weight (%)", sum_of_weight * 100)
        if not sum_of_weight:
            return
        targets = [PortfolioTarget(s, self._weight_by_symbol[s.symbol] / sum_of_weight) for s in selected]
        # Remove securities that are not top ATR
        self.set_holdings(targets, True)
