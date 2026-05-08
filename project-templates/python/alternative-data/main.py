# region imports
from AlgorithmImports import *
# endregion


class BrainMLRankingDataAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100_000)
        # Seed the price of each asset with its last known price to avoid trading errors.
        self.settings.seed_initial_prices = True
        # Select 5 high-volume, large-cap stocks; higher trading volume improves ML ranking credibility.
        tickers = ["AAPL", "TSLA", "MSFT", "F", "KO"]
        for ticker in tickers:
            # Requesting data to get 2 days estimated relative ranking.
            equity = self.add_equity(ticker)
            self.add_data(BrainStockRanking2Day, equity)

    def on_data(self, data: Slice) -> None:
        # Get Brain ML ranking data for all symbols in the current slice.
        points = data.get(BrainStockRanking2Day)
        if points is None:
            return
        sum_of_ranks = sum(abs(x.rank) for x in points.values()) * .9
        if sum_of_ranks == 0:
            return
        # Plot each symbol's ML rank score.
        for x in points.values():
            self.plot("Rank", x.symbol.underlying, x.rank)
        # Allocate weight proportional to each symbol's ML rank score.
        targets = [PortfolioTarget(x.symbol.underlying, x.rank/sum_of_ranks) for x in points.values()]
        self.set_holdings(targets)
