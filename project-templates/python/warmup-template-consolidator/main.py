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
        consolidation_minutes = 30
        # Build and subscribe the 30-minute consolidator.
        consolidator = self.consolidate(self._equity.symbol, timedelta(minutes=consolidation_minutes), self._consolidation_handler)
        # Create 30-minute indicators for the scheduled trading signal.
        self._ema = ExponentialMovingAverage(10)
        self._rsi = RelativeStrengthIndex(12)
        indicators = [self._ema, self._rsi]
        for indicator in indicators:
            self.register_indicator(self._equity, indicator, consolidator)
        # Warm the consolidator and registered indicators with one history request.
        warm_up_period = max(indicator.warm_up_period for indicator in indicators) * consolidation_minutes
        for bar in self.history[TradeBar](self._equity, warm_up_period):
            consolidator.update(bar)

    def _consolidation_handler(self, consolidated_bar: TradeBar) -> None:
        if self.is_warming_up or not (self.is_market_open(self._equity.symbol) and self._ema.is_ready and self._rsi.is_ready):
            return
        price = consolidated_bar.close
        if not self._equity.invested and price > self._ema.current.value and self._rsi.current.value > 50:
            self.set_holdings(self._equity, 1)
        elif self._equity.invested and (price < self._ema.current.value or self._rsi.current.value < 50):
            self.liquidate(self._equity)
