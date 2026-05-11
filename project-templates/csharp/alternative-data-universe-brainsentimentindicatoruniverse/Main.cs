using System;
using System.Collections.Generic;
using System.Linq;
using QuantConnect.Algorithm.Framework.Portfolio;
using QuantConnect.Data.Fundamental;
using QuantConnect.Data.UniverseSelection;
using QuantConnect.DataSource;

namespace QuantConnect.Algorithm.CSharp
{
    public class BrainSentimentIndicatorChainedUniverseAlgorithm : QCAlgorithm
    {
        private List<Symbol> _fundamental = [];
        private Universe _universe;

        public override void Initialize()
        {
            SetStartDate(2024, 9, 1);
            SetEndDate(2024, 12, 31);
            SetCash(100000);
            Settings.SeedInitialPrices = true;
            UniverseSettings.Resolution = Resolution.Daily;
            // Add a fundamental universe to track the most liquid US Equities by dollar volume.
            AddUniverse(fundamental =>
            {
                _fundamental = fundamental
                    .OrderBy(c => c.DollarVolume)
                    .TakeLast(100)
                    .Select(c => c.Symbol)
                    .ToList();
                return Universe.Unchanged;
            });
            // Add a Brain Sentiment universe, restricted to high-sentiment names within the fundamental list.
            _universe = AddUniverse<BrainSentimentIndicatorUniverse>(
                // Keep names with both active mention coverage and positive 7-day sentiment.
                altCoarse => altCoarse
                    .OfType<BrainSentimentIndicatorUniverse>()
                    .Where(d => d.TotalArticleMentions7Days > 0m && d.Sentiment7Days > 0m)
                    .Select(d => d.Symbol)
                    .Intersect(_fundamental)
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
