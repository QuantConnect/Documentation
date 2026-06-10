#region imports
    using System;
    using System.Collections;
    using System.Collections.Generic;
    using System.Linq;
    using System.Globalization;
    using System.Drawing;
    using QuantConnect;
    using QuantConnect.Algorithm.Framework;
    using QuantConnect.Algorithm.Framework.Selection;
    using QuantConnect.Algorithm.Framework.Alphas;
    using QuantConnect.Algorithm.Framework.Portfolio;
    using QuantConnect.Algorithm.Framework.Portfolio.SignalExports;
    using QuantConnect.Algorithm.Framework.Execution;
    using QuantConnect.Algorithm.Framework.Risk;
    using QuantConnect.Algorithm.Selection;
    using QuantConnect.Api;
    using QuantConnect.Parameters;
    using QuantConnect.Benchmarks;
    using QuantConnect.Brokerages;
    using QuantConnect.Commands;
    using QuantConnect.Configuration;
    using QuantConnect.Util;
    using QuantConnect.Interfaces;
    using QuantConnect.Algorithm;
    using QuantConnect.Indicators;
    using QuantConnect.Data;
    using QuantConnect.Data.Auxiliary;
    using QuantConnect.Data.Consolidators;
    using QuantConnect.Data.Custom;
    using QuantConnect.Data.Custom.IconicTypes;
    using QuantConnect.DataSource;
    using QuantConnect.Data.Fundamental;
    using QuantConnect.Data.Market;
    using QuantConnect.Data.Shortable;
    using QuantConnect.Data.UniverseSelection;
    using QuantConnect.Notifications;
    using QuantConnect.Orders;
    using QuantConnect.Orders.Fees;
    using QuantConnect.Orders.Fills;
    using QuantConnect.Orders.OptionExercise;
    using QuantConnect.Orders.Slippage;
    using QuantConnect.Orders.TimeInForces;
    using QuantConnect.Python;
    using QuantConnect.Scheduling;
    using QuantConnect.Securities;
    using QuantConnect.Securities.Equity;
    using QuantConnect.Securities.Future;
    using QuantConnect.Securities.Option;
    using QuantConnect.Securities.Positions;
    using QuantConnect.Securities.Forex;
    using QuantConnect.Securities.Crypto;
    using QuantConnect.Securities.CryptoFuture;
    using QuantConnect.Securities.IndexOption;
    using QuantConnect.Securities.Interfaces;
    using QuantConnect.Securities.Volatility;
    using QuantConnect.Storage;
    using QuantConnect.Statistics;
    using QCAlgorithmFramework = QuantConnect.Algorithm.QCAlgorithm;
    using QCAlgorithmFrameworkBridge = QuantConnect.Algorithm.QCAlgorithm;
    using Calendar = QuantConnect.Data.Consolidators.Calendar;
#endregion

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
            var volatility = Math.Sqrt(
                returns.Select(x => Math.Pow(x - mean, 2)).Average()
            );
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
