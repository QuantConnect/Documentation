# region imports
from AlgorithmImports import *
# endregion

class EquityDonchianBreakoutTurtleAlgorithm(QCAlgorithm):
    _risk_per_trade = 0.01

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(250000)
        self.settings.seed_initial_prices = True
        self.settings.automatic_indicator_warm_up = True
        for ticker in ["SPY", "QQQ", "IWM", "EFA", "EEM"]:
            equity = self.add_equity(ticker)
            equity.entry = self.dch(equity, 20, 20, Resolution.DAILY)
            equity.exit = self.dch(equity, 10, 10, Resolution.DAILY)
            equity.atr = self.atr(equity, 20, MovingAverageType.SIMPLE, Resolution.DAILY)
        self.schedule.on(self.date_rules.every_day("SPY"), self.time_rules.after_market_open("SPY", 1), self._rebalance)

    def _rebalance(self) -> None:
        for security in self.securities.values():
            if not security.atr.is_ready:
                continue
            price = security.price
            quantity = security.holdings.quantity
            if quantity == 0 and price > security.entry.upper_band.current.value:
                risk_shares = (self.portfolio.total_portfolio_value * self._risk_per_trade) / security.atr.current.value
                cap_shares = self.calculate_order_quantity(security, 1/len(self.securities))
                shares = int(min(risk_shares, cap_shares))
                if shares:
                    self.market_order(security, shares)
            elif quantity > 0 and price < security.exit.lower_band.current.value:
                self.liquidate(security)
