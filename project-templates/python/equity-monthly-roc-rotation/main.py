# region imports
from AlgorithmImports import *
# endregion

class MonthlyRotationAlgorithm(QCAlgorithm):
    _roc_period = 60
    _top_n = 3

    def initialize(self) -> None:
        self.set_start_date(2022, 1, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100000)
        # automatic_indicator_warm_up only supports automatic indicators, not manual indicators.
        self.settings.automatic_indicator_warm_up = True

        for ticker in ["SPY", "EFA", "EEM", "AGG", "GLD"]:
            equity = self.add_equity(ticker, Resolution.MINUTE)
            equity.roc = self.roc(equity, self._roc_period, Resolution.DAILY)
            # Alternatively, use a manual indicator.
            # equity.roc = RateOfChange(self._roc_period)
            # self.warm_up_indicator(equity, equity.roc, Resolution.DAILY)
            # self.register_indicator(equity, equity.roc, Resolution.DAILY)

        self.schedule.on(
            self.date_rules.month_start("SPY"),
            self.time_rules.after_market_open("SPY", 1),
            self._rebalance
        )

    def _rebalance(self) -> None:
        securities = [s for s in self.securities.values() if s.roc.is_ready]
        if len(securities) < self._top_n:
            return

        if all(s.roc.current.value < 0 for s in securities):
            self.liquidate()
            return

        top = sorted(securities, key=lambda s: s.roc.current.value, reverse=True)[:self._top_n]
        top_symbols = {s.symbol for s in top}

        weight = 1.0 / self._top_n
        targets = [
            PortfolioTarget(s.symbol, weight if s.symbol in top_symbols else 0)
            for s in self.securities.values()
        ]
        self.set_holdings(targets, liquidate_existing_holdings=True)
