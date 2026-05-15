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
public class FisherTransformAlgorithm : QCAlgorithm
{
    private Equity _qqq;
    private FisherTransform _fisher;

    public override void Initialize()
    {
        SetStartDate(2022, 1, 1);
        SetEndDate(2024, 12, 31);
        SetCash(100000);

        Settings.AutomaticIndicatorWarmUp = true;

        _qqq = AddEquity("QQQ", Resolution.Minute);

        _fisher = FISH(_qqq.Symbol, 10, Resolution.Daily);
        PlotIndicator("Fisher", _fisher);

        Schedule.On(
            DateRules.EveryDay(_qqq.Symbol),
            TimeRules.AfterMarketOpen(_qqq.Symbol, 30),
            Rebalance
        );
    }

    private void Rebalance()
    {
        if (!_fisher.IsReady)
        {
            return;
        }

        var current = _fisher.Current.Value;
        var previous = _fisher.Previous.Value;
        var holdings = _qqq.Holdings.Quantity;

        // Exit on cross of 0
        if (holdings > 0 && previous < 0m && 0m < current)
        {
            Liquidate(_qqq.Symbol);
        }
        else if (holdings < 0 && previous > 0m && 0m > current)
        {
            Liquidate(_qqq.Symbol);
        }

        // Entry only if flat (one position at a time)
        if (holdings == 0)
        {
            if (current > 2.0m && current < previous)
            {
                SetHoldings(_qqq.Symbol, -1.0m);
            }
            else if (current < -2.0m && current > previous)
            {
                SetHoldings(_qqq.Symbol, 1.0m);
            }
        }
    }
}
