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

public class MonthlyRotationAlgorithm : QCAlgorithm
{
    private readonly int _rocPeriod = 60;
    private readonly int _topN = 3;

    public override void Initialize()
    {
        SetStartDate(2022, 1, 1);
        SetEndDate(2024, 12, 31);
        SetCash(100000);
        Settings.AutomaticIndicatorWarmUp = true;

        foreach (var ticker in new[] { "SPY", "EFA", "EEM", "AGG", "GLD" })
        {
            dynamic equity = AddEquity(ticker, Resolution.Minute);
            equity.Roc = ROC(equity, _rocPeriod, Resolution.Daily);
        }

        Schedule.On(
            DateRules.MonthStart("SPY"),
            TimeRules.AfterMarketOpen("SPY", 1),
            Rebalance);
    }

    private void Rebalance()
    {
        var securities = Securities.Values
            .Where(security => (bool)((dynamic)security).Roc.IsReady)
            .ToList();
        if (securities.Count < _topN)
        {
            return;
        }

        if (securities.All(security => (decimal)((dynamic)security).Roc.Current.Value < 0))
        {
            Liquidate();
            return;
        }

        var topSymbols = securities
            .OrderByDescending(security => (decimal)((dynamic)security).Roc.Current.Value)
            .Take(_topN)
            .Select(security => security.Symbol)
            .ToHashSet();

        var weight = 1.0m / _topN;
        var targets = Securities.Values
            .Select(security => new PortfolioTarget(
                security.Symbol, topSymbols.Contains(security.Symbol) ? weight : 0))
            .ToList();

        SetHoldings(targets, liquidateExistingHoldings: true);
    }
}
