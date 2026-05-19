# region imports
from AlgorithmImports import *
# endregion

class CoinGeckoUniverseAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100000)
        self.set_account_currency("USD")

        # Trade the largest CoinGecko coins on Coinbase quoted in USD.
        self._market = Market.COINBASE
        self._market_pairs = [
            x.key.symbol
            for x in self.symbol_properties_database.get_symbol_properties_list(self._market)
            if x.value.quote_currency == self.account_currency
        ]

        self.universe_settings.resolution = Resolution.DAILY
        # Universe of the top 10 CoinGecko coins by market cap that we can trade.
        self._universe = self.add_universe(CoinGeckoUniverse, "CoinGeckoUniverse", Resolution.DAILY, self._select_assets)

        # Rebalance daily on US trading days, after CoinGecko refreshes overnight.
        self.schedule.on(self.date_rules.every_day("SPY"), self.time_rules.at(9, 0, 0), self._rebalance)

    def _select_assets(self, data: List[CoinGecko]) -> List[Symbol]:
        # Keep coins quoted in our account currency on the chosen brokerage.
        tradable = [d for d in data
                    if d.coin and (d.coin + self.account_currency) in self._market_pairs]
        # Take the 10 largest by market cap and create their tradable Symbols.
        return [c.create_symbol(self._market, self.account_currency)
                for c in sorted(tradable, key=lambda x: x.market_cap or 0)[-10:]]

    def _rebalance(self) -> None:
        if not self._universe.selected:
            return

        weight = 1 / len(self._universe.selected)
        targets = [PortfolioTarget(symbol, weight) for symbol in self._universe.selected]

        self.set_holdings(targets, liquidate_existing_holdings=True)
