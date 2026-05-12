# region imports
from AlgorithmImports import *
# endregion


class BrainSentimentIndicatorUniverseAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100_000)
        self.settings.seed_initial_prices = True
        self.universe_settings.resolution = Resolution.DAILY
        # Add a Brain Sentiment universe, restricted to high-sentiment names with strong coverage.
        self._universe = self.add_universe(BrainSentimentIndicatorUniverse, self._select_assets)
        # Rebalance every day at 9am.
        self.schedule.on(
            self.date_rules.every_day("SPY"),
            self.time_rules.at(9, 0),
            self._rebalance
        )

    def _select_assets(self, data: List[BrainSentimentIndicatorUniverse]) -> List[Symbol]:
        # Keep names with both active mention coverage 15+ articles and positive 7-day sentiment above 0.5.
        return [d.symbol for d in data
                if d.total_article_mentions_7_days and d.total_article_mentions_7_days > 15 and
                d.sentiment_7_days and d.sentiment_7_days > 0.5]

    def _rebalance(self) -> None:
        if not self._universe.selected:
            return
        # Filter to only securities with valid prices.
        tradeable = [s for s in self._universe.selected if self.securities[s].price]
        if not tradeable:
            return
        weight = 1 / len(tradeable)
        targets = [PortfolioTarget(symbol, weight) for symbol in tradeable]
        self.set_holdings(targets, True)
