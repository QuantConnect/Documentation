# region imports
from AlgorithmImports import *
# endregion


class SP500LowVolatility(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2022, 1, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(250_000)
        self.settings.seed_initial_prices = True
        self._lookback = 60
        self.universe_settings.resolution = Resolution.MINUTE
        # Refilter the ETF constituents monthly to match the rebalance cadence.
        self.universe_settings.schedule.on(self.date_rules.month_start('SPY'))
        # Add a universe of the SPY constituents ranked by realized volatility.
        self._universe = self.add_universe(self.universe.etf('SPY', universe_filter_func=self._select_assets))
        # Create a Scheduled Event to rebalance the portfolio monthly.
        self.schedule.on(self.date_rules.month_start('SPY'), self.time_rules.at(9, 0), self._rebalance)

    def _select_assets(self, constituents: list[ETFConstituentUniverse]) -> list[Symbol]:
        # Store the realized volatility of each constituent.
        volatility_by_symbol: dict[Symbol, float] = {}
        for constituent in constituents:
            history = self.history(constituent.symbol, self._lookback, Resolution.DAILY)
            if history.empty:
                continue
            returns = history["close"].pct_change().dropna()
            volatility = float(np.std(returns.to_numpy()))
            volatility_by_symbol[constituent.symbol] = volatility
        # Select the 30 ETF constituents with the lowest 60-day realized volatility.
        return [symbol for symbol, _ in sorted(volatility_by_symbol.items(), key=lambda x: x[1])[:30]]

    def _rebalance(self) -> None:
        selected_symbols = [symbol for symbol in self._universe.selected]
        if not selected_symbols:
            return
        # Equal-weight the selected low-volatility constituents.
        weight = 1 / len(selected_symbols)
        targets = [PortfolioTarget(symbol, weight) for symbol in selected_symbols]
        self.set_holdings(targets, liquidate_existing_holdings=True)
