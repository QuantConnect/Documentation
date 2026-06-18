# region imports
from AlgorithmImports import *
# endregion


class EquityKeltnerSqueezeAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100_000)
        self.settings.automatic_indicator_warm_up = True
        # Trade multiple ETFs.
        tickers = ["SPY", "QQQ", "IWM", "DIA"]
        self._weight = 1.0 / len(tickers)
        for ticker in tickers:
            equity = self.add_equity(ticker)
            # BB with 1.5 std devs and KC with 2.0 ATR multiplier for squeeze detection.
            equity.bb = self.bb(equity, 20, 1.5, resolution=Resolution.DAILY)
            # Alternatively, use a manual indicator.
            # equity.bb = BollingerBands(20, 1.5)
            # self.warm_up_indicator(equity.symbol, equity.bb, Resolution.DAILY)
            # self.register_indicator(equity.symbol, equity.bb, Resolution.DAILY)
            equity.kc = self.kch(equity, 20, 2.0, resolution=Resolution.DAILY)
            # Alternatively, use a manual indicator.
            # equity.kc = KeltnerChannels(20, 2.0)
            # self.warm_up_indicator(equity.symbol, equity.kc, Resolution.DAILY)
            # self.register_indicator(equity.symbol, equity.kc, Resolution.DAILY)
            equity.in_squeeze = False
            equity.armed = False
        # Add a Schedule Event to scan for trades daily.
        self.schedule.on(
            self.date_rules.every_day("SPY"),
            self.time_rules.after_market_open("SPY", 1),
            self._rebalance
        )


    def _rebalance(self) -> None:
        for security in self.securities.values():
            if not security.bb.is_ready or not security.kc.is_ready:
                continue
            bb_upper = security.bb.upper_band.current.value
            bb_lower = security.bb.lower_band.current.value
            bb_middle = security.bb.middle_band.current.value
            kc_upper = security.kc.upper_band.current.value
            kc_lower = security.kc.lower_band.current.value
            price = security.price
            in_squeeze = bb_upper < kc_upper and bb_lower > kc_lower
            if in_squeeze and not security.in_squeeze:
                security.armed = True
            security.in_squeeze = in_squeeze
            # Manage any open position: exit when price reverts to the BB midline.
            holding = security.holdings
            if holding.invested:
                if (holding.quantity > 0 and price <= bb_middle or 
                    holding.quantity < 0 and price >= bb_middle):
                    self.liquidate(security)
                continue
            # Wait for the squeeze to release before acting on a BB break.
            if in_squeeze or not security.armed:
                continue
            # Equal weight allocation across all securities.
            if price > bb_upper:
                self.set_holdings(security, self._weight)
                security.armed = False
            elif price < bb_lower:
                self.set_holdings(security, -self._weight)
                security.armed = False
