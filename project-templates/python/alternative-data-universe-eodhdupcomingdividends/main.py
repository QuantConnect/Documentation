# region imports
from AlgorithmImports import *
# endregion


class EODHDUpcomingDividendsUniverseAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100_000)
        self.settings.seed_initial_prices = True
        # Subscribe to fundamental data so history[Fundamentals] returns data.
        self.add_data(Fundamental, "fundamentals")
        # EODHD-driven universe: ticks whenever upcoming dividend events arrive.
        self._universe = self.add_universe(EODHDUpcomingDividends, self._select)
        # Schedule daily rebalancing at 9:31 to trade the current universe selection.
        self.schedule.on(
            self.date_rules.every_day("SPY"),
            self.time_rules.at(9, 31),
            self._rebalance
        )

    def _select(self, dividends: List[EODHDUpcomingDividends]) -> List[Symbol]:
        dividends_list = list(dividends)
        # Filter by dividend date and amount.
        candidates = {d.symbol for d in dividends_list
                      if d.dividend_date and d.dividend and
                      d.dividend_date <= self.time + timedelta(1) and d.dividend > 0.05}
        self.log(f"[select] data_count={len(dividends_list)} candidates={len(candidates)}")
        if not candidates:
            return []
        # Get all US equity symbols via Fundamentals history.
        snapshot = list(self.history[Fundamentals](timedelta(5)))
        self.log(f"[select] fundamentals_snapshot_len={len(snapshot)}")
        if not snapshot:
            return []
        try:
            all_symbols = [f.symbol for f in list(snapshot[-1].values)[0].data]
        except Exception as e:
            self.log(f"[select] snapshot parse error: {e}")
            return []
        self.log(f"[select] all_us_equity_symbols={len(all_symbols)}")
        # Filter by dollar volume across ALL US equities, then return top candidates.
        fundamentals = list(self.fundamentals(all_symbols))
        self.log(f"[select] fundamentals_returned={len(fundamentals)}")
        filtered = sorted(fundamentals, key=lambda f: f.dollar_volume)[-50:]
        selected = [f.symbol for f in filtered if f.symbol in candidates]
        self.log(f"[select] top50_count={len(filtered)} selected={len(selected)}")
        return selected

    def _rebalance(self) -> None:
        if not self._universe.selected:
            return
        # Filter to only securities with valid prices.
        securities = [s for s in self._universe.selected if self.securities[s].price]
        if not securities:
            return
        weight = 1 / len(securities)
        targets = [PortfolioTarget(symbol, weight) for symbol in securities]
        self.set_holdings(targets, True)
