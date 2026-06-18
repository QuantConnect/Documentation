# region imports
from AlgorithmImports import *
# endregion

class ObvDivergenceAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self.set_start_date(2022, 1, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100000)

        # automatic_indicator_warm_up only supports automatic indicators, not manual indicators.
        self.settings.automatic_indicator_warm_up = True

        self._spy = self.add_equity("SPY", Resolution.MINUTE)

        # OBV on SPY at daily resolution
        self._obv = self.obv(self._spy.symbol, Resolution.DAILY)
        # Alternatively, use a manual indicator.
        # self._obv = OnBalanceVolume()
        # self.warm_up_indicator(self._spy, self._obv, Resolution.DAILY)
        # self.register_indicator(self._spy, self._obv, Resolution.DAILY)

        # Price ROC(20) on SPY at daily resolution
        self._price_roc = self.roc(self._spy.symbol, 20, Resolution.DAILY)
        # Alternatively, use a manual indicator.
        # self._price_roc = RateOfChange(20)
        # self.warm_up_indicator(self._spy, self._price_roc, Resolution.DAILY)
        # self.register_indicator(self._spy, self._price_roc, Resolution.DAILY)

        # ROC of OBV using IndicatorExtensions
        obv_roc_indicator = RateOfChange(20)
        self._obv_roc = IndicatorExtensions.of(obv_roc_indicator, self._obv)

        # Plot indicators
        self.plot_indicator("OBV", self._obv)
        self.plot_indicator("Price ROC", self._price_roc)
        self.plot_indicator("OBV ROC", self._obv_roc)

        self._bullish_count = 0
        self._bearish_count = 0
        self._required_confirmation = 5

        self.schedule.on(
            self.date_rules.every_day("SPY"),
            self.time_rules.after_market_open("SPY", 30),
            self._check_divergence
        )

    def _check_divergence(self) -> None:
        if not self._price_roc.is_ready or not self._obv_roc.is_ready:
            return

        price_roc_value = self._price_roc.current.value
        obv_roc_value = self._obv_roc.current.value

        bullish_divergence = price_roc_value < 0 and obv_roc_value > 0
        bearish_divergence = price_roc_value > 0 and obv_roc_value < 0

        quantity = self._spy.holdings.quantity

        if bullish_divergence:
            self._bullish_count += 1
            self._bearish_count = 0
        elif bearish_divergence:
            self._bearish_count += 1
            self._bullish_count = 0
        else:
            self._bullish_count = 0
            self._bearish_count = 0
            if quantity != 0:
                self.liquidate(self._spy.symbol)
            return

        if self._bullish_count >= self._required_confirmation and quantity <= 0:
            self.set_holdings(self._spy.symbol, 1.0)
        elif self._bearish_count >= self._required_confirmation and quantity >= 0:
            self.set_holdings(self._spy.symbol, -1.0)
