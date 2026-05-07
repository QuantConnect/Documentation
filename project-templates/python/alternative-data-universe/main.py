# region imports
from AlgorithmImports import *
# endregion

class UpcomingEarningsExampleAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 12, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100000)

        # Seed the last price as price since we need to use the underlying price for option contract filtering when it join the universe.
        self.settings.seed_initial_prices = True

        # Trade on daily basis based on daily upcoming earnings signals.
        self.universe_settings.resolution = Resolution.DAILY
        # Option trading requires raw price for strike price comparison.
        self.universe_settings.data_normalization_mode = DataNormalizationMode.RAW
        # Universe consists of equities with upcoming earnings events.
        self.add_universe(EODHDUpcomingEarnings, self._selection)

    def _selection(self, earnings: List[EODHDUpcomingEarnings]) -> List[Symbol]:
        # We do not want to lock our fund too early, so filter for stocks is in lower volatility but will go up.
        # Assuming 5 days before the earnings report publish is less volatile.
        # We do not want depository due to their low liquidity.
        for i in range(5, 0, -1):
            target_date = self.time + timedelta(i)
            symbols = set(d.symbol for d in earnings if d.report_date == target_date)
            if symbols:
                self.plot('Universe', 'Count', len(symbols))
                return list(symbols)
        return []

    def on_securities_changed(self, changes: SecurityChanges) -> None:
        # Actions only based on the equity universe changes.
        for security in changes.added_securities:
            if security.type != SecurityType.EQUITY:
                continue
            # Select the option contracts to construct a straddle to trade the volatility.
            call, put = self._select_option_contracts(security)
            if not call or not put:
                security.options = []
                continue
            security.options = (call, put)
            # Request the option contract data for trading.
            call = self.add_option_contract(call)
            put = self.add_option_contract(put)
            # Long a straddle by buying the selected ATM call and put.
            self.combo_market_order([
                    Leg.create(call, 1),
                    Leg.create(put, 1)
                ],
                1
            )

        # Actions only based on the equity universe changes.
        for security in changes.removed_securities:
            if security.type != SecurityType.EQUITY:
                continue
            # Liquidate any assigned position.
            self.liquidate(security)
            # Liquidate the option positions and capitalize the volatility 1-day after the earnings announcement.
            for contract in security.options:
                self.remove_option_contract(contract)

    def _select_option_contracts(self, underlying: Equity) -> tuple[Any, Any]:
        # Get all tradable option contracts for filtering.
        option_contract_list = self.option_chain(underlying)

        # Expiry at least 30 days later to have a smaller theta to reduce time decay loss.
        # Yet also be ensure liquidity over the volatility fluctuation hence we take the closet expiry after that.
        long_expiries = [x for x in option_contract_list if x.expiry >= self.time + timedelta(30)]
        if len(long_expiries) < 2:
            return None, None
        expiry = min(x.expiry for x in long_expiries)
        filtered_contracts = [x for x in option_contract_list if x.expiry == expiry]

        # Select ATM call and put to form a straddle for trading the volatility.
        strike = sorted(filtered_contracts, 
            key=lambda x: abs(x.strike - underlying.price))[0].strike
        atm_contracts = [x for x in filtered_contracts if x.strike == strike]
        if len(atm_contracts) < 2:
            return None, None
        
        atm_call = next(filter(lambda x: x.right == OptionRight.CALL, atm_contracts))
        atm_put = next(filter(lambda x: x.right == OptionRight.PUT, atm_contracts))
        return atm_call, atm_put
