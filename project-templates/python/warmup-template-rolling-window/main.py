# region imports
from AlgorithmImports import *
# endregion


class WarmUpRollingWindowAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100_000)
        self.settings.seed_initial_prices = True
        self._equity = self.add_equity("SPY", Resolution.DAILY)
        self._window = RollingWindow[TradeBar](20)
        # Warm the rolling window with daily history before the first data event.
        history = self.history[TradeBar](self._equity, self._window.size, Resolution.DAILY)
        for bar in history:
            self._window.add(bar)

    def on_data(self, data: Slice) -> None:
        bar = data.bars.get(self._equity)
        if not bar:
            return
        # Add the latest bar before checking the momentum signal.
        self._window.add(bar)
        if not self._window.is_ready:
            return
        # Buy if the latest close is above the previous close.
        if not self._equity.invested and self._window[0].close > self._window[1].close:
            self.set_holdings(self._equity, 1)
        # Sell if the latest close is below the previous close.
        elif self._equity.invested and self._window[0].close < self._window[1].close:
            self.liquidate(self._equity)
