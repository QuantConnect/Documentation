# region imports
from AlgorithmImports import *
# endregion


class CustomDataBitstampAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2020, 9, 1)
        self.set_end_date(2020, 12, 31)
        self.set_cash(100_000)
        self.settings.seed_initial_prices = True
        # Define the "type" of our generic data:
        self._btc = self.add_data(Bitstamp, "BTC")
        # Get some historical data.
        history = self.history(Bitstamp, self._btc, 200, Resolution.DAILY)

    def on_data(self, data: Slice) -> None:
        # Get the data of the current day.
        data = data.get(self._btc)
        if not data:
            return
        self.plot(self._btc.symbol.value, 'Price', data.close)


class Bitstamp(PythonData):

    def get_source(self, config: SubscriptionDataConfig, date: datetime, is_live_mode: bool) -> SubscriptionDataSource:
        if is_live_mode:
            return SubscriptionDataSource('https://www.bitstamp.net/api/ticker/', SubscriptionTransportMedium.REST)
        return SubscriptionDataSource(
            "https://raw.githubusercontent.com/QuantConnect/Documentation/master/Resources/datasets/custom-data/bitstampusd.csv",
            SubscriptionTransportMedium.REMOTE_FILE
        )

    def reader(self, config: SubscriptionDataConfig, line: str, date: datetime, is_live_mode: bool) -> BaseData:
        if not line.strip():
            return None
        coin = Bitstamp()
        coin.symbol = config.symbol
        # In live trading, parse the JSON file.
        if is_live_mode:
            # Example Line Format:
            # {"high": "441.00", "last": "421.86", "timestamp": "1411606877", "bid": "421.96", "vwap": "428.58", "volume": "14120.40683975", "low": "418.83", "ask": "421.99"}.
            live_btc = json.loads(line)
            # If value is zero, return None.
            coin.value = float(live_btc["last"])
            if coin.value == 0:
                return None
            coin.end_time =  Extensions.convert_from_utc(
                datetime.utcnow(), config.exchange_time_zone
            )
            coin.time = coin.end_time - timedelta(1)
            coin["Open"] = float(live_btc["open"])
            coin["High"] = float(live_btc["high"])
            coin["Low"] = float(live_btc["low"])
            coin["Close"] = coin.value
            coin["Ask"] = float(live_btc["ask"])
            coin["Bid"] = float(live_btc["bid"])
            coin["VolumeBTC"] = float(live_btc["volume"])
            coin["WeightedPrice"] = float(live_btc["vwap"])
            return coin
        # In backtests, parse the CSV file.
        # Example Line Format:
        # Date      Open   High    Low     Close   Volume (BTC)    Volume (Currency)   Weighted Price.
        # 2011-09-13 5.8    6.0     5.65    5.97    58.37138238,    346.0973893944      5.929230648356.
        if not line[0].isdigit():
            return None
        data = line.split(',')
        # If value is zero, return None.
        coin.value = float(data[4])
        if coin.value == 0:
            return None
        coin.time = datetime.strptime(data[0], "%Y-%m-%d")
        coin.end_time = coin.time + timedelta(1)
        coin["Open"] = float(data[1])
        coin["High"] = float(data[2])
        coin["Low"] = float(data[3])
        coin["Close"] = coin.value
        coin["VolumeBTC"] = float(data[5])
        coin["VolumeUSD"] = float(data[6])
        coin["WeightedPrice"] = float(data[7])
        return coin
