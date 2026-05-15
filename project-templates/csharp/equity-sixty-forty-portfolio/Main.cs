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
public class Static6040Algorithm : QCAlgorithm
{
    private const decimal _spyWeight = 0.60m;
    private const decimal _tltWeight = 0.40m;
    private const decimal _driftThreshold = 0.05m;

    private Symbol _spy;
    private Symbol _tlt;
    private Dictionary<Symbol, decimal> _targets;

    public override void Initialize()
    {
        SetStartDate(2022, 1, 1);
        SetEndDate(2024, 12, 31);
        SetCash(100000);

        _spy = AddEquity("SPY", Resolution.Minute).Symbol;
        _tlt = AddEquity("TLT", Resolution.Minute).Symbol;

        Schedule.On(
            DateRules.QuarterStart("SPY"),
            TimeRules.AfterMarketOpen("SPY", 1),
            Rebalance);

        _targets = new Dictionary<Symbol, decimal>
        {
            { _spy, _spyWeight },
            { _tlt, _tltWeight },
        };
    }

    public override void OnWarmupFinished()
    {
        Rebalance();
    }

    private void Rebalance()
    {
        var needsRebalance = false;
        foreach (var kvp in _targets)
        {
            var currentWeight = Portfolio[kvp.Key].HoldingsValue / Portfolio.TotalPortfolioValue;
            if (Math.Abs(currentWeight - kvp.Value) > _driftThreshold)
            {
                needsRebalance = true;
                break;
            }
        }

        if (!needsRebalance)
        {
            return;
        }

        var targets = _targets
            .Select(kvp => new PortfolioTarget(kvp.Key, kvp.Value))
            .ToList();
        SetHoldings(targets);
    }
}
