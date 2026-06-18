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
public class EquityADXTrendFilterAlgorithm : QCAlgorithm
{
    private const decimal _adxThreshold = 25m;
    private Symbol _spy;
    private IndicatorBase<IndicatorDataPoint> _trend;
    private AverageDirectionalIndex _adx;

    public override void Initialize()
    {
        SetStartDate(2024, 9, 1);
        SetEndDate(2024, 12, 31);
        SetCash(100000);
        Settings.AutomaticIndicatorWarmUp = true;
        _spy = AddEquity("SPY").Symbol;
        var fastEma = EMA(_spy, 50, Resolution.Daily);
        var slowEma = EMA(_spy, 200, Resolution.Daily);
        _trend = fastEma.Minus(slowEma);
        // Alternatively, use a manual indicator.
        // var fastEma = new ExponentialMovingAverage(50);
        // WarmUpIndicator(_spy, fastEma, Resolution.Daily);
        // RegisterIndicator(_spy, fastEma, Resolution.Daily);
        // var slowEma = new ExponentialMovingAverage(200);
        // WarmUpIndicator(_spy, slowEma, Resolution.Daily);
        // RegisterIndicator(_spy, slowEma, Resolution.Daily);
        // _trend = fastEma.Minus(slowEma);
        _adx = ADX(_spy, 14, Resolution.Daily);
        // Alternatively, use a manual indicator.
        // _adx = new AverageDirectionalIndex(14);
        // WarmUpIndicator(_spy, _adx, Resolution.Daily);
        // RegisterIndicator(_spy, _adx, Resolution.Daily);
        PlotIndicator("Trend", _trend);
        PlotIndicator("ADX", _adx);
        Schedule.On(
            DateRules.EveryDay(_spy),
            TimeRules.AfterMarketOpen(_spy, 1),
            Rebalance
        );
    }

    private void Rebalance()
    {
        if (!_trend.IsReady || !_adx.IsReady)
        {
            return;
        }
        var inUptrend = _trend.Current.Value > 0m;
        var isTrending = _adx.Current.Value > _adxThreshold;
        var invested = Portfolio[_spy].Invested;
        if (inUptrend && isTrending)
        {
            if (!invested)
            {
                SetHoldings(_spy, 1m);
            }
        }
        else if (invested)
        {
            Liquidate();
        }
    }
}
