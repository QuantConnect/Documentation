# region imports
from AlgorithmImports import *
# endregion


class WarmUpManualIndicatorAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100_000)
        self.settings.seed_initial_prices = True
        self._equity = self.add_equity("SPY", Resolution.DAILY)
        self._ema = ExponentialMovingAverage(10)
        self._rsi = RelativeStrengthIndex(12)
        self._indicators = [self._ema, self._rsi]
        # Warm the manually managed indicators with daily history.
        history = self.history[TradeBar](self._equity, max(indicator.warm_up_period for indicator in self._indicators))
        for bar in history:
            for indicator in self._indicators:
                indicator.update(bar)

    def on_data(self, data: Slice) -> None:
        bar = data.bars.get(self._equity)
        if not bar:
            return
        # Update the indicators once from the subscribed daily bar.
        for indicator in self._indicators:
            indicator.update(bar)
        if not all([indicator.is_ready for indicator in self._indicators]):
            return
        # Buy if both indicators are increasing.
        if all([indicator.current.value > indicator.previous.value for indicator in self._indicators]):
            self.set_holdings(self._equity, 1)
        # Sell if either indicator is decreasing or we are invested.
        elif any([indicator.current.value < indicator.previous.value for indicator in self._indicators]) or self._equity.invested:
            self.liquidate(self._equity)
