using System.Collections.Generic;
using System.Linq;
using QuantConnect.Algorithm.Framework.Portfolio;
using QuantConnect.Data.UniverseSelection;
using QuantConnect.DataSource;

namespace QuantConnect.Algorithm.CSharp
{
    public class EODHDUpcomingIPOsUniverseAlgorithm : QCAlgorithm
    {
        private static readonly HashSet<EODHD.DealType> _dealTypesWanted = new() { EODHD.DealType.Expected, EODHD.DealType.Priced };

        private Universe _universe;

        public override void Initialize()
        {
            SetStartDate(2024, 9, 1);
            SetEndDate(2024, 12, 31);
            SetCash(100000);
            Settings.SeedInitialPrices = true;

            UniverseSettings.Resolution = Resolution.Minute;
            // Universe of confirmed non-penny upcoming IPOs.
            _universe = AddUniverse<EODHDUpcomingIPOs>(data =>
            {
                // Keep expected/priced IPOs with a confirmed date and an above-$1 price band.
                return from d in data.OfType<EODHDUpcomingIPOs>()
                       where _dealTypesWanted.Contains(d.DealType)
                          && d.IpoDate.HasValue
                          && new[] { d.LowestPrice, d.HighestPrice, d.OfferPrice }
                                .Where(x => x.HasValue)
                                .Min().Value > 1m
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
