# region imports
from AlgorithmImports import *
# endregion

class OptionIronCondorSpyAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100000)
        self.settings.seed_initial_prices = True
        # Add the Option and define the chain filter.
        self._option = self.add_option("SPY")
        self._option.set_filter(lambda u: u.strikes(-10, 10).expiration(0, 7))
        # Sell weekly iron condors on Monday's open and close them by Friday's close.
        self.schedule.on(self.date_rules.every(DayOfWeek.MONDAY), self.time_rules.after_market_open(self._option, 1), self._open_iron_condor)
        self.schedule.on(self.date_rules.every(DayOfWeek.FRIDAY), self.time_rules.before_market_close(self._option, 5), self._close)

    def _open_iron_condor(self) -> None:
        # Target the upcoming Friday expiration.
        next_friday = (self.time + timedelta(4)).date()
        chain = [x for x in self.option_chain(self._option) if x.expiry.date() == next_friday]
        if not chain:
            return
        calls = sorted([x for x in chain if x.right == OptionRight.CALL], key=lambda x: x.strike)
        puts = sorted([x for x in chain if x.right == OptionRight.PUT], key=lambda x: x.strike)
        if len(calls) < 4 or len(puts) < 4:
            return
        # Short legs nearest 10-delta on each side; calls have positive delta, puts negative.
        short_call = min(calls, key=lambda x: abs(x.greeks.delta - 0.10))
        short_put = min(puts, key=lambda x: abs(x.greeks.delta + 0.10))
        # Long wings sit 3 strikes further out from each short.
        call_strikes = sorted({c.strike for c in calls})
        put_strikes = sorted({p.strike for p in puts})
        sc_idx = call_strikes.index(short_call.strike)
        sp_idx = put_strikes.index(short_put.strike)
        if sc_idx + 3 >= len(call_strikes) or sp_idx - 3 < 0:
            return
        long_call = next(c for c in calls if c.strike == call_strikes[sc_idx + 3])
        long_put = next(p for p in puts if p.strike == put_strikes[sp_idx - 3])
        # Skip if any leg is missing a quote we can trade against.
        if not all(c.bid_price and c.ask_price for c in [short_call, long_call, short_put, long_put]):
            return
        # Use OptionStrategies helper to create the iron condor
        option_strategy = OptionStrategies.iron_condor(
            self._option,
            long_put.strike,
            short_put.strike,
            short_call.strike,
            long_call.strike,
            next_friday
        )
        self.buy(option_strategy, 1)

    def _close(self) -> None:
        # Close any open legs (or assigned underlying) at expiry.
        self.liquidate()
