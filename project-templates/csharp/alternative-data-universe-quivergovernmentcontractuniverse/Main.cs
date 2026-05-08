using System.Linq;
using QuantConnect.Algorithm.Framework.Portfolio;
using QuantConnect.Data.UniverseSelection;
using QuantConnect.DataSource;

namespace QuantConnect.Algorithm.CSharp
{
    public class QuiverGovernmentContractUniverseAlgorithm : QCAlgorithm
    {
        private Universe _universe;

        public override void Initialize()
        {
            SetStartDate(2024, 9, 1);
            SetEndDate(2024, 12, 31);
            SetCash(100000);
            Settings.SeedInitialPrices = true;

            UniverseSettings.Resolution = Resolution.Minute;
            // Universe of US Equities with frequent, sizable US government contracts.
            _universe = AddUniverse<QuiverGovernmentContractUniverse>(data =>
            {
                // Group by ticker and keep names with 3+ contracts totalling over $50K.
                return from g in data.OfType<QuiverGovernmentContractUniverse>().GroupBy(x => x.Symbol)
                       where g.Count() >= 3 && g.Sum(x => x.Amount) > 50000m
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

            var weight = 1m / _universe.Selected.Count;
            var targets = _universe.Selected
                .Select(symbol => new PortfolioTarget(symbol, weight))
                .ToList();

            SetHoldings(targets, true);
        }
    }
}
