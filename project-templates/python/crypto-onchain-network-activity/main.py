# region imports
from AlgorithmImports import *
# endregion

class CryptoOnChainNetworkActivityAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100000)
        # Trade BTCUSD on Coinbase.
        self._btc = self.add_crypto("BTCUSD", market=Market.COINBASE)
        # Subscribe to the Blockchain Bitcoin Metadata dataset for network-fundamentals signals.
        self._metadata = self.add_data(BitcoinMetadata, self._btc).symbol
        # 30-day moving average baselines of two distinct on-chain properties.
        self._tx_sma = SimpleMovingAverage(30)
        self._hash_sma = SimpleMovingAverage(30)
        # Warm up via history so the strategy can trade from the first bar.
        history = self.history[BitcoinMetadata](self._metadata, 30, Resolution.DAILY)
        for data in history:
            self._update_indicators(data)

    def _update_indicators(self, data: BitcoinMetadata) -> bool:
        # Update on-chain indicators with new data.
        return (
            self._tx_sma.update(data.end_time, data.numberof_transactions) &
            self._hash_sma.update(data.end_time, data.hash_rate)
        )

    def on_data(self, slice: Slice) -> None:
        # On-chain dataset publishes once per day; act only when fresh data arrives.
        data = slice.get(self._metadata)
        # Update both indicators with today's on-chain readings.
        if not (data and self._update_indicators(data)):
            return
        # Long when network demand (transactions) AND supply (hash rate) both expand.
        bullish = (
            data.numberof_transactions > self._tx_sma.current.value and
            data.hash_rate > self._hash_sma.current.value
        )
        if not self._btc.invested and bullish:
            self.set_holdings(self._btc, 1)
        elif self._btc.invested and not bullish:
            self.liquidate()
