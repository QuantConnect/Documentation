# region imports
from AlgorithmImports import *
# endregion


class SP500Momentum(QCAlgorithm):
    def initialize(self) -> None:
        self.set_start_date(2022, 1, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(250_000)

        self.settings.seed_initial_prices = True
        self.settings.minimum_order_margin_portfolio_percentage = 0

        self._lookback = 252
        self._universe_cap = 100
        self._top_decile_pct = 0.1

        # One momentum indicator per constituent, fed from the universe stream.
        self._momentum_by_symbol: dict[Symbol, MomentumPercent] = {}

        # Chain SPY ETF constituents into a fundamental universe.
        self.universe_settings.resolution = Resolution.MINUTE
        self._universe = self.add_universe(
            self.universe.etf("SPY"),
            self._select_assets,
        )
        # Warm up the per-symbol momentum indicators with daily bars.
        self.set_warm_up(self._lookback + 1, Resolution.DAILY)

        # Monthly rebalance shortly after the open
        self.schedule.on(
            self.date_rules.month_start("SPY"),
            self.time_rules.at(9, 31),
            self._rebalance,
        )

    def _select_assets(self, fundamentals: list[Fundamental]):
        # Stream each constituent's adjusted price into its momentum indicator.
        ready = [
            f for f in fundamentals
            if self._momentum_by_symbol.setdefault(
                f.symbol, MomentumPercent(self._lookback)
            ).update(f.end_time, f.adjusted_price)
        ]
        if self.is_warming_up:
            return Universe.UNCHANGED

        # Cap the universe at the top 100 constituents by fundamental dollar volume.
        top_by_volume = sorted(ready, reverse=True,
            key=lambda f: f.dollar_volume
        )[:self._universe_cap]
        if not top_by_volume:
            return Universe.UNCHANGED

        # Select the top decile by momentum.
        decile_count = max(1, int(len(top_by_volume) * self._top_decile_pct))
        ranked = sorted(top_by_volume, reverse=True,
            key=lambda f: self._momentum_by_symbol[f.symbol].current.value
        )
        return [f.symbol for f in ranked[:decile_count]]

    def _rebalance(self) -> None:
        selected = list(self._universe.selected)
        if not selected:
            return
        weight = 1.0 / len(selected)
        targets = [PortfolioTarget(symbol, weight) for symbol in selected]
        self.set_holdings(targets, liquidate_existing_holdings=True)
