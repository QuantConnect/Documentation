# region imports
from AlgorithmImports import *
# endregion

class EquityOptionAlgorithm(QCAlgorithm):
    
    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100000)

        self._option = self.add_option("SPY")
        # Using Staddle() method, it will only return the best-matched ATM call and put contracts expiring after 30 days.
        # It provides better accuracy in filtering, and subscribe only to the needed contracts to save computation resources.
        self._option.set_filter(lambda u: u.straddle(30))

    def on_data(self, data: Slice) -> None:
        if self.portfolio.invested:
            return
        # Only wants the option chain of the selected symbol.
        chain = list(data.option_chains.get(self._option))
        if chain:
            # There should only be 1 expiry and 1 strike from the 2 contracts returned, getting from either contract is fine.
            expiry, strike = chain[0].expiry, chain[0].strike
    
            # Forms a long straddle option strategy using abstraction method ensure accuracy.
            long_straddle = OptionStrategies.straddle(self._option, strike, expiry)
            self.buy(long_straddle, 1)