# region imports
from AlgorithmImports import *
# endregion

class PermanentPortfolioAlgorithm(QCAlgorithm):
    _targets: list[PortfolioTarget] = []
    _weight: float = 0.25
    _drift_threshold = 0.03

    def initialize(self) -> None:
        self.set_start_date(2022, 1, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100000)
        self.settings.seed_initial_prices = True
        
        for ticker in {"SPY", "TLT", "GLD", "SHY"}:
            symbol = self.add_equity(ticker, Resolution.MINUTE).symbol
            self._targets.append(PortfolioTarget(symbol, self._weight))

        self.schedule.on(
            self.date_rules.year_start("SPY", 2),
            self.time_rules.after_market_open("SPY", 5),
            self._rebalance
        )

    def on_warmup_finished(self) -> None:
        self.set_holdings(self._targets, liquidate_existing_holdings=True)

    def _rebalance(self) -> None:
        for target in self._targets:
            current_weight = self.portfolio[target.symbol].holdings_value / self.portfolio.total_holdings_value
            if abs(current_weight - self._weight) > self._drift_threshold:
                self.set_holdings(self._targets, liquidate_existing_holdings=True)
                return