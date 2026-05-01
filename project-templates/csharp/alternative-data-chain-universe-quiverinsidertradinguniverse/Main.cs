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
        private List<Symbol> _fundamental = new();
        private Universe _universe;

        public override void Initialize()
        {
            SetStartDate(2024, 9, 1);
            SetEndDate(2024, 12, 31);
            SetCash(100000);

            UniverseSettings.Resolution = Resolution.Daily;
            // First universe: top 100 US Equities by dollar volume; emits Universe.Unchanged.
            AddUniverse(fundamental =>
            {
                _fundamental = (from c in fundamental
                                orderby c.DollarVolume descending
                                select c.Symbol).Take(100).ToList();
                return Universe.Unchanged;
            });
            // Second universe: 10 largest insider-trading dollar volumes, intersected with the fundamental list.
            _universe = AddUniverse<QuiverInsiderTradingUniverse>(altCoarse =>
            {
                // Aggregate insider dollar volume per ticker and keep the 10 largest.
                var dollarVolume = new Dictionary<Symbol, decimal>();
                foreach (var d in altCoarse.OfType<QuiverInsiderTradingUniverse>())
                {
                    if (d.PricePerShare == null || d.PricePerShare == 0m)
                    {
                        continue;
                    }
                    if (!dollarVolume.ContainsKey(d.Symbol))
                    {
                        dollarVolume[d.Symbol] = 0m;
                    }
                    dollarVolume[d.Symbol] += (d.Shares ?? 0m) * d.PricePerShare.Value;
                }
                var alt = dollarVolume
                    .OrderByDescending(kvp => kvp.Value)
                    .Take(10)
                    .Select(kvp => kvp.Key);
                return _fundamental.Intersect(alt);
            });

            // Rebalance shortly after the open so today's intersection is locked in.
            Schedule.On(DateRules.EveryDay("SPY"), TimeRules.At(9, 0, 0), Rebalance);
        }

        private void Rebalance()
        {
            if (_universe.Selected == null || _universe.Selected.Count == 0)
            {
                return;
            }

            var weight = 1m / _universe.Selected.Count;
            var targets = _universe.Selected
                .Select(symbol => new PortfolioTarget(symbol, weight))
                .ToList();

            SetHoldings(targets, liquidateExistingHoldings: true);
        }
    }
}
