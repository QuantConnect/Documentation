using System.Collections.Generic;
using System.Linq;
using QuantConnect.Algorithm.Framework.Portfolio;
using QuantConnect.Data.Fundamental;
using QuantConnect.Data.UniverseSelection;
using QuantConnect.DataSource;

namespace QuantConnect.Algorithm.CSharp
{
    public class BrainStockRankingChainedUniverseAlgorithm : QCAlgorithm
    {
        private List<Fundamental> _fundamental = [];
        private Universe _universe;

        public override void Initialize()
        {
            SetStartDate(2024, 9, 1);
            SetEndDate(2024, 12, 31);
            SetCash(100000);
            Settings.SeedInitialPrices = true;
            UniverseSettings.Resolution = Resolution.Minute;
            // First universe: store all US Equity fundamentals; emits Universe.Unchanged.
            AddUniverse(fundamental =>
            {
                _fundamental = [..fundamental];
                return Universe.Unchanged;
            });
            // Second universe: positive Brain ML rankings across 2-, 3-, and 5-day horizons, ranked by dollar volume.
            _universe = AddUniverse<BrainStockRankingUniverse>(altCoarse =>
            {
                // Keep names with consistent positive momentum across all three horizons.
                var alt = altCoarse.OfType<BrainStockRankingUniverse>()
                    .Where(d => d.Rank2Days > 0m && d.Rank3Days > 0m && d.Rank5Days > 0m)
                    .Select(d => d.Symbol)
                    .ToHashSet();
                Plot("Universe", "Raw", alt.Count);
                return _fundamental
                    .Where(c => alt.Contains(c.Symbol))
                    .OrderByDescending(c => c.DollarVolume)
                    .Select(c => c.Symbol)
                    .Take(100);
            });
            // Rebalance before market open to trade today's intersection.
            Schedule.On(DateRules.EveryDay("SPY"), TimeRules.At(9, 0, 0), Rebalance);
        }

        private void Rebalance()
        {
            if (_universe.Selected.Count == 0)
            {
                return;
            }
            var weight = _universe.Selected.Count >= 10 ? 1m / _universe.Selected.Count : 0.1m;
            var targets = _universe.Selected
                .Select(symbol => new PortfolioTarget(symbol, weight))
                .ToList();
            SetHoldings(targets, true);
        }
    }
}
