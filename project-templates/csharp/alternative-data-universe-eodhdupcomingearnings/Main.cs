using System.Collections.Generic;
using System.Linq;
using QuantConnect.Algorithm.Framework.Portfolio;
using QuantConnect.Data.UniverseSelection;
using QuantConnect.DataSource;

namespace QuantConnect.Algorithm.CSharp
{
    public class EODHDUpcomingEarningsUniverseAlgorithm : QCAlgorithm
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
            // Add an earnings universe restricted to upcoming reporters within the fundamental list.
            _universe = AddUniverse<EODHDUpcomingEarnings>(
                // Filter for symbols with a positive analyst estimate reporting within the next 3 days.
                data => data
                    .OfType<EODHDUpcomingEarnings>()
                    .Where(d => d.ReportDate <= Time.AddDays(3) && d.Estimate > 0m)
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
            SetHoldings(targets, liquidateExistingHoldings: true);
        }
    }
}
