# region imports
from AlgorithmImports import *
# endregion


class IndexOptionAlgorithm(QCAlgorithm):
    _chain: list[OptionContract] = []
    _min_strike = -1
    _max_strike = 1

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(500_000)
        self.universe_settings.minimum_time_in_universe = timedelta(0)
        # Warm-up the option contracts as soon as it is added to the algorithm.
        self.settings.seed_initial_prices = True
        # Create the option chain symbol for the SPXW weekly index option.
        index = self.add_index("SPX")
        self._option_chain_symbol = Symbol.create_canonical_option(index, "SPXW", Market.USA, "?SPXW")
        # Populate the updated option chain immediately to trade with.
        self._populate_option_chain()
        date_rule = self.date_rules.every_day(self._option_chain_symbol)
        # Set a schedule event to populate the option chain when the market opens since the option contracts are updated daily.
        self.schedule.on(
            date_rule,
            self.time_rules.after_market_open(self._option_chain_symbol, 1),
            self._populate_option_chain
        )
        # Set a scheduled event to filter the closed ATM calls every 5 minutes.
        self.schedule.on(
            date_rule,
            self.time_rules.every(timedelta(minutes=5)),
            self._filter
        )

    def _populate_option_chain(self) -> None:
        # Filter the expiry daily only since the contract list is updated daily.
        chain = self.option_chain(self._option_chain_symbol)
        expiry = min([x.expiry for x in chain])
        self._chain = [x for x in chain if x.expiry==expiry]

    def _filter(self) -> None:
        if not self._chain:
            return
        underlying = self.securities[self._option_chain_symbol.underlying]
        # Filter the contracts with strike range spread between the preset level.
        strikes = sorted(set([x.strike for x in self._chain]))
        spot = underlying.price
        atm = sorted(strikes, key=lambda x: abs(spot - x))[0]
        index = strikes.index(atm)
        min_strike = strikes[max(0, index + self._min_strike)]
        max_strike = strikes[min(len(strikes) - 1, index + self._max_strike)]
        contracts = [x for x in self._chain if min_strike <= x.strike and x.strike <= max_strike]
        # Request data of the newly identified ATM contracts.
        for contract in contracts:
            if contract not in self.securities:
                self.add_option_contract(contract)
        # Since we are trading 0DTE, they will expire on end of day.
        # So we don't need to remove them explicitly.

    def on_data(self, data: Slice) -> None:
        # Only trade on updated data.
        chain = data.option_chains.get(self._option_chain_symbol)
        if not chain:
            return
        # Filter the closest ATM call contract and trade.
        expiry = min([x.expiry for x in chain])
        calls = [x for x in chain if x.expiry == expiry and x.right == OptionRight.CALL and self.securities[x].is_tradable]
        if not calls:
            return
        atm_call = sorted(calls, key=lambda x: abs(chain.underlying.price - x.strike))[0]
        # We will buy the ATM call if we don't have it.
        # We are not selling the calls we have purchased previously.
        # So, we will buy a lot of contracts if underlying price moves a lot.
        if not self.portfolio[atm_call].invested:
            self.market_order(atm_call, 1)
