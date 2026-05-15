# region imports
from AlgorithmImports import *
# endregion

class DualMomentumAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2022, 1, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100000)
        self.settings.seed_initial_prices = True
        self.settings.automatic_indicator_warm_up = True

        for ticker in ["SPY", "EFA", "AGG"]:
            equity = self.add_equity(ticker, Resolution.MINUTE)
            equity.rocp = self.rocp(equity.symbol, 252, Resolution.DAILY)

        self.schedule.on(
            self.date_rules.month_start("SPY"),
            self.time_rules.before_market_open("SPY", 5),
            self._rebalance
        )

    def on_warmup_finished(self) -> None:
        self._rebalance()

    def _rebalance(self) -> None:
        returns = {sym: sec.rocp.current.value for sym, sec in self.securities.items() if sec.rocp.is_ready}
        if not returns:
            return
        winner = max(returns, key=lambda s: returns[s])
        self.set_holdings([PortfolioTarget(winner, 1.0)], liquidate_existing_holdings=True)
