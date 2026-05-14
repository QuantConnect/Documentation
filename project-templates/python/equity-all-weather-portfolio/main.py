# region imports
from AlgorithmImports import *
# endregion

class AllWeatherPortfolioAlgorithm(QCAlgorithm):
    _drift_threshold = 0.01

    def initialize(self) -> None:
        self.set_start_date(2022, 1, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(200000)

        targets = {
            "SPY": 0.30,
            "TLT": 0.40,
            "IEF": 0.15,
            "GLD": 0.075,
            "DBC": 0.075,
        }

        self._symbols = {}
        for ticker, target in targets.items():
            security = self.add_equity(ticker, Resolution.MINUTE)
            security.target = target

        # Set target weights on the first bar with data.
        self._last_rebalance_date = self.start_date.date()

        # Annual rebalance on the first trading day of each year.
        self.schedule.on(
            self.date_rules.year_start("SPY", 0),
            self.time_rules.after_market_open("SPY", 1),
            self._rebalance
        )

        # Weekly holdings-drift monitoring.
        self.schedule.on(
            self.date_rules.week_start("SPY"),
            self.time_rules.after_market_open("SPY", 30),
            self._log_drift
        )

    def on_warmup_finished(self) -> None:
        self._rebalance()

    def _rebalance(self) -> None:
        today = self.time.date()
        if self._last_rebalance_date == today:
            return

        targets = []
        for symbol, security in self.securities.items():
            # Edge case: skip GLD/DBC if missing in early data.
            if not security.has_data:
                self.debug(f"Skipping {symbol}: no data available.")
                continue

            targets.append(PortfolioTarget(symbol, security.target))

        if targets:
            self.set_holdings(targets, liquidate_existing_holdings=True)
            self.debug(f"Rebalanced on {self.time}")

        self._last_rebalance_date = today
        self._log_drift()

    def _log_drift(self) -> None:
        total_value = self.portfolio.total_portfolio_value
        if total_value <= 0:
            return

        for symbol, security in self.securities.items():
            if not security.has_data:
                continue

            current_weight = security.holdings.holdings_value / total_value
            drift = abs(current_weight - security.target)
            self.debug(f"{symbol}: target={security.target:.2%}, current={current_weight:.2%}, drift={drift:.2%}")

            if drift > self._drift_threshold:
                self.debug(f"{symbol} drift exceeds {self._drift_threshold:.2%}: {drift:.2%}")
