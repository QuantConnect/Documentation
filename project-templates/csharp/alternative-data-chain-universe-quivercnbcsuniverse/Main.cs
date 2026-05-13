using System.Collections.Generic;
using System.Linq;
using QuantConnect.Algorithm.Framework.Portfolio;
using QuantConnect.Data.Fundamental;
using QuantConnect.Data.UniverseSelection;
using QuantConnect.DataSource;
using QuantConnect.Orders;

namespace QuantConnect.Algorithm.CSharp
{
    public class QuiverCNBCsChainedUniverseAlgorithm : QCAlgorithm
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
            // First universe: top 100 US Equities by dollar volume; emits Universe.Unchanged.
            AddUniverse(fundamental =>
            {
                _fundamental = [..fundamental];
                return Universe.Unchanged;
            });
            // Second universe: 3+ BUY CNBC opinions, intersected with the fundamental list.
            _universe = AddUniverse<QuiverCNBCsUniverse>(altCoarse =>
            {
                // Group raw CNBC opinions by ticker and keep names with 2+ BUY recommendations.
                var alt = altCoarse.OfType<QuiverCNBCsUniverse>()
                    .GroupBy(x => x.Symbol)
                    .Where(g => g.Count(x => x.Direction == OrderDirection.Buy) >= 2)
                    .Select(g => g.Key)
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
