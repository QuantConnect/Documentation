using System.Linq;
using QuantConnect.Algorithm.Framework.Portfolio;
using QuantConnect.Data.UniverseSelection;
using QuantConnect.DataSource;

namespace QuantConnect.Algorithm.CSharp
{
    public class SmartInsiderIntentionUniverseAlgorithm : QCAlgorithm
    {
        private Universe _universe;

        public override void Initialize()
        {
            SetStartDate(2024, 9, 1);
            SetEndDate(2024, 12, 31);
            SetCash(100000);
            Settings.SeedInitialPrices = true;

            UniverseSettings.Resolution = Resolution.Minute;
            // Universe of large-cap US Equities announcing meaningful buyback intentions.
            _universe = AddUniverse<SmartInsiderIntentionUniverse>(data =>
            {
                // Keep $100M+ market-cap names announcing a buyback over 0.5% of shares.
                return from d in data.OfType<SmartInsiderIntentionUniverse>()
                       where d.Percentage > 0.005m && d.USDMarketCap > 100000000m
                       select d.Symbol;
            });

            // Rebalance before market open to trade today's universe.
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
