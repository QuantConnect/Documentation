# region imports
from AlgorithmImports import *
# endregion


class FutureOptionAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        # Filter the underlying continuous Futures to narrow the FOP spectrum.
        self._underlying = self.add_future(
            Futures.Indices.SP_500_E_MINI,
            extended_market_hours=True,
            data_mapping_mode=DataMappingMode.OPEN_INTEREST,
            data_normalization_mode=DataNormalizationMode.BACKWARDS_RATIO,
            contract_depth_offset=0
        )
        self._underlying.set_filter(0, 182)
        # Use CallSpread filter to obtain the 2 best-matched contracts that forms a call spread.
        # It simplifies from further filtering and reduce computation on redundant subscription.
        self.add_future_option(self._underlying, lambda u: u.call_spread(5, 5, -5))

    def on_data(self, data: Slice) -> None:
        if self.portfolio.invested:
            return
        # Create canonical symbol for the mapped future contract, since we need that to access the option chain.
        symbol = Symbol.create_canonical_option(self._underlying.mapped)
        # Get option chain data for the mapped future only.
        # It requires 2 contracts with different strikes to form a call spread, so we make sure at least 2 contracts are present.
        chain = data.option_chains.get(symbol)
        if not chain or len(list(chain)) < 2:
            return
        # Separate the contracts by strike, as we need to access their strike.
        expiry = min([x.expiry for x in chain])
        sorted_by_strike = sorted([x.strike for x in chain])
        itm_strike = sorted_by_strike[0]
        otm_strike = sorted_by_strike[-1]
        # Use abstraction method to order a bull call spread to avoid manual error.
        option_strategy = OptionStrategies.bull_call_spread(symbol, itm_strike, otm_strike, expiry)
        self.buy(option_strategy, 1)
