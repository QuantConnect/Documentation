# region imports
from AlgorithmImports import *
# endregion

class EquitiesStaticTemplateAlgorithm(QCAlgorithm):
    _tolerance = 0.0025

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100000)
        self.settings.automatic_indicator_warm_up = True
        for ticker in ["SPY", "QQQ", "IWM"]:
            equity = self.add_equity(ticker)
            equity.macd = self.macd(equity, 12, 26, 9, MovingAverageType.EXPONENTIAL, Resolution.DAILY)
            # Alternatively, use a manual indicator.
            # equity.macd = MovingAverageConvergenceDivergence(12, 26, 9, MovingAverageType.EXPONENTIAL)
            # self.warm_up_indicator(equity.symbol, equity.macd, Resolution.DAILY)
            # self.register_indicator(equity.symbol, equity.macd, Resolution.DAILY)
            self.plot_indicator(ticker, equity.macd)
        self.schedule.on(self.date_rules.every_day('SPY'), self.time_rules.after_market_open('SPY', 1), self._rebalance)

    def _rebalance(self) -> None:
        for security in self.securities.values():
            quantity = security.holdings.quantity
            macd = security.macd
            signal_delta_percent = (macd.current.value - macd.signal.current.value)/macd.fast.current.value
            if quantity <= 0 and signal_delta_percent > self._tolerance:
                self.set_holdings(security, 1 / len(self.securities))
            if quantity >= 0 and signal_delta_percent < -self._tolerance:
                self.liquidate(security)
