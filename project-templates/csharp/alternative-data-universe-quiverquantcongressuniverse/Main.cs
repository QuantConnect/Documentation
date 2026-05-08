using System.Linq;
using QuantConnect.Algorithm.Framework.Portfolio;
using QuantConnect.Data.UniverseSelection;
using QuantConnect.DataSource;
using QuantConnect.Orders;

namespace QuantConnect.Algorithm.CSharp
{
    public class QuiverQuantCongressUniverseAlgorithm : QCAlgorithm
    {
        private Universe _universe;

        public override void Initialize()
        {
            SetStartDate(2024, 9, 1);
            SetEndDate(2024, 12, 31);
            SetCash(100000);
            Settings.SeedInitialPrices = true;

            UniverseSettings.Resolution = Resolution.Daily;
            // Universe of US Equities recently bought by US Congress members in trades over $200K.
            _universe = AddUniverse<QuiverQuantCongressUniverse>(data =>
            {
                // Keep buy disclosures over $200K to filter out small reports.
                return from d in data.OfType<QuiverQuantCongressUniverse>()
                       where d.Amount > 200000m && d.Transaction == OrderDirection.Buy
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
