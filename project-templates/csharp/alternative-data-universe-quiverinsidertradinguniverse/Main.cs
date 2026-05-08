using System.Collections.Generic;
using System.Linq;
using QuantConnect.Algorithm.Framework.Portfolio;
using QuantConnect.Data.UniverseSelection;
using QuantConnect.DataSource;

namespace QuantConnect.Algorithm.CSharp
{
    public class QuiverInsiderTradingUniverseAlgorithm : QCAlgorithm
    {
        private Universe _universe;

        public override void Initialize()
        {
            SetStartDate(2024, 9, 1);
            SetEndDate(2024, 12, 31);
            SetCash(100000);
            Settings.SeedInitialPrices = true;

            UniverseSettings.Resolution = Resolution.Minute;
            // Universe of the 10 US Equities with the largest insider-trading dollar volume.
            _universe = AddUniverse<QuiverInsiderTradingUniverse>(data =>
            {
                // Aggregate insider dollar volume per ticker and keep the 10 largest.
                var dollarVolume = new Dictionary<Symbol, decimal>();
                foreach (var d in data.OfType<QuiverInsiderTradingUniverse>())
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
                return dollarVolume
                    .OrderByDescending(kvp => kvp.Value)
                    .Take(10)
                    .Select(kvp => kvp.Key);
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

            var weight = 1m / _universe.Selected.Count;
            var targets = _universe.Selected
                .Select(symbol => new PortfolioTarget(symbol, weight))
                .ToList();

            SetHoldings(targets, true);
        }
    }
}
