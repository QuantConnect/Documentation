# region imports
from AlgorithmImports import *
# endregion


class SP500LowVolatility(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2022, 1, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(250000)
        self.settings.seed_initial_prices = True
        self.settings.minimum_order_margin_portfolio_percentage = 0
        self._lookback = 60
        self._portfolio_size = 30
        self._selected_symbols: list[Symbol] = []
        self._selection_month: tuple[int, int] | None = None
        self.universe_settings.resolution = Resolution.DAILY
        self._universe = self.add_universe(
            self.universe.etf("SPY", self._select_assets)
        )
        self.schedule.on(
            self.date_rules.month_start("SPY"),
            self.time_rules.at(9, 31),
            self._rebalance,
        )

    def _select_assets(
        self, constituents: list[ETFConstituentUniverse]
    ) -> list[Symbol] | Universe.UnchangedUniverse:
        selection_month = (self.time.year, self.time.month)
        if self._selection_month == selection_month:
            return Universe.UNCHANGED
        vol_by_symbol: dict[Symbol, float] = {}
        for constituent in constituents:
            if not constituent.weight:
                continue
            history = self.history(constituent.symbol, self._lookback, Resolution.DAILY)
            if history.empty or len(history) < self._lookback:
                continue
            closes = history["close"]
            returns = closes.pct_change().dropna()
            if len(returns) < self._lookback - 1:
                continue
            volatility = float(returns.std())
            if volatility <= 0:
                continue
            vol_by_symbol[constituent.symbol] = volatility
        ranked_symbols = sorted(vol_by_symbol, key=lambda symbol: vol_by_symbol[symbol])
        self._selected_symbols = ranked_symbols[:self._portfolio_size]
        self._selection_month = selection_month
        return self._selected_symbols

    def _rebalance(self) -> None:
        if not self._selected_symbols:
            self.liquidate()
            return
        weight = 1 / len(self._selected_symbols)
        targets = [
            PortfolioTarget(symbol, weight)
            for symbol in self._selected_symbols
        ]
        self.set_holdings(targets, liquidate_existing_holdings=True)
