# region imports
from AlgorithmImports import *
# endregion

class BasicFutureAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.universe_settings.asynchronous = True
        self._future = self.add_future(
            Futures.Indices.SP_500_E_MINI,
            extended_market_hours=True,
            data_mapping_mode=DataMappingMode.LAST_TRADING_DAY,
            data_normalization_mode=DataNormalizationMode.BACKWARDS_RATIO,
            contract_depth_offset=0
        )
        self._future.set_filter(0, 92)

    def on_data(self, data: Slice) -> None:
        if self.portfolio.invested:
            return
        self.market_order(self._future.mapped, 1)

    def on_symbol_changed_events(self, symbol_changed_events: SymbolChangedEvents) -> None:
        '''Track events when security changes its ticker, allowing the algorithm to adapt to these changes.'''
        for symbol, changed_event in symbol_changed_events.items():
            old_symbol = self.symbol(changed_event.old_symbol)
            new_symbol = self.symbol(changed_event.new_symbol)
            quantity = self.portfolio[old_symbol].quantity

            # Rolling over: to liquidate any position of the old mapped contract and switch to the newly mapped contract
            tag = f"Rollover - Symbol changed at {self.time}: {old_symbol.value} -> {new_symbol.value}"
            self.liquidate(old_symbol, tag=tag)
            if quantity: self.market_order(new_symbol, quantity, tag=tag)
