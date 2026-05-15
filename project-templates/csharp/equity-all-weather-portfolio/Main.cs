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
public class AllWeatherPortfolioAlgorithm : QCAlgorithm
{
    private const decimal _driftThreshold = 0.01m;

    private DateTime _lastRebalanceDate;

    public override void Initialize()
    {
        SetStartDate(2022, 1, 1);
        SetEndDate(2024, 12, 31);
        SetCash(200000);

        var targets = new Dictionary<string, decimal>
        {
            { "SPY", 0.30m },
            { "TLT", 0.40m },
            { "IEF", 0.15m },
            { "GLD", 0.075m },
            { "DBC", 0.075m },
        };

        foreach (var (ticker, target) in targets)
        {
            // Duck-type the target weight onto the security object.
            dynamic security = AddEquity(ticker, Resolution.Minute);
            security.Target = target;
        }

        // Set target weights on the first bar with data.
        _lastRebalanceDate = StartDate.Date;

        // Annual rebalance on the first trading day of each year.
        Schedule.On(
            DateRules.YearStart("SPY", 0),
            TimeRules.AfterMarketOpen("SPY", 1),
            Rebalance);

        // Weekly holdings-drift monitoring.
        Schedule.On(
            DateRules.WeekStart("SPY"),
            TimeRules.AfterMarketOpen("SPY", 30),
            LogDrift);
    }

    public override void OnWarmupFinished()
    {
        Rebalance();
    }

    private void Rebalance()
    {
        var today = Time.Date;
        if (_lastRebalanceDate == today)
        {
            return;
        }

        var targets = new List<PortfolioTarget>();
        foreach (var (symbol, security) in Securities)
        {
            // Edge case: skip GLD/DBC if missing in early data.
            if (!security.HasData)
            {
                Debug($"Skipping {symbol}: no data available.");
                continue;
            }

            targets.Add(new PortfolioTarget(symbol, (decimal)((dynamic)security).Target));
        }

        if (targets.Count > 0)
        {
            SetHoldings(targets, liquidateExistingHoldings: true);
            Debug($"Rebalanced on {Time}");
        }

        _lastRebalanceDate = today;
        LogDrift();
    }

    private void LogDrift()
    {
        var totalValue = Portfolio.TotalPortfolioValue;
        if (totalValue <= 0)
        {
            return;
        }

        foreach (var (symbol, security) in Securities)
        {
            if (!security.HasData)
            {
                continue;
            }

            var target = (decimal)((dynamic)security).Target;
            var currentWeight = security.Holdings.HoldingsValue / totalValue;
            var drift = Math.Abs(currentWeight - target);
            Debug($"{symbol}: target={target:P2}, current={currentWeight:P2}, drift={drift:P2}");

            if (drift > _driftThreshold)
            {
                Debug($"{symbol} drift exceeds {_driftThreshold:P2}: {drift:P2}");
            }
        }
    }
}
