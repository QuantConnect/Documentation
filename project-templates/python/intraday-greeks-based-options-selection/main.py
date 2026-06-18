# region imports
from AlgorithmImports import *
# endregion

class OptionChainFullExample(QCAlgorithm):
    _interest_rate_model = InterestRateProvider()
    _last_ticket: OrderTicket = None

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 9, 5)
        self.set_cash(500000)
        # automatic_indicator_warm_up only supports automatic indicators, not manual indicators.
        self.settings.automatic_indicator_warm_up = True
        self.universe_settings.minimum_time_in_universe = timedelta(0)

        # Warm-up the option contracts as soon as it is added to the algorithm
        self.settings.seed_initial_prices = True

        # The EMA/price cross will determine we trade ATM contracts
        self._index = self.add_index("RUT")
        self.ema(self._index, 60).updated += self._trade_target_delta_contract
        # Alternatively, use a manual indicator.
        # self._ema = ExponentialMovingAverage(60)
        # self.warm_up_indicator(self._index, self._ema)
        # self.register_indicator(self._index, self._ema)
        # self._ema.updated += self._trade_target_delta_contract

        self._option_chain_symbol = Symbol.create_canonical_option(self._index, "RUTW", Market.USA, "?RUTW")
        self._dividend_yield_model = DividendYieldProvider(self._index)

    def _trade_target_delta_contract(self, ema: ExponentialMovingAverage, current: IndicatorDataPoint) -> None:
        # Pace trades every 10 minutes
        last_trade_time = self._last_ticket.time if self._last_ticket else None
        if last_trade_time and (self.utc_time-last_trade_time).total_seconds() < 600: return

        if not ema.is_ready: return

        spot = self._index.price

        if spot > current.value and spot > ema[-1].value:
            atm_call = self._get_target_delta_contract(OptionRight.CALL, spot)
            if atm_call and not self.portfolio[atm_call].invested:
                self._last_ticket = self.market_order(atm_call, 1)

        if spot < current.value and spot < ema[-1].value:
            atm_put = self._get_target_delta_contract(OptionRight.PUT, spot)
            if atm_put and not self.portfolio[atm_put].invested:
                self._last_ticket = self.market_order(atm_put, 1)

    def _get_target_delta_contract(self, right: OptionRight, spot: float, target_delta: float = 0.4) -> Symbol | None:
        mirror_option_right = OptionRight.PUT if right == OptionRight.CALL else OptionRight.CALL
        chain = self.option_chain(self._option_chain_symbol)
        if not chain:
            return None
        expiry = min([x.expiry for x in chain])

        def get_delta(x: OptionContract) -> tuple[OptionContract, float]:
            mirror_option = Symbol.create_option(x.symbol.underlying, "RUT", Market.USA, OptionStyle.EUROPEAN, mirror_option_right, x.strike, x.expiry)
            delta = Delta(x, self._interest_rate_model, self._dividend_yield_model, mirror_option)
            self.warm_up_indicator([x.symbol, mirror_option, x.symbol.underlying], delta, Resolution.MINUTE)
            return x, abs(delta.current.value)

        contracts = sorted([x for x in chain if x.expiry == expiry and x.right == right],
            key=lambda x: abs(spot - x.strike))[:10]
        delta_by_contract = sorted([get_delta(x) for x in contracts],
            key=lambda x: abs(target_delta - x[1]))

        if not delta_by_contract:
            return None

        return self.add_option_contract(delta_by_contract[0][0])
