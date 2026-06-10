using System;
using System.Collections.Generic;
using System.Linq;
using QuantConnect;
using QuantConnect.Algorithm.Framework.Portfolio;
using QuantConnect.Util;
using QuantConnect.Algorithm;
using QuantConnect.Data.Market;
using QuantConnect.Data.UniverseSelection;
using QuantConnect.Scheduling;
using QuantConnect.Securities;

public class SP500LowVolatility : QCAlgorithm
{
    private Universe _universe;
    private readonly int _lookback = 60;

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
        // Store the realized volatility of each constituent.
        var volatilityBySymbol = new Dictionary<Symbol, double>();
        foreach (var constituent in constituents)
        {
            var history = History<TradeBar>(constituent.Symbol, _lookback, Resolution.Daily).ToList();
            if (history.Count == 0)
            {
                continue;
            }
            var returns = history
                .Select(bar => (double)bar.Close)
                .Zip(history.Skip(1).Select(bar => (double)bar.Close), (previous, current) => current / previous - 1d)
                .ToList();
            if (returns.Count == 0)
            {
                continue;
            }
            var mean = returns.Average();
            var volatility = Math.Sqrt(returns.Select(x => Math.Pow(x - mean, 2)).Average());
            volatilityBySymbol[constituent.Symbol] = volatility;
        }
        // Select the 30 ETF constituents with the lowest 60-day realized volatility.
        return volatilityBySymbol.OrderBy(kvp => kvp.Value).Take(30).Select(kvp => kvp.Key);
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
