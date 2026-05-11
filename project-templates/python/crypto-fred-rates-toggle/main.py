# region imports
from datetime import timedelta

from AlgorithmImports import *
# endregion

class CryptoBTCFedFundsToggleAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 1, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100000)
        # Trade BTCUSD on Kraken, rotating to PAXGUSD (gold-backed) when the rate cycle turns hawkish.
        self._btc = self.add_crypto("BTCUSD", market=Market.KRAKEN)
        self._safe = self.add_crypto("PAXGUSD", market=Market.KRAKEN)
        # Subscribe to FRED's Federal Funds Effective Rate (series ID FEDFUNDS).
        self._fedfunds = self.add_data(Fred, "FEDFUNDS", Resolution.DAILY).symbol
        # 63-bar rate of change ~ 3-month change in the Fed Funds Rate.
        self._roc = RateOfChange(3)
        # FEDFUNDS publishes monthly, so let's warm up the indicator.
        history = self.history[Fred](self._fedfunds, 365, Resolution.DAILY)
        for data_point in history:
            self._roc.update(data_point.time, data_point.value)

    def on_data(self, slice: Slice) -> None:
        # FRED only emits on publication days; act when a fresh print arrives.
        data = slice.get(Fred).get(self._fedfunds)
        if not (data and self._roc.update(data.time, data.value)):
            return
        # Negative 3-month change = easing cycle = risk-on for BTC.
        if self._roc.current.value < 0 and not self._btc.invested:
            self.set_holdings(self._btc, 1, True)
        # Non-negative = flat or hiking = rotate to PAXG (gold-backed safe asset).
        elif self._roc.current.value >= 0 and not self._safe.invested:
            self.set_holdings(self._safe, 1, True)
