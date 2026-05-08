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
using System.Collections.Concurrent;

public class EmaCrossUniverseSelectionAlgorithm : QCAlgorithm
{
    // Tolerance to prevent bouncing.
    const decimal _tolerance = 0.01m;
    private const int _count = 10;
    // Use Buffer+Count to leave a little in cash.
    private const decimal _targetPercent = 0.09m;

    private Universe _universe;

    // Holds our coarse fundamental indicators by symbol.
    private readonly ConcurrentDictionary<Symbol, SelectionData> _averages = [];

    /// <summary>
    /// Initialise the data and resolution required, as well as the cash and start-end dates for your algorithm. All algorithms must initialized.
    /// </summary>
    public override void Initialize()
    {
        SetStartDate(2021, 1, 1);
        SetEndDate(2024, 12, 31);
        SetCash(100000);
        Settings.SeedInitialPrices = true;
        UniverseSettings.Leverage = 2.0m;
        UniverseSettings.Resolution = Resolution.Daily;

        _universe = AddUniverse(coarse =>
        {
            return (from cf in coarse
                // Grab th SelectionData instance for this symbol.
                let avg = _averages.GetOrAdd(cf.Symbol, sym => new SelectionData())
                // Update returns true when the indicators are ready, so don't accept until they are.
                where avg.Update(cf.EndTime, cf.AdjustedPrice)
                // And only pick symbols who have their market cap above 1Bi and 100 day ema over their 300 day ema.
                where cf.MarketCap > 10000000000 && avg.Fast > avg.Slow * (1 + _tolerance)
                // Prefer symbols with a larger delta by percentage between the two averages.
                orderby avg.ScaledDelta descending
                // We only need to return the symbol and return 'Count' symbols.
                select cf.Symbol).Take(_count);
        });

        SetWarmUp(400, Resolution.Daily);
    }

    /// <summary>
    /// OnData event is the primary entry point for your algorithm. Each new data point will be pumped in here.
    /// </summary>
    /// <param name="slice">Slice object keyed by symbol containing the stock data</param>
    public override void OnData(Slice slice)
    {
        // We'll simply go long each security in the universe.
        var targets = _universe.Selected.Where(x => Securities[x].HasData)
            .Select(x => new PortfolioTarget(x, _targetPercent)).ToList();
        SetHoldings(targets, true);
    }

    // Class used to improve readability of the coarse selection function.
    private class SelectionData
    {
        public readonly ExponentialMovingAverage Fast;
        public readonly ExponentialMovingAverage Slow;

        public SelectionData()
        {
            Fast = new ExponentialMovingAverage(100);
            Slow = new ExponentialMovingAverage(300);
        }

        // Computes an object score of how much large the fast is than the slow.
        public decimal ScaledDelta => (Fast - Slow)/((Fast + Slow)/2m);

        // Updates the EMA50 and EMA100 indicators, returning true when they're both ready.
        public bool Update(DateTime time, decimal value)
        {
            return Fast.Update(time, value) & Slow.Update(time, value);
        }
    }
}
