# region imports
from AlgorithmImports import *
# endregion


class CfdExampleAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        # Seed the price of each asset with its last known price to avoid trading errors.
        self.settings.seed_initial_prices = True
        # Trade CFDs across different markets to remain invested around the clock.
        for ticker in ['DE30EUR', 'SG30SGD', 'US30USD']:
            # Add the CFD.
            cfd = self.add_cfd(ticker)
            # Buy after market open.
            self.schedule.on(
                self.date_rules.every_day(cfd),
                self.time_rules.after_market_open(cfd, 1),
                lambda cfd=cfd: self.set_holdings(cfd, 0.3)
            )
            # Liquidate before market close.
            self.schedule.on(
                self.date_rules.every_day(cfd),
                self.time_rules.before_market_close(cfd, 1),
                lambda cfd=cfd: self.liquidate(cfd)
            )
