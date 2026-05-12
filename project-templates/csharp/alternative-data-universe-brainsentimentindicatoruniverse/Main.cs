using System;
using System.Collections.Generic;
using System.Linq;
using QuantConnect.Algorithm.Framework.Portfolio;
using QuantConnect.Data.UniverseSelection;
using QuantConnect.DataSource;

namespace QuantConnect.Algorithm.CSharp
{
    public class BrainSentimentIndicatorChainedUniverseAlgorithm : QCAlgorithm
    {
        private Universe _universe;

        public override void Initialize()
        {
            SetStartDate(2024, 9, 1);
            SetEndDate(2024, 12, 31);
            SetCash(100000);
            Settings.SeedInitialPrices = true;
            UniverseSettings.Resolution = Resolution.Daily;
            // Add a Brain Sentiment universe, restricted to high-sentiment names with strong coverage.
            _universe = AddUniverse<BrainSentimentIndicatorUniverse>(
                // Keep names with both active mention coverage 15+ articles and positive 7-day sentiment above 0.5.
                altCoarse => altCoarse
                    .OfType<BrainSentimentIndicatorUniverse>()
                    .Where(d => d.TotalArticleMentions7Days > 15m && d.Sentiment7Days > 0.5m)
                    .Select(d => d.Symbol)
            );
            // Rebalance every day at 9am.
            Schedule.On(DateRules.EveryDay("SPY"), TimeRules.At(9, 0, 0), Rebalance);
        }

        private void Rebalance()
        {
            if (_universe.Selected == null || _universe.Selected.Count == 0)
            {
                return;
            }
            // Filter to only securities with valid prices.
            var tradeable = _universe.Selected
                .Where(s => Securities[s].Price > 0)
                .ToList();
            if (tradeable.Count == 0)
            {
                return;
            }
            var weight = Math.Round(1m / tradeable.Count, 10, MidpointRounding.AwayFromZero);
            var targets = tradeable
                .Select(symbol => new PortfolioTarget(symbol, weight))
                .ToList();
            SetHoldings(targets, true);
        }
    }
}
