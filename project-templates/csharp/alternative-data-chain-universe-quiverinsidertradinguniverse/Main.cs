using System.Collections.Generic;
using System.Linq;
using QuantConnect.Algorithm.Framework.Portfolio;
using QuantConnect.Data.Fundamental;
using QuantConnect.Data.UniverseSelection;
using QuantConnect.DataSource;

namespace QuantConnect.Algorithm.CSharp
{
    public class QuiverInsiderTradingChainedUniverseAlgorithm : QCAlgorithm
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
            // Second universe: 10 largest insider-trading dollar volumes, ranked by dollar volume.
            _universe = AddUniverse<QuiverInsiderTradingUniverse>(altCoarse =>
            {
                // Aggregate insider dollar volume per ticker and keep the 10 largest.
                var alt = altCoarse.OfType<QuiverInsiderTradingUniverse>()
                    .Where(d => d.PricePerShare.HasValue && d.PricePerShare.Value != 0m)
                    .GroupBy(d => d.Symbol)
                    .Select(g => new { Symbol = g.Key, DollarVolume = g.Sum(d => (d.Shares ?? 0m) * d.PricePerShare.Value) })
                    .OrderByDescending(x => x.DollarVolume)
                    .Take(10)
                    .Select(x => x.Symbol)
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

            var weight = 1m / _universe.Selected.Count;
            var targets = _universe.Selected
                .Select(symbol => new PortfolioTarget(symbol, weight))
                .ToList();

            SetHoldings(targets, true);
        }
    }
}
