using System.Linq;
using QuantConnect.Algorithm.Framework.Portfolio;
using QuantConnect.Data.UniverseSelection;
using QuantConnect.DataSource;
using QuantConnect.Orders;

namespace QuantConnect.Algorithm.CSharp
{
    public class QuiverCNBCsUniverseAlgorithm : QCAlgorithm
    {
        private Universe _universe;

        public override void Initialize()
        {
            SetStartDate(2024, 9, 1);
            SetEndDate(2024, 12, 31);
            SetCash(100000);
            Settings.SeedInitialPrices = true;

            // Trade daily on CNBC opinion updates.
            UniverseSettings.Resolution = Resolution.Daily;
            // Universe of US Equities flagged by 3+ positive CNBC opinions.
            _universe = AddUniverse<QuiverCNBCsUniverse>(data =>
            {
                // Group raw CNBC opinions by ticker so we can score each name.
                var cnbcBySymbol = data.OfType<QuiverCNBCsUniverse>().GroupBy(x => x.Symbol);
                // Keep names with 3+ BUY recommendations to filter out noise.
                return from g in cnbcBySymbol
                       where g.Count(x => x.Direction == OrderDirection.Buy) >= 3
                       select g.Key;
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
            var securities = _universe.Selected.Where(s => Securities[s].Price > 0).ToList();
            if (securities.Count == 0)
            {
                return;
            }
            var weight = securities.Count >= 10 ? 1m / securities.Count : 0.1m;
            var targets = securities
                .Select(symbol => new PortfolioTarget(symbol, weight))
                .ToList();

            SetHoldings(targets, liquidateExistingHoldings: true);
        }
    }
}
