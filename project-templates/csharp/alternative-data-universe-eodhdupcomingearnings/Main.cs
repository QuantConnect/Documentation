using System.Linq;
using QuantConnect.Algorithm.Framework.Portfolio;
using QuantConnect.Data.UniverseSelection;
using QuantConnect.DataSource;

namespace QuantConnect.Algorithm.CSharp
{
    public class EODHDUpcomingEarningsUniverseAlgorithm : QCAlgorithm
    {
        private Universe _universe;

        public override void Initialize()
        {
            SetStartDate(2024, 9, 1);
            SetEndDate(2024, 12, 31);
            SetCash(100000);
            Settings.SeedInitialPrices = true;

            UniverseSettings.Resolution = Resolution.Minute;
            // Universe of US Equities reporting earnings in the next 3 days with a positive estimate.
            _universe = AddUniverse<EODHDUpcomingEarnings>(data =>
            {
                // Keep names with a positive analyst estimate ahead of the report.
                return from d in data.OfType<EODHDUpcomingEarnings>()
                       where d.ReportDate <= Time.AddDays(3) && d.Estimate > 0m
                       select d.Symbol;
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
