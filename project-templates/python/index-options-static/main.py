# region imports
from AlgorithmImports import *
# endregion

class OptionChainFullExample(QCAlgorithm):
    _last_ticket: OrderTicket = None

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 9, 5)
        self.set_cash(500000)
        self.settings.automatic_indicator_warm_up = True
        self.universe_settings.minimum_time_in_universe = timedelta(0)

        # Warm-up the option contracts as soon as it is added to the algorithm
        self.settings.seed_initial_prices = True

        # The EMA/price cross will determine we trade ATM contracts 
        index = self.add_index("SPX")
        self.ema(index, 60).updated += self._trade_at_the_money_contract

        self._option_chain_symbol = Symbol.create_canonical_option(index, "SPXW", Market.USA, "?SPXW")

    def _trade_at_the_money_contract(self, ema: ExponentialMovingAverage, current: IndicatorDataPoint) -> None:
        # Pace trades every 10 minutes
        last_trade_time = self._last_ticket.time if self._last_ticket else None
        if last_trade_time and (self.utc_time-last_trade_time).total_seconds() < 600: return

        if not ema.is_ready: return

        spot = self.securities[current.symbol].price
        
        if spot > current.value and spot > ema[-1].value:
            atm_call = self._get_at_the_money_contract(OptionRight.CALL, spot)
            if atm_call and not atm_call.invested:
                self._last_ticket = self.market_order(atm_call, 1)

        if spot < current.value and spot < ema[-1].value:
            atm_put = self._get_at_the_money_contract(OptionRight.PUT, spot)
            if atm_put and not atm_put.invested:
                self._last_ticket = self.market_order(atm_put, 1)

    def _get_at_the_money_contract(self, right: OptionRight, spot: float) -> Option | None:
        chain = self.option_chain(self._option_chain_symbol)
        expiry = min([x.expiry for x in chain])
        contracts = sorted([x for x in chain if x.expiry == expiry and x.right == right],
            key=lambda x: abs(spot - x.strike))

        if not contracts:
            return None

        return self.add_option_contract(contracts[0])
