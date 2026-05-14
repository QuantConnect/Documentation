# region imports
from AlgorithmImports import *
import numpy as np
# endregion


class KOPepPairsTrading(QCAlgorithm):
    def initialize(self) -> None:
        self.set_start_date(2022, 1, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100_000)

        ko = self.add_equity("KO", Resolution.MINUTE)
        pep = self.add_equity("PEP", Resolution.MINUTE)
        self._ko = ko.symbol
        self._pep = pep.symbol

        # Cache 60 daily market sessions per security to fit the OLS
        # hedge ratio without making history requests.
        ko.session.size = 60
        pep.session.size = 60

        self._alpha = 0.0
        self._beta = 1.0
        self._spread_mean = 0.0
        self._spread_std = 1.0
        self._state = 0  # 0: flat, 1: long spread, -1: short spread

        # Demonstrate RollingWindow usage by tracking recent z-scores
        self._z_window = RollingWindow[float](5)

        self.set_warm_up(60, Resolution.DAILY)

        # Re-fit the hedge ratio weekly on the first trading day of the week
        self.schedule.on(
            self.date_rules.week_start(),
            self.time_rules.at(9, 31),
            self._update_model,
        )

        # Evaluate the spread signal daily shortly after the open
        self.schedule.on(
            self.date_rules.every_day(),
            self.time_rules.at(10, 0),
            self._trade_logic,
        )

    def on_warmup_finished(self) -> None:
        self._update_model()

    def _update_model(self) -> None:
        """Fit the OLS hedge ratio from the cached daily market sessions."""
        ko_session = self.securities[self._ko].session
        pep_session = self.securities[self._pep].session
        if ko_session.count < 60 or pep_session.count < 60:
            return

        # Session windows iterate newest-first; reverse to chronological order.
        ko_closes = np.array([x.close for x in ko_session][::-1], dtype=float)
        pep_closes = np.array([x.close for x in pep_session][::-1], dtype=float)

        # OLS: KO = beta * PEP + alpha
        A = np.vstack([pep_closes, np.ones(len(pep_closes))]).T
        result = np.linalg.lstsq(A, ko_closes, rcond=None)[0]
        self._beta = float(result[0])
        self._alpha = float(result[1])

        spread = ko_closes - (self._beta * pep_closes + self._alpha)
        self._spread_mean = float(np.mean(spread))
        self._spread_std = float(np.std(spread))

        if self._spread_std == 0:
            self._spread_std = 1e-6

    def _trade_logic(self) -> None:
        if self.is_warming_up or self._spread_std == 0:
            return

        ko_price = self.securities[self._ko].close
        pep_price = self.securities[self._pep].close

        current_spread = ko_price - (self._beta * pep_price + self._alpha)
        z_score = (current_spread - self._spread_mean) / self._spread_std
        self._z_window.add(float(z_score))

        if z_score < -2 and self._state != 1:
            # Long spread -> long KO, short PEP (dollar-neutral)
            self.set_holdings(
                [
                    PortfolioTarget(self._ko, 0.5),
                    PortfolioTarget(self._pep, -0.5),
                ],
                liquidate_existing_holdings=True,
            )
            self._state = 1
        elif z_score > 2 and self._state != -1:
            # Short spread -> short KO, long PEP (dollar-neutral)
            self.set_holdings(
                [
                    PortfolioTarget(self._ko, -0.5),
                    PortfolioTarget(self._pep, 0.5),
                ],
                liquidate_existing_holdings=True,
            )
            self._state = -1
        elif abs(z_score) < 0.5 and self._state != 0:
            self.liquidate()
            self._state = 0

    def on_end_of_algorithm(self) -> None:
        self.liquidate()
