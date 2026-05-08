# region imports
from AlgorithmImports import *
# endregion


class CustomIndicatorsAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        # Request daily SPY data to feed the indicators to generate trade signals and trade.
        self._spy = self.add_equity("SPY")
        # Create a custom money flow index to generate a trade signal.
        self._custom_mfi = CustomMoneyFlowIndex(20)
        # Warm up for immediate usage of indicators.
        self.set_warm_up(20, Resolution.DAILY)

    def on_data(self, data: Slice) -> None:
        bar = data.bars.get(self._spy)
        if not bar:
            return
        # Update the custom MFI with the updated trade bar to obtain the updated trade signal.
        self._custom_mfi.update(bar)
        # Don't trade until warm-up is done.
        if self.is_warming_up:
            return
        # Buy if the positive money flow is above negative, indicating demand is greater than supply, driving up the price.
        if self._custom_mfi.current.value > 50:
            self.set_holdings(self._spy, 1)
        # Sell if the positive money flow is below negative, indicating demand is less than supply, driving down the price.
        else:
            self.set_holdings(self._spy, -1)


class CustomMoneyFlowIndex(PythonIndicator):

    def __init__(self, period: int) -> None:
        super().__init__()
        self.value = 0
        self._previous_typical_price = 0
        self._negative_money_flow: RollingWindow[float] = RollingWindow(period)
        self._positive_money_flow: RollingWindow[float] = RollingWindow(period)

    def update(self, input: BaseData) -> bool:
        if not isinstance(input, TradeBar):
            raise TypeError('CustomMoneyFlowIndex.update: input must be a TradeBar')
        # Estimate the money flow by averaging the price multiplied by volume.
        typical_price = (input.high + input.low + input.close) / 3
        money_flow = typical_price * input.volume
        # We need to avoid double-rounding errors.
        if abs(self._previous_typical_price / typical_price - 1) < 1e-10:
            self._previous_typical_price = typical_price
        # Add the period money flow to calculate the aggregated money flow.
        self._negative_money_flow.add(money_flow if typical_price < self._previous_typical_price else 0)
        self._positive_money_flow.add(money_flow if typical_price > self._previous_typical_price else 0)
        self._previous_typical_price = typical_price
        positive_money_flow_sum = sum(self._positive_money_flow)
        total_money_flow = positive_money_flow_sum + sum(self._negative_money_flow)
        # Set the value to be the positive money flow ratio.
        self.value = 100
        if total_money_flow != 0:
            self.value *= positive_money_flow_sum / total_money_flow
        # Set the is_ready property to receive the required bars to fill all windows.
        return self._positive_money_flow.is_ready
