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
        ema = ExponentialMovingAverage(10)
        rsi = RelativeStrengthIndex(12)
        self._indicators = [ema, rsi]
        # Warm the manual indicators with the helper method.
        for indicator in self._indicators:
            self.warm_up_indicator(self._equity.symbol, indicator)

    def on_data(self, data: Slice) -> None:
        bar = data.bars.get(self._equity)
        if not bar:
            return
        # Update the indicators once from the subscribed daily bar.
        if not all([indicator.update(bar) for indicator in self._indicators]):
            return
        # Buy if both indicators are increasing.
        if not self._equity.invested and all([indicator.current.value > indicator.previous.value for indicator in self._indicators]):
            self.set_holdings(self._equity, 1)
        # Sell if either indicator is decreasing.
        elif self._equity.invested and any([indicator.current.value < indicator.previous.value for indicator in self._indicators]):
            self.liquidate(self._equity)
