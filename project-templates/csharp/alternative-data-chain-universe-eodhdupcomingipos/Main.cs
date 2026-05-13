using System;
using System.Collections.Generic;
using System.Linq;
using QuantConnect.Algorithm.Framework.Portfolio;
using QuantConnect.Data.Fundamental;
using QuantConnect.Data.UniverseSelection;
using QuantConnect.DataSource;

namespace QuantConnect.Algorithm.CSharp
{
    public class EODHDUpcomingIPOsChainedUniverseAlgorithm : QCAlgorithm
    {
        private static readonly HashSet<EODHD.DealType> _dealTypesWanted = new() { EODHD.DealType.Expected, EODHD.DealType.Priced };

        private List<Fundamental> _fundamental = [];
        // Map of IPO symbol -> IPO date, captured while the event is upcoming so we can
        // trade the name once Morningstar has a few days of fundamentals on it.
        private Dictionary<Symbol, DateTime> _ipoDates = new();
        private Universe _universe;

        public override void Initialize()
        {
            SetStartDate(2024, 9, 1);
            SetEndDate(2024, 12, 31);
            SetCash(100000);
            Settings.SeedInitialPrices = true;

            UniverseSettings.Resolution = Resolution.Minute;
            // First universe: store all US Equity fundamentals; emits Universe.Unchanged.
            AddUniverse(fundamental =>
            {
                _fundamental = [..fundamental];
                return Universe.Unchanged;
            });
            // Second universe: trade IPOs one week after listing so fundamentals are populated.
            _universe = AddUniverse<EODHDUpcomingIPOs>(altCoarse =>
            {
                // Capture expected/priced IPOs with a confirmed date and a >$1 minimum price band.
                foreach (var d in altCoarse.OfType<EODHDUpcomingIPOs>())
                {
                    if (!_dealTypesWanted.Contains(d.DealType) || !d.IpoDate.HasValue)
                    {
                        continue;
                    }
                    var prices = new[] { d.LowestPrice, d.HighestPrice, d.OfferPrice }
                        .Where(x => x.HasValue)
                        .Select(x => x.Value)
                        .ToList();
                    if (prices.Count == 0 || prices.Min() <= 1m)
                    {
                        continue;
                    }
                    _ipoDates[d.Symbol] = d.IpoDate.Value;
                }
                // Drop entries whose IPO was more than 30 days ago to keep the dict bounded.
                _ipoDates = _ipoDates
                    .Where(kv => kv.Value > Time.AddDays(-30))
                    .ToDictionary(kv => kv.Key, kv => kv.Value);
                // Trade IPOs that listed at least 7 days ago, ranked by dollar volume.
                var alt = _ipoDates
                    .Where(kv => kv.Value <= Time.AddDays(-7))
                    .Select(kv => kv.Key)
                    .ToHashSet();
                Plot("Universe", "Raw", alt.Count);
                return _fundamental
                    .Where(c => alt.Contains(c.Symbol))
                    .OrderByDescending(c => c.DollarVolume)
                    .Select(c => c.Symbol)
                    .Take(100);
            });

            // Rebalance before market open to trade today's intersection.
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
