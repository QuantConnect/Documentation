using System.Linq;
using QuantConnect.Algorithm.Framework.Portfolio;
using QuantConnect.Data.UniverseSelection;
using QuantConnect.DataSource;

namespace QuantConnect.Algorithm.CSharp
{
    public class SmartInsiderIntentionUniverseAlgorithm : QCAlgorithm
    {
        private Universe _universe;

        public override void Initialize()
        {
            SetStartDate(2024, 9, 1);
            SetEndDate(2024, 12, 31);
            SetCash(100000);
            Settings.SeedInitialPrices = true;

            UniverseSettings.Resolution = Resolution.Daily;
            // Universe of US Equities announcing meaningful buyback intentions.
            _universe = AddUniverse<SmartInsiderIntentionUniverse>(data =>
            {
                // Keep names announcing a buyback over 0.5% of shares. (USDMarketCap is not
                // populated for intention records, unlike SmartInsiderTransactionUniverse.)
                return data.OfType<SmartInsiderIntentionUniverse>()
                    .Where(d => d.Percentage > 0.005m)
                    .Select(d => d.Symbol);
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
