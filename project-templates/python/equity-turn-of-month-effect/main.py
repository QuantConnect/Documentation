# region imports
from AlgorithmImports import *
from datetime import datetime, date
import calendar
# endregion


class MonthlyEdgeStrategy(QCAlgorithm):
    def initialize(self) -> None:
        self.set_start_date(2022, 1, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100000)

        self._spy = self.add_equity("SPY", Resolution.MINUTE).symbol
        self._month_trading_days = []
        self._current_month = None

        self.schedule.on(
            self.date_rules.every_day(self._spy),
            self.time_rules.after_market_open(self._spy, 1),
            self._rebalance,
        )

    def _rebalance(self) -> None:
        today = self.time.date()
        current_month = (today.year, today.month)

        if current_month != self._current_month:
            self._current_month = current_month
            self._month_trading_days = self._get_month_trading_days(today)

        if not self._month_trading_days:
            return

        is_hold = today in self._month_trading_days[:3] or today in self._month_trading_days[-4:]

        if is_hold and not self.portfolio[self._spy].invested:
            self.set_holdings(self._spy, 1.0)
        elif not is_hold and self.portfolio[self._spy].invested:
            self.liquidate(self._spy)

    def _get_month_trading_days(self, anchor_date):
        start = datetime(anchor_date.year, anchor_date.month, 1)
        last_day = calendar.monthrange(anchor_date.year, anchor_date.month)[1]
        end = datetime(anchor_date.year, anchor_date.month, last_day)

        trading_days = self.trading_calendar.get_trading_days(start, end)
        return [date(d.date.year, d.date.month, d.date.day) for d in trading_days]
