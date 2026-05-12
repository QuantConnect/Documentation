using System.Collections.Generic;
using System.Linq;
using QuantConnect.Algorithm.Framework.Portfolio;
using QuantConnect.Data.UniverseSelection;
using QuantConnect.DataSource;

namespace QuantConnect.Algorithm.CSharp
{
    public class EODHDUpcomingDividendsUniverseAlgorithm : QCAlgorithm
    {
        private List<Symbol> _fundamental = [];
        private Universe _universe;

        public override void Initialize()
        {
            SetStartDate(2024, 9, 1);
            SetEndDate(2024, 12, 31);
            SetCash(100000);
            Settings.SeedInitialPrices = true;
            // Add a fundamental universe to track the most liquid US equities by dollar volume.
            AddUniverse(fundamental =>
            {
                // Store the top 50 equities by dollar volume without changing the active universe.
                _fundamental = fundamental
                    .OrderByDescending(f => f.DollarVolume)
                    .Take(50)
                    .Select(f => f.Symbol)
                    .ToList();
                return Universe.Unchanged;
            });
            // Add a dividend universe restricted to high-payout names within the fundamental list.
            _universe = AddUniverse<EODHDUpcomingDividends>(
                // Filter for symbols with dividends over $0.05 paying within one day.
                data => data
                    .OfType<EODHDUpcomingDividends>()
                    .Where(d => d.DividendDate <= Time.AddDays(1) && d.Dividend > 0.05m)
                    .Select(d => d.Symbol)
                    .Where(s => _fundamental.Contains(s))
            );
            // Schedule daily rebalancing at 9:31 AM to trade the current universe selection.
            Schedule.On(DateRules.EveryDay("SPY"), TimeRules.At(9, 31), Rebalance);
        }

        private void Rebalance()
        {
            if (_universe.Selected.Count == 0)
            {
                return;
            }
            // Create an equal weight portfolio with selected securities.
            var weight = 1m / _universe.Selected.Count;
            var targets = _universe.Selected
                .Select(symbol => new PortfolioTarget(symbol, weight))
                .ToList();
            SetHoldings(targets, true);
        }
    }
}
