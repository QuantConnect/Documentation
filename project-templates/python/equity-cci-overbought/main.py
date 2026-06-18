# region imports
from AlgorithmImports import *
# endregion

class XleCciMeanReversionAlgorithm(QCAlgorithm):
    _atr_threshold = 0.01  # Skip if ATR / price < 1%

    def initialize(self) -> None:
        self.set_start_date(2022, 1, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100000)
        # automatic_indicator_warm_up only supports automatic indicators, not manual indicators.
        self.settings.automatic_indicator_warm_up = True

        self._xle = self.add_equity("XLE", Resolution.MINUTE).symbol

        self._cci = self.cci(self._xle, 20, MovingAverageType.SIMPLE, Resolution.DAILY)
        # Alternatively, use a manual indicator.
        # self._cci = CommodityChannelIndex(20, MovingAverageType.SIMPLE)
        # self.warm_up_indicator(self._xle, self._cci, Resolution.DAILY)
        # self.register_indicator(self._xle, self._cci, Resolution.DAILY)
        self._atr = self.atr(self._xle, 14, MovingAverageType.SIMPLE, Resolution.DAILY)
        # Alternatively, use a manual indicator.
        # self._atr = AverageTrueRange(14, MovingAverageType.SIMPLE)
        # self.warm_up_indicator(self._xle, self._atr, Resolution.DAILY)
        # self.register_indicator(self._xle, self._atr, Resolution.DAILY)

        self.plot_indicator("CCI", self._cci)

        self.schedule.on(
            self.date_rules.every_day(self._xle),
            self.time_rules.after_market_open(self._xle, 1),
            self._rebalance
        )

    def _rebalance(self) -> None:
        if not self._cci.is_ready or not self._atr.is_ready:
            return

        price = self.securities[self._xle].price
        if price == 0:
            return

        atr_ratio = self._atr.current.value / price
        self.plot("ATR Ratio", "Ratio", atr_ratio)
        self.plot("ATR Ratio", "Threshold", self._atr_threshold)
        if atr_ratio < self._atr_threshold:
            return

        cci = self._cci.current.value
        holdings = self.portfolio[self._xle].quantity

        # Exit long at CCI >= 0
        if holdings > 0 and cci >= 0:
            self.liquidate(self._xle, tag=f"Exit long XLE at CCI = {cci:.2f}")
            holdings = 0

        # Cover short at CCI <= 0
        if holdings < 0 and cci <= 0:
            self.liquidate(self._xle, tag=f"Cover short XLE at CCI = {cci:.2f}")
            holdings = 0

        # Enter new positions
        if holdings == 0:
            if cci < -100:
                self.set_holdings(self._xle, 1.0, tag=f"Long XLE at CCI = {cci:.2f}")
            elif cci > 100:
                self.set_holdings(self._xle, -1.0, tag=f"Short XLE at CCI = {cci:.2f}")
