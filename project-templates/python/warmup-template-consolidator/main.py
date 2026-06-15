# region imports
from AlgorithmImports import *
# endregion


class WarmUpConsolidatorAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100_000)
        self.settings.seed_initial_prices = True
        self._equity = self.add_equity("SPY", Resolution.MINUTE)
        # Build the 30-minute consolidator.
        self._consolidator = TradeBarConsolidator(timedelta(minutes=30))
        # Subscribe the consolidator for automatic updates.
        self.subscription_manager.add_consolidator(self._equity, self._consolidator)
        # Create 30-minute indicators for the scheduled trading signal.
        self._ema = ExponentialMovingAverage(10)
        self._rsi = RelativeStrengthIndex(12)
        self._indicators = [self._ema, self._rsi]
        for indicator in self._indicators:
            self.register_indicator(self._equity, indicator, self._consolidator)
        # Schedule the trading logic separately from the consolidator updates.
        self.schedule.on(self.date_rules.every_day(self._equity), self.time_rules.every(timedelta(minutes=30)), self._rebalance)
        # Warm the consolidator and registered indicators with one history request.
        for bar in self.history[TradeBar](self._equity, max(indicator.warm_up_period for indicator in self._indicators)):
            self._consolidator.update(bar)
            self._ema.update(bar)
            self._rsi.update(bar)

    def _rebalance(self) -> None:
        price = self._equity.price
        if not all([indicator.is_ready for indicator in self._indicators]) or not price:
            return
        if price > self._ema.current.value and self._rsi.current.value > 50:
            self.set_holdings(self._equity, 1)
        elif self._equity.invested:
            self.liquidate(self._equity)
