# region imports
from AlgorithmImports import *
from datetime import time
# endregion

class OpeningRangeBreakoutAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100000)
        self.settings.seed_initial_prices = True

        self._spy = self.add_equity("SPY", resolution=Resolution.MINUTE).symbol

        self._orb_high = None
        self._orb_low = None

        self.schedule.on(
            self.date_rules.every_day(self._spy),
            self.time_rules.at(15, 55),
            self._liquidate_eod
        )

    def _liquidate_eod(self) -> None:
        self.liquidate(self._spy)
        # Clearing the range stops further entries and resets it for tomorrow.
        self._orb_high = None
        self._orb_low = None

    def on_data(self, data: Slice) -> None:
        bar = data.bars.get(self._spy)
        if not bar:
            return

        current_time = self.time.time()

        # Build opening range from 9:30 up to (but not including) 10:00
        if time(9, 30) <= current_time < time(10, 0):
            if self._orb_high is None:
                self._orb_high = bar.high
                self._orb_low = bar.low
            else:
                self._orb_high = max(self._orb_high, bar.high)
                self._orb_low = min(self._orb_low, bar.low)
            self.plot("ORB", "High", self._orb_high)
            self.plot("ORB", "Low", self._orb_low)

        # Trade breakouts after 10:00, only one trade per day
        elif current_time >= time(10, 0):
            if self._orb_high and bar.close > self._orb_high:
                self.set_holdings(self._spy, 1.0)
                self.plot("Trades", "Direction", 1)
                self._orb_high = self._orb_low = None
            if self._orb_low and bar.close < self._orb_low:
                self.set_holdings(self._spy, -1.0)
                self.plot("Trades", "Direction", -1)
                self._orb_high = self._orb_low = None
