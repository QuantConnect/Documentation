# region imports
from AlgorithmImports import *
# endregion


class Static6040Algorithm(QCAlgorithm):
    _spy_weight = 0.60
    _tlt_weight = 0.40
    _drift_threshold = 0.05

    def initialize(self) -> None:
        self.set_start_date(2022, 1, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100000)

        self._spy = self.add_equity("SPY", Resolution.MINUTE).symbol
        self._tlt = self.add_equity("TLT", Resolution.MINUTE).symbol

        self.schedule.on(
            self.date_rules.quarter_start("SPY"),
            self.time_rules.after_market_open("SPY", 1),
            self._rebalance,
        )

        self._targets = {
            self._spy: self._spy_weight,
            self._tlt: self._tlt_weight,
        }

    def on_warmup_finished(self) -> None:
        self._rebalance()

    def _rebalance(self) -> None:
        needs_rebalance = False
        for symbol, target_weight in self._targets.items():
            current_weight = self.portfolio[symbol].holdings_value / self.portfolio.total_portfolio_value
            if abs(current_weight - target_weight) > self._drift_threshold:
                needs_rebalance = True
                break

        if not needs_rebalance:
            return

        targets = [PortfolioTarget(symbol, weight) for symbol, weight in self._targets.items()]
        self.set_holdings(targets)
