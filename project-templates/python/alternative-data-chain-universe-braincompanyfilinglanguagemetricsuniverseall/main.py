# region imports
from AlgorithmImports import *
# endregion

class BrainCompanyFilingLanguageMetricsChainedUniverseAlgorithm(QCAlgorithm):

    _fundamental = []

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100000)

        self.universe_settings.resolution = Resolution.DAILY
        # First universe: top 100 US Equities by dollar volume; emits Universe.UNCHANGED.
        self.add_universe(self._fundamental_filter)
        # Second universe: positive sentiment in latest SEC filings, intersected with the fundamental list.
        self._universe = self.add_universe(BrainCompanyFilingLanguageMetricsUniverseAll, self._select_assets)

        # Rebalance shortly after the open so today's intersection is locked in.
        self.schedule.on(self.date_rules.every_day("SPY"), self.time_rules.at(9, 0, 0), self._rebalance)

    def _fundamental_filter(self, fundamental: List[Fundamental]):
        sorted_by_dollar_volume = sorted(fundamental, key=lambda x: x.dollar_volume, reverse=True)
        self._fundamental = [c.symbol for c in sorted_by_dollar_volume[:100]]
        return Universe.UNCHANGED

    def _select_assets(self, alt_coarse: List[BrainCompanyFilingLanguageMetricsUniverseAll]) -> List[Symbol]:
        # Keep names with positive sentiment in both the report and MD&A sections.
        alt = [d.symbol for d in alt_coarse
               if d.report_sentiment and d.report_sentiment.sentiment and d.report_sentiment.sentiment > 0
               and d.management_discussion_analyasis_of_financial_condition_and_results_of_operations
               and d.management_discussion_analyasis_of_financial_condition_and_results_of_operations.sentiment
               and d.management_discussion_analyasis_of_financial_condition_and_results_of_operations.sentiment > 0]
        return list(set(self._fundamental) & set(alt))

    def _rebalance(self) -> None:
        if not self._universe.selected:
            return

        weight = 1 / len(self._universe.selected)
        targets = [PortfolioTarget(symbol, weight) for symbol in self._universe.selected]

        self.set_holdings(targets, liquidate_existing_holdings=True)
