# region imports
from AlgorithmImports import *
# endregion

class EquityMacdHistogramDivergenceAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100000)
        # Add AAPL with daily resolution.
        self._equity = self.add_equity("AAPL")
        # Create MACD indicator with standard parameters.
        self._macd = self.macd(self._equity, 12, 26, 9, MovingAverageType.EXPONENTIAL, Resolution.DAILY)
        # Warm up the indicator and its windows using historical data.
        self.indicator_history(self._macd, self._equity, self._macd.warm_up_period + 1)
        # Plot MACD components for visualization.
        self.plot_indicator("MACD", self._macd, self._macd.signal)
        self.plot_indicator("Histogram", self._macd.histogram)

    def on_data(self, data: Slice) -> None:
        # Wait for indicator to be ready and have at least 2 values in windows.
        if self._macd.samples < self._macd.warm_up_period + 1:
            return
        # Get current and previous histogram values from built-in window.
        histogram_now = self._macd.histogram.window[0].value
        histogram_prev = self._macd.histogram.window[1].value
        # Detect zero crossovers in the histogram.
        crossed_above = histogram_prev <= 0 < histogram_now
        crossed_below = histogram_prev >= 0 > histogram_now
        # Check if signal line is rising for confirmation.
        signal_rising = self._macd.signal.window[0].value > self._macd.signal.window[1].value
        # Entry: Buy when histogram crosses above zero and signal is rising.
        if not self._equity.invested and crossed_above and signal_rising:
            self.set_holdings(self._equity, 1)
        # Exit: Sell when histogram crosses below zero.
        elif self._equity.invested and crossed_below:
            self.liquidate()
