using System.Collections.Generic;
using System.Linq;
using QuantConnect.Algorithm.Framework.Portfolio;
using QuantConnect.Data.UniverseSelection;
using QuantConnect.DataSource;

namespace QuantConnect.Algorithm.CSharp
{
    public class BrainStockRankingUniverseAlgorithm : QCAlgorithm
    {
        private Universe _universe;

        public override void Initialize()
        {
            SetStartDate(2024, 9, 1);
            SetEndDate(2024, 12, 31);
            SetCash(100000);
            Settings.SeedInitialPrices = true;
            // Add a Brain Stock Ranking universe, restricted to high-ranking names with strong multi-horizon signals.
            _universe = AddUniverse<BrainStockRankingUniverse>(
                // Filter for stocks with positive rankings across 2-day, 3-day, and 5-day horizons.
                data => data
                    .OfType<BrainStockRankingUniverse>()
                    .Where(d => d.Rank2Days > 0.05m && d.Rank3Days > 0.05m && d.Rank5Days > 0.05m)
                    .Select(d => d.Symbol)
            );
            // Schedule daily rebalancing at 9:00 AM before market open.
            Schedule.On(DateRules.EveryDay("SPY"), TimeRules.At(9, 0), Rebalance);
        }

        private void Rebalance()
        {
            if (_universe.Selected.Count == 0)
            {
                return;
            }
            // Calculate equal weights for all selected securities.
            var weight = 1m / _universe.Selected.Count;
            var targets = _universe.Selected
                .Select(symbol => new PortfolioTarget(symbol, weight))
                .ToList();
            SetHoldings(targets, true);
        }
    }
}
