# region imports
from AlgorithmImports import *
# endregion


class BrainCompanyFilingLanguageMetricsUniverseAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100_000)
        self.settings.seed_initial_prices = True
        self.universe_settings.resolution = Resolution.MINUTE
        # Universe of US Equities with positive sentiment in their latest SEC filings.
        self._universe = self.add_universe(BrainCompanyFilingLanguageMetricsUniverseAll, self._select_assets)
        # Rebalance before market open to trade today's universe.
        self.schedule.on(
            self.date_rules.every_day("SPY"),
            self.time_rules.at(9, 0),
            self._rebalance
        )

    def _select_assets(self, data: List[BrainCompanyFilingLanguageMetricsUniverseAll]) -> List[Symbol]:
        # Keep names with positive sentiment in both the report and MD&A sections.
        return [d.symbol for d in data
                if d.report_sentiment and d.report_sentiment.sentiment and d.report_sentiment.sentiment > 0 and
                d.management_discussion_analyasis_of_financial_condition_and_results_of_operations and
                d.management_discussion_analyasis_of_financial_condition_and_results_of_operations.sentiment and
                d.management_discussion_analyasis_of_financial_condition_and_results_of_operations.sentiment > 0]

    def _rebalance(self) -> None:
        if not self._universe.selected:
            return
        weight = 1 / len(self._universe.selected)
        targets = [PortfolioTarget(symbol, weight) for symbol in self._universe.selected]
        self.set_holdings(targets, True)
