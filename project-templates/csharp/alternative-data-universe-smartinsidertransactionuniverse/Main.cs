using System.Linq;
using QuantConnect.Algorithm.Framework.Portfolio;
using QuantConnect.Data.UniverseSelection;
using QuantConnect.DataSource;

namespace QuantConnect.Algorithm.CSharp
{
    public class SmartInsiderTransactionUniverseAlgorithm : QCAlgorithm
    {
        private Universe _universe;

        public override void Initialize()
        {
            SetStartDate(2024, 9, 1);
            SetEndDate(2024, 12, 31);
            SetCash(100000);
            Settings.SeedInitialPrices = true;

            UniverseSettings.Resolution = Resolution.Daily;
            // Universe of large-cap US Equities executing meaningful share buybacks.
            _universe = AddUniverse<SmartInsiderTransactionUniverse>(data =>
            {
                // Keep $100M+ market-cap names buying back over 0.5% of shares.
                return from d in data.OfType<SmartInsiderTransactionUniverse>()
                       where d.BuybackPercentage > 0.005m && d.USDMarketCap > 100000000m
                       select d.Symbol;
            });

            // Rebalance shortly after the open so today's universe is locked in.
            Schedule.On(DateRules.EveryDay("SPY"), TimeRules.At(9, 0, 0), Rebalance);
        }

        private void Rebalance()
        {
            if (_universe.Selected.Count == 0)
            {
                return;
            }
            var securities = _universe.Selected.Where(s => Securities[s].Price > 0).ToList();
            if (securities.Count == 0)
            {
                return;
            }
            var weight = securities.Count >= 10 ? 1m / securities.Count : 0.1m;
            var targets = securities
                .Select(symbol => new PortfolioTarget(symbol, weight))
                .ToList();

            SetHoldings(targets, liquidateExistingHoldings: true);
        }
    }
}
