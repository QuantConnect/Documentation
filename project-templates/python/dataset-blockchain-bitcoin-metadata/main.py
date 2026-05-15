# region imports
from AlgorithmImports import *
# endregion


class BitcoinBlockTimeAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2023, 1, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100_000)
        # BTCUSD on Coinbase, minute resolution for fills.
        self._btc = self.add_crypto("BTCUSD", Resolution.MINUTE, Market.COINBASE)
        # Blockchain Bitcoin Metadata for daily on-chain network readings.
        self._metadata = self.add_data(BitcoinMetadata, self._btc.symbol, Resolution.DAILY).symbol
        # 30-day rate of change on median block time (network speed regime).
        self._roc = RateOfChange(30)
        # Stale-data guard: liquidate if the daily feed lags more than 3 days.
        self._last_metadata_time: datetime | None = None
        self._stale_threshold = timedelta(days=3)
        # Warm up the ROC from history so we can trade from the first bar.
        history = self.history[BitcoinMetadata](self._metadata, 30, Resolution.DAILY)
        for data in history:
            self._update(data)

    def _update(self, data: BitcoinMetadata) -> None:
        block_time = data.median_transaction_confirmation_time
        if block_time and block_time > 0:
            self._roc.update(data.end_time, block_time)
            self._last_metadata_time = data.end_time

    def on_data(self, slice: Slice) -> None:
        # Daily metadata feed: only react when a fresh reading arrives.
        data = slice.get(self._metadata)
        if data:
            self._update(data)
        if not self._roc.is_ready:
            return
        # Stop trading if the feed has gone stale (e.g. provider outage).
        if self._last_metadata_time is None or self.time - self._last_metadata_time > self._stale_threshold:
            self.liquidate(self._btc.symbol)
            return
        # Faster blocks (block time trending down) -> long; slower -> flat.
        if self._roc.current.value < 0:
            self.set_holdings(self._btc.symbol, 1.0)
        else:
            self.liquidate(self._btc.symbol)
