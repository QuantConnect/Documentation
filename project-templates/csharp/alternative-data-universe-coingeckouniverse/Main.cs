using System.Collections.Generic;
using System.Linq;
using QuantConnect.Algorithm.Framework.Portfolio;
using QuantConnect.Data.UniverseSelection;
using QuantConnect.DataSource;

namespace QuantConnect.Algorithm.CSharp
{
    public class CoinGeckoUniverseAlgorithm : QCAlgorithm
    {
        private Universe _universe;
        private string _market;
        private List<string> _marketPairs;

        public override void Initialize()
        {
            SetStartDate(2024, 9, 1);
            SetEndDate(2024, 12, 31);
            SetCash(100000);
            Settings.SeedInitialPrices = true;
            SetAccountCurrency("USD");

            // Trade the largest CoinGecko coins on Coinbase quoted in USD.
            _market = Market.Coinbase;
            _marketPairs = SymbolPropertiesDatabase.GetSymbolPropertiesList(_market)
                .Where(x => x.Value.QuoteCurrency == AccountCurrency)
                .Select(x => x.Key.Symbol)
                .ToList();

            UniverseSettings.Resolution = Resolution.Minute;
            // Universe of the top 10 CoinGecko coins by market cap that we can trade.
            _universe = AddUniverse<CoinGecko>("CoinGeckoUniverse", Resolution.Daily, data =>
            {
                return data
                    .OfType<CoinGecko>()
                    // Keep coins quoted in our account currency on the chosen brokerage.
                    .Where(c => _marketPairs.Contains(c.Coin + AccountCurrency))
                    // Take the 10 largest by market cap and create their tradable Symbols.
                    .OrderByDescending(c => c.MarketCap)
                    .Take(10)
                    .Select(c => c.CreateSymbol(_market, AccountCurrency));
            });

            // Rebalance daily on US trading days, after CoinGecko refreshes overnight.
            Schedule.On(DateRules.EveryDay("SPY"), TimeRules.At(9, 0, 0), Rebalance);
        }

        private void Rebalance()
        {
            if (_universe.Selected.Count == 0)
            {
                return;
            }

            var weight = 1m / _universe.Selected.Count;
            var targets = _universe.Selected
                .Select(symbol => new PortfolioTarget(symbol, weight))
                .ToList();

            SetHoldings(targets, true);
        }
    }
}
