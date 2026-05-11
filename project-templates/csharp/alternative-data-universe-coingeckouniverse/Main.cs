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
            SetAccountCurrency("USD");
            SetTimeZone(TimeZones.Utc);
            Settings.MinimumOrderMarginPortfolioPercentage = 0;
            // Filter for crypto assets on Coinbase that are quoted in USD.
            _market = Market.Coinbase;
            _marketPairs = SymbolPropertiesDatabase.GetSymbolPropertiesList(_market)
                .Where(x => x.Value.QuoteCurrency == AccountCurrency)
                .Select(x => x.Key.Symbol)
                .ToList();
            UniverseSettings.Resolution = Resolution.Hour;
            // Add a CoinGecko universe to select the top 10 coins by market cap.
            _universe = AddUniverse<CoinGeckoUniverse>(
                // Filter coins that are quoted in account currency and available on the market.
                // Sort by market cap and return the top 10 as tradable Symbols.
                data => data
                    .OfType<CoinGecko>()
                    .Where(c => _marketPairs.Contains(c.Coin + AccountCurrency))
                    .OrderByDescending(c => c.MarketCap)
                    .Take(10)
                    .Select(c => c.CreateSymbol(_market, AccountCurrency))
            );
            // Schedule daily rebalancing at 9 AM UTC.
            Schedule.On(DateRules.EveryDay(), TimeRules.At(9, 0), Rebalance);
        }

        private void Rebalance()
        {
            // Create equal weight portfolio targets and liquidate any removed assets.
            if (_universe.Selected.Count == 0)
            {
                return;
            }
            // Filter for securities with valid prices.
            var securities = _universe.Selected
                .Where(symbol => Securities[symbol].Price > 0)
                .ToList();
            if (securities.Count == 0)
            {
                return;
            }
            var weight = 1m / securities.Count;
            var targets = securities
                .Select(symbol => new PortfolioTarget(symbol, weight))
                .ToList();
            SetHoldings(targets, true);
        }
    }
}
