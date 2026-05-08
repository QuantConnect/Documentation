# region imports
from AlgorithmImports import *
# endregion


class IndexOptionAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100_000)
        self.settings.seed_initial_prices = True
        # Subscribe to the Option chain.
        self._option = self.add_index_option("SPX", "SPXW")
        # Filter the Option universe to only select 0DTE Options.
        self._option.set_filter(lambda u: u.expiration(0, 0).strikes(-1, 1))
        # Filter the Option universe by Delta. The last set_filter call prevails.
        # Self._option.set_filter(lambda u: u.delta(0.25, 0.75)).

    def on_data(self, data: Slice) -> None:
        if self.portfolio.invested:
            return
        # Get the Option chain data.
        chain = data.option_chains.get(self._option)
        if not chain:
            return
        # Sorted the call Option contracts according to their strike prices.
        calls = sorted([x for x in chain if x.right == OptionRight.CALL], key=lambda x: x.strike)
        if not calls: return
        # Buy 1 0DTE call Option contract for the SPX index.
        self.buy(calls[0], 1)
