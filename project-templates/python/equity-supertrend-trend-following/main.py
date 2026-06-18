# region imports
from AlgorithmImports import *
# endregion


class EquitySuperTrendTrendFollowingAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100_000)
        # Request daily SPY data to feed the SuperTrend indicator and trade.
        self._spy = self.add_equity("SPY")
        # Auto-updating SuperTrend (10-period ATR, 3x multiplier) â€” the helper wires it to the bar stream.
        self._supertrend = self.str(self._spy, 10, 3, resolution=Resolution.DAILY)
        # Alternatively, use a manual indicator.
        # self._supertrend = SuperTrend(10, 3, MovingAverageType.WILDERS)
        # self.warm_up_indicator(self._spy, self._supertrend, Resolution.DAILY)
        # self.register_indicator(self._spy, self._supertrend, Resolution.DAILY)
        # Track the previous direction so we can act only on a flip.
        self._previous_direction = None
        # Register event handler to run trading logic when indicator updates
        self._supertrend.updated += self._on_supertrend_updated
        # Warm up so the ATR and SuperTrend bands are valid before the first trade.
        self.set_warm_up(self._supertrend.warm_up_period + 1, Resolution.DAILY)

    def _on_supertrend_updated(self, sender: object, updated: IndicatorDataPoint) -> None:
        """Event handler triggered every time the SuperTrend indicator updates."""
        # Direction is +1 when the SuperTrend line sits below price (bullish), -1 when above (bearish).
        if not self._supertrend.is_ready:
            return
        direction = 1 if self._supertrend.current.value < self._spy.price else -1
        if not self.is_warming_up and self._previous_direction:
            # Long when SuperTrend sits below price; short when it sits above.
            if self._previous_direction < 0 and direction > 0:
                self.set_holdings(self._spy, 1)
            elif self._previous_direction > 0 and direction < 0:
                self.set_holdings(self._spy, -1)
            elif not self.portfolio.invested:
                self.set_holdings(self._spy, 1)
        self._previous_direction = direction
