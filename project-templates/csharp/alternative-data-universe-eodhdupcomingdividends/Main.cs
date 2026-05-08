using System.Linq;
using QuantConnect.Algorithm.Framework.Portfolio;
using QuantConnect.Data.UniverseSelection;
using QuantConnect.DataSource;

namespace QuantConnect.Algorithm.CSharp
{
    public class EODHDUpcomingDividendsUniverseAlgorithm : QCAlgorithm
    {
        private Universe _universe;

        public override void Initialize()
        {
            SetStartDate(2024, 9, 1);
            SetEndDate(2024, 12, 31);
            SetCash(100000);
            Settings.SeedInitialPrices = true;

            UniverseSettings.Resolution = Resolution.Minute;
            // Universe of US Equities going ex-dividend in the next day with a meaningful payout.
            _universe = AddUniverse<EODHDUpcomingDividends>(data =>
            {
                // Keep names with a dividend over $0.05 paying within one day.
                return from d in data.OfType<EODHDUpcomingDividends>()
                       where d.DividendDate <= Time.AddDays(1) && d.Dividend > 0.05m
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
