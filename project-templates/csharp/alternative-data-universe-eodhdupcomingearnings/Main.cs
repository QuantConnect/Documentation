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

            UniverseSettings.Resolution = Resolution.Daily;
            // Universe of US Equities reporting earnings in the next 3 days with estimate > 1.5.
            _universe = AddUniverse<EODHDUpcomingEarnings>(data =>
            {
                // Keep names with an analyst estimate over 1.5 ahead of the report.
                return from d in data.OfType<EODHDUpcomingEarnings>()
                       where d.ReportDate <= Time.AddDays(3) && d.Estimate > 1.5m
                       select d.Symbol;
            });

            // Rebalance shortly after the open so today's universe is locked in.
            Schedule.On(DateRules.EveryDay("SPY"), TimeRules.At(9, 31, 0), Rebalance);
        }

        private void Rebalance()
        {
            if (_universe.Selected.Count == 0)
            {
                return;
            }

            var securities = _universe.Selected.Where(s => Securities[s].Price > 0).ToList();
            if (securities.Count == 0)
            {
                return;
            }
            var weight = 1m / securities.Count;
            var targets = securities
                .Select(symbol => new PortfolioTarget(symbol, weight))
                .ToList();

            SetHoldings(targets, liquidateExistingHoldings: true);
        }
    }
}
