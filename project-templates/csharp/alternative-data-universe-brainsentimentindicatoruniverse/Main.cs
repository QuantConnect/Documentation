using System.Linq;
using QuantConnect.Algorithm.Framework.Portfolio;
using QuantConnect.Data.UniverseSelection;
using QuantConnect.DataSource;

namespace QuantConnect.Algorithm.CSharp
{
    public class BrainSentimentIndicatorUniverseAlgorithm : QCAlgorithm
    {
        private Universe _universe;

        public override void Initialize()
        {
            SetStartDate(2024, 9, 1);
            SetEndDate(2024, 12, 31);
            SetCash(100000);
            Settings.SeedInitialPrices = true;

            UniverseSettings.Resolution = Resolution.Minute;
            // Universe of US Equities with positive 7-day media sentiment and active mention coverage.
            _universe = AddUniverse<BrainSentimentIndicatorUniverse>(data =>
            {
                // Keep names with both active mention coverage and positive 7-day sentiment.
                return from d in data.OfType<BrainSentimentIndicatorUniverse>()
                       where d.TotalArticleMentions7Days > 0m && d.Sentiment7Days > 0m
                       select d.Symbol;
            });

            // Rebalance before market open to trade today's universe.
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
