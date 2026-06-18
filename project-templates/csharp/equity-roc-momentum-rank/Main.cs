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

public class SectorRotationAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        SetStartDate(2022, 1, 1);
        SetEndDate(2024, 12, 31);
        SetCash(200000);
        Settings.AutomaticIndicatorWarmUp = true;

        var tickers = new string[]
        {
            "XLK", "XLF", "XLE", "XLV", "XLI",
            "XLB", "XLY", "XLP", "XLU", "XLRE"
        };
        foreach (var ticker in tickers)
        {
            // Minute-resolution data, but rank on a daily ROC indicator.
            dynamic equity = AddEquity(ticker);
            equity.Roc = ROC(equity, 60, Resolution.Daily);
            // Alternatively, use a manual indicator.
            // equity.Roc = new RateOfChange(60);
            // WarmUpIndicator(equity.Symbol, equity.Roc, Resolution.Daily);
            // RegisterIndicator(equity.Symbol, equity.Roc, Resolution.Daily);
        }

        Schedule.On(
            DateRules.MonthStart("XLK"),
            TimeRules.AfterMarketOpen("XLK", 1),
            Rebalance);
    }

    private void Rebalance()
    {
        var securities = Securities.Values.ToList();
        if (!securities.All(security => (bool)((dynamic)security).Roc.IsReady))
        {
            return;
        }

        var top3 = securities
            .OrderByDescending(security => (decimal)((dynamic)security).Roc.Current.Value)
            .Take(3)
            .ToList();

        var targets = top3
            .Select(security => new PortfolioTarget(security.Symbol, (decimal)(1.0 / 3.0)))
            .ToList();
        SetHoldings(targets, liquidateExistingHoldings: true);
    }
}
