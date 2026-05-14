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
public class OpeningRangeBreakoutAlgorithm : QCAlgorithm
{
    private static readonly TimeSpan _orbStart = new TimeSpan(9, 30, 0);
    private static readonly TimeSpan _orbEnd = new TimeSpan(10, 0, 0);

    private Symbol _spy;
    private decimal? _orbHigh;
    private decimal? _orbLow;

    public override void Initialize()
    {
        SetStartDate(2024, 9, 1);
        SetEndDate(2024, 12, 31);
        SetCash(100000);
        Settings.SeedInitialPrices = true;

        _spy = AddEquity("SPY", Resolution.Minute).Symbol;

        _orbHigh = null;
        _orbLow = null;

        Schedule.On(
            DateRules.EveryDay(_spy),
            TimeRules.At(15, 55),
            LiquidateEod
        );
    }

    private void LiquidateEod()
    {
        Liquidate(_spy);
        // Clearing the range stops further entries and resets it for tomorrow.
        _orbHigh = null;
        _orbLow = null;
    }

    public override void OnData(Slice data)
    {
        if (!data.Bars.TryGetValue(_spy, out var bar))
        {
            return;
        }

        var currentTime = Time.TimeOfDay;

        // Build opening range from 9:30 up to (but not including) 10:00
        if (currentTime >= _orbStart && currentTime < _orbEnd)
        {
            if (_orbHigh == null)
            {
                _orbHigh = bar.High;
                _orbLow = bar.Low;
            }
            else
            {
                _orbHigh = Math.Max(_orbHigh.Value, bar.High);
                _orbLow = Math.Min(_orbLow.Value, bar.Low);
            }
            Plot("ORB", "High", _orbHigh.Value);
            Plot("ORB", "Low", _orbLow.Value);
        }
        // Trade breakouts after 10:00, only one trade per day
        else if (currentTime >= _orbEnd)
        {
            if (_orbHigh != null && bar.Close > _orbHigh.Value)
            {
                SetHoldings(_spy, 1m);
                Plot("Trades", "Direction", 1);
                _orbHigh = _orbLow = null;
            }
            if (_orbLow != null && bar.Close < _orbLow.Value)
            {
                SetHoldings(_spy, -1m);
                Plot("Trades", "Direction", -1);
                _orbHigh = _orbLow = null;
            }
        }
    }
}
