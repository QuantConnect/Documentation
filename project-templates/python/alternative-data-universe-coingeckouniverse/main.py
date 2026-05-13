# region imports
from AlgorithmImports import *
# endregion


class CoinGeckoUniverseAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100_000)
        self.set_account_currency("USD")
        self.set_time_zone(TimeZones.UTC)
        self.settings.minimum_order_margin_portfolio_percentage = 0
        # Filter for crypto assets on Coinbase that are quoted in USD.
        self._market = Market.COINBASE
        self._market_pairs = [
            x.key.symbol
            for x in self.symbol_properties_database.get_symbol_properties_list(self._market)
            if x.value.quote_currency == self.account_currency
        ]
        self.universe_settings.resolution = Resolution.HOUR
        # Add a CoinGecko universe to select the top 10 coins by market cap.
        self._universe = self.add_universe(CoinGeckoUniverse, "CoinGeckoUniverse", Resolution.DAILY, self._select_assets)
        # Schedule daily rebalancing at 9 AM UTC.
        self.schedule.on(
            self.date_rules.every_day(), 
            self.time_rules.at(9, 0), 
            self._rebalance
        )

    def _select_assets(self, data: List[CoinGecko]) -> List[Symbol]:
        # Filter coins that are quoted in account currency and available on the market.
        tradable = [d for d in data if d.coin and (d.coin + self.account_currency) in self._market_pairs]
        # Sort by market cap and return the top 10 as tradable Symbols.
        return [f.create_symbol(self._market, self.account_currency)
                for f in sorted(tradable, key=lambda f: f.market_cap)[-10:]]

    def _rebalance(self) -> None:
        # Create equal weight portfolio targets and liquidate any removed assets.
        if not self._universe.selected:
            return
        # Filter for securities with valid prices.
        securities = [s for s in self._universe.selected if self.securities[s].price]
        if not securities:
            return
        weight = 1 / len(securities)
        targets = [PortfolioTarget(symbol, weight) for symbol in securities]
        self.set_holdings(targets, True)
