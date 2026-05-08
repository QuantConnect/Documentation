# region imports
from AlgorithmImports import *
# endregion

CONTENT = """
2020-01-20,FB,100
2020-01-20,MSFT,200
2020-01-20,NVDA,300
2024-09-03,META,-100
2024-09-03,MSFT,-200
2024-09-03,NVDA,-300"""


class CustomDataTradeProviderAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2020, 1, 1)
        self.set_end_date(2024, 12, 31)
        # Save the data to the object store.
        if not self.object_store.contains_key("selected_trades.csv"):
            self.object_store.save("selected_trades.csv", CONTENT)
        self.settings.seed_initial_prices = True
        self.add_data(SelectedTrades, "X")

    def on_data(self, data: Slice) -> None:
        for symbol, data in data.get(SelectedTrades).items():
            if not symbol in self.securities:
                self.add_security(symbol)
            self.market_order(symbol, data.quantity)


class SelectedTrades(PythonData):

    def get_source(self, config: SubscriptionDataConfig, date: datetime, is_live_mode: bool) -> SubscriptionDataSource:
        return SubscriptionDataSource("selected_trades.csv", SubscriptionTransportMedium.OBJECT_STORE)

    def reader(self, config: SubscriptionDataConfig, line: str, date: datetime, is_live_mode: bool) -> BaseData:
        if not line.strip():
            return None
        data = [x.strip() for x in line.split(',')]
        try:
            ticker = data[1]
            time = datetime.strptime(data[0], "%Y-%m-%d")
            # Create the SecurityIdentifier with the point-in-time ticker and the current date.
            # This example uses META, which traded under the ticker FB in 2020.
            # From 2024 onwards, the symbol resolves to META.
            security_id = SecurityIdentifier.generate_equity(ticker, Market.USA, mapping_resolve_date=time)
            if security_id.date.year < 1998:
                # Ticker not found in QuantConnect database on this date.
                return None
            trade = SelectedTrades()
            trade.symbol = Symbol(security_id, ticker)
            trade.end_time = time
            trade.time = time - timedelta(1)
            trade.quantity = float(data[2])
            return trade
        except:
            pass
        return None
