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
        private List<Symbol> _fundamental = [];

        public override void Initialize()
        {
            SetStartDate(2024, 9, 1);
            SetEndDate(2024, 12, 31);
            SetCash(100000);
            Settings.SeedInitialPrices = true;
            // Add a fundamental universe to track the most liquid US equities by dollar volume.
            AddUniverse(fundamental =>
            {
                // Store the top 50 symbols by dollar volume for use in the alt-data universe.
                _fundamental = fundamental
                    .OrderBy(f => f.DollarVolume)
                    .TakeLast(50)
                    .Select(f => f.Symbol)
                    .ToList();
                return Universe.Unchanged;
            });
            // Add a Brain Stock Ranking universe, restricted to high-ranking names within the fundamental list.
            _universe = AddUniverse<BrainStockRankingUniverse>(
                data => data
                    .OfType<BrainStockRankingUniverse>()
                    .Where(d => d.Rank2Days > 0m && d.Rank3Days > 0m && d.Rank5Days > 0m)
                    .Select(d => d.Symbol)
                    .Where(symbol => _fundamental.Contains(symbol))
            );
            // Schedule daily rebalancing at 9:00 AM after market open.
            Schedule.On(DateRules.EveryDay("SPY"), TimeRules.At(9, 0), Rebalance);
        }

        private void Rebalance()
        {
            if (_universe.Selected.Count == 0)
            {
                return;
            }
            // Calculate equal weights for all selected securities with valid prices.
            var weight = 1m / _universe.Selected.Count;
            var targets = _universe.Selected
                .Where(symbol => Securities[symbol].Price > 0)
                .Select(symbol => new PortfolioTarget(symbol, weight))
                .ToList();
            SetHoldings(targets, true);
        }
    }
}
