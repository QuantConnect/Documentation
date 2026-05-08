# region imports
from AlgorithmImports import *
# endregion


class EquityOptionAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100_000)
        # Seed the price of each asset with its last known price to avoid trading errors.
        self.settings.seed_initial_prices = True
        # Set the data normalization mode as raw for option strike-price comparability.
        self._spy = self.add_equity("SPY", data_normalization_mode=DataNormalizationMode.RAW)
        self.schedule.on(
            self.date_rules.every(DayOfWeek.MONDAY),
            self.time_rules.after_market_open(self._spy, 1),
            self._buy_covered_call
        )

    def _buy_covered_call(self) -> None:
        '''Buy a Covered Call: Buy the underlying and sell the ATM call.
        '''
        next_friday = (self.time + timedelta(4)).date()
        chain = self.option_chain(self._spy)
        contracts = sorted([x for x in chain if x.right == OptionRight.CALL and x.expiry.date() == next_friday],
            key=lambda x: abs(self._spy.price - x.strike))
        # If we cannot find a contract expiring on Friday, it is likely a holiday.
        # For simplicity, we will not trade and close the underlying position, if any.
        if not contracts:
            self.liquidate(self._spy, tag=f"Cannot find ATM Call expiring next Friday {next_friday:%yMd}")
            return
        atm_call = contracts[0]
        contract = self.add_option_contract(atm_call)
        contract_multiplier = contract.contract_multiplier
        # We will invest 100% of the portfolio value observing the contract multiplier.
        # For example, if 100% of the portfolio value is 345 shares of SPY, we will invest 300.
        # Then, we sell 3 contracts of ATM call. If we are exercised, the position is closed.
        equity_order_quantity = np.floor(self.calculate_order_quantity(self._spy, 1)  / contract_multiplier) * contract_multiplier
        # If we are invested in the underlying, the hedge takes into account the final quantity.
        atm_call_order_quantity = -(self._spy.holdings.quantity + equity_order_quantity) / contract_multiplier
        if equity_order_quantity:
            self.market_order(self._spy, equity_order_quantity)
        self.market_order(atm_call, atm_call_order_quantity)
