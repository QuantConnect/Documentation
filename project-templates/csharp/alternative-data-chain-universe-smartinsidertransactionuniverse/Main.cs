using System.Collections.Generic;
using System.Linq;
using QuantConnect.Algorithm.Framework.Portfolio;
using QuantConnect.Data.Fundamental;
using QuantConnect.Data.UniverseSelection;
using QuantConnect.DataSource;

namespace QuantConnect.Algorithm.CSharp
{
    public class SmartInsiderTransactionChainedUniverseAlgorithm : QCAlgorithm
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
            // Second universe: $100M+ market-cap buybacks over 0.5%, intersected with the fundamental list.
            _universe = AddUniverse<SmartInsiderTransactionUniverse>(altCoarse =>
            {
                // Keep $100M+ market-cap names buying back over 0.5% of shares.
                var alt = from d in altCoarse.OfType<SmartInsiderTransactionUniverse>()
                          where d.BuybackPercentage > 0.005m && d.USDMarketCap > 100000000m
                          select d.Symbol;
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
