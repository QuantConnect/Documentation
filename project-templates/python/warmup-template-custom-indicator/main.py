# region imports
from AlgorithmImports import *
# endregion


class WarmUpCustomIndicatorAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100_000)
        self.settings.seed_initial_prices = True
        self._equity = self.add_equity("SPY", Resolution.DAILY)
        self._equity.volatility = CustomVolatility(3*21)
        # Warm the custom indicator with daily trade bars.
        for bar in self.history[TradeBar](self._equity, self._equity.volatility.warm_up_period, Resolution.DAILY):
            self._equity.volatility.update(bar)
        self.register_indicator(self._equity, self._equity.volatility)

    def on_data(self, data: Slice) -> None:
        if not self._equity.volatility.is_ready:
            return
        # Buy if volatility is increasing.
        if not self._equity.invested and self._equity.volatility.value > self._equity.volatility.previous.value:
            self.set_holdings(self._equity, 1)
        # Exit if volatility is decreasing.
        elif self._equity.invested and self._equity.volatility.value < self._equity.volatility.previous.value:
            self.liquidate()


class CustomVolatility(PythonIndicator):

    def __init__(self, period, trading_days_per_year=252):
        super().__init__()
        self.warm_up_period = period + 1
        self._trading_days_per_year = trading_days_per_year
        self.value = 0
        self._log_return = LogReturn(1)
        self._standard_deviation = IndicatorExtensions.of(StandardDeviation(period), self._log_return)

    def update(self, input_: BaseData):
        # Annualized log-return volatility.
        price = input_.value
        if price <= 0:
            return
        self._log_return.update(input_.end_time, price)
        if self.is_ready:
            self.value = self._standard_deviation.current.value * math.sqrt(self._trading_days_per_year) * 100.0
        return self.is_ready

    @property
    def is_ready(self) -> bool:
        return self._standard_deviation.is_ready
