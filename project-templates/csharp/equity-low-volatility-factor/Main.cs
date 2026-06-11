using System;
using System.Collections.Generic;
using System.Linq;
using MathNet.Numerics.Statistics;
using QuantConnect;
using QuantConnect.Algorithm.Framework.Portfolio;
using QuantConnect.Algorithm;
using QuantConnect.Data.Market;
using QuantConnect.Data.UniverseSelection;
using QuantConnect.Scheduling;
using QuantConnect.Securities;

public class SP500LowVolatility : QCAlgorithm
{
    private Universe _universe;

    public override void Initialize()
    {
        SetStartDate(2022, 1, 1);
        SetEndDate(2024, 12, 31);
        SetCash(250000);
        Settings.SeedInitialPrices = true;
        UniverseSettings.Resolution = Resolution.Minute;
        // Refilter the ETF constituents monthly to match the rebalance cadence.
        UniverseSettings.Schedule.On(DateRules.MonthStart("SPY"));
        // Add a universe of the SPY constituents ranked by realized volatility.
        _universe = AddUniverse(Universe.ETF("SPY", SelectAssets));
        // Create a Scheduled Event to rebalance the portfolio monthly.
        Schedule.On(DateRules.MonthStart("SPY"), TimeRules.At(9, 0), Rebalance);
    }

    private IEnumerable<Symbol> SelectAssets(IEnumerable<ETFConstituentUniverse> constituents)
    {
        var history = History<TradeBar>(constituents.Select(constituent => constituent.Symbol), TimeSpan.FromDays(60), Resolution.Daily).ToList();
        // Select the 30 ETF constituents with the lowest 60-trading-day realized volatility.
        return history
            .SelectMany(bars => bars.Values)
            .GroupBy(bar => bar.Symbol)
            .OrderBy(group => group
                .Select(bar => (double)bar.Close)
                .Zip(group.Select(bar => (double)bar.Close).Skip(1), (previous, current) => current / previous - 1d)
                .StandardDeviation())
            .Take(30)
            .Select(group => group.Key);
    }

    private void Rebalance()
    {
        var selectedSymbols = _universe.Selected.ToList();
        if (selectedSymbols.Count == 0)
        {
            return;
        }
        // Equal-weight the selected low-volatility constituents.
        var weight = 1m / selectedSymbols.Count;
        var targets = selectedSymbols.Select(symbol => new PortfolioTarget(symbol, weight)).ToList();
        SetHoldings(targets, liquidateExistingHoldings: true);
    }
}
