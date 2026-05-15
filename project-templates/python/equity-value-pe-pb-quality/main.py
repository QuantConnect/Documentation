# region imports
from AlgorithmImports import *
import math
from typing import List
# endregion

class LowPePbHighRoeAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self.set_start_date(2022, 1, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(250000)
        self.settings.seed_initial_prices = True
        
        self.universe_settings.resolution = Resolution.MINUTE
        self.universe_settings.leverage = 2.0

        self._universe = self.add_universe(self._select_fundamentals)

        self.schedule.on(
            self.date_rules.month_start(),
            self.time_rules.at(9, 35),
            self._rebalance
        )
    
    def _select_fundamentals(self, fundamentals: List[Fundamental]) -> List[Symbol]:
        filtered = []
        for f in fundamentals:
            pe = f.valuation_ratios.pe_ratio
            pb = f.valuation_ratios.pb_ratio
            roe = f.operation_ratios.roe.one_year

            if (f.market_cap > 10_000_000_000 and
                f.dollar_volume > 10_000_000 and
                f.price > 5.0 and
                math.isfinite(pe) and
                math.isfinite(pb) and
                math.isfinite(roe)):
                filtered.append((f.symbol, pe, pb, roe))
        
        if len(filtered) < 20:
            return Universe.UNCHANGED
        
        pe_values = [-x[1] for x in filtered]
        pb_values = [-x[2] for x in filtered]
        roe_values = [x[3] for x in filtered]
        
        def _z_score(values: List[float]) -> List[float]:
            n = len(values)
            mean = sum(values) / n
            variance = sum((v - mean) ** 2 for v in values) / n
            std = math.sqrt(variance)
            if std == 0:
                return [0.0] * n
            return [(v - mean) / std for v in values]
        
        z_pe = _z_score(pe_values)
        z_pb = _z_score(pb_values)
        z_roe = _z_score(roe_values)
        
        scored = []
        for i, (symbol, _, _, _) in enumerate(filtered):
            composite = (z_pe[i] + z_pb[i] + z_roe[i]) / 3.0
            scored.append((symbol, composite))
        
        scored.sort(key=lambda x: x[1], reverse=True)
        top_20 = [s[0] for s in scored[:20]]
        return top_20
    
    def on_warmup_finished(self) -> None:
        self._rebalance()

    def _rebalance(self) -> None:
        selected = self._universe.selected
        if not selected:
            return

        weight = 1.0 / len(selected)
        targets = [PortfolioTarget(symbol, weight) for symbol in selected]
        self.set_holdings(targets, liquidate_existing_holdings=True)
