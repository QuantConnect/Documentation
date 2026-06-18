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
public class EquityIchimokuCloudAlgorithm : QCAlgorithm
{
    private Symbol _qqq;
    private IchimokuKinkoHyo _ichimoku;

    public override void Initialize()
    {
        SetStartDate(2024, 9, 1);
        SetEndDate(2024, 12, 31);
        SetCash(100000);
        _qqq = AddEquity("QQQ").Symbol;
        // Add the IchimokuKinkoHyo indicator.
        _ichimoku = ICHIMOKU(_qqq, 9, 26, 52, 26, 26, 52, Resolution.Daily);
        // Alternatively, use a manual indicator.
        // _ichimoku = new IchimokuKinkoHyo(9, 26, 52, 26, 26, 52);
        // WarmUpIndicator(_qqq, _ichimoku, Resolution.Daily);
        // RegisterIndicator(_qqq, _ichimoku, Resolution.Daily);
        // Manual warm-up: need WarmUpPeriod + 1 so both .Current and .Previous are valid.
        IndicatorHistory(_ichimoku, _qqq, _ichimoku.WarmUpPeriod + 1, Resolution.Daily);
        // Plot all five Ichimoku components — Tenkan, Kijun, Senkou A, Senkou B, Chikou.
        PlotIndicator("Ichimoku", _ichimoku.Tenkan, _ichimoku.Kijun, _ichimoku.SenkouA, _ichimoku.SenkouB, _ichimoku.Chikou);
        // Add a Scheduled Event to scan for trades every trading day at 8 AM.
        Schedule.On(DateRules.EveryDay(_qqq), TimeRules.At(8, 0), Rebalance);
    }

    private void Rebalance()
    {
        if (!_ichimoku.IsReady)
        {
            return;
        }
        var tenkan = _ichimoku.Tenkan.Current.Value;
        var kijun = _ichimoku.Kijun.Current.Value;
        var senkouA = _ichimoku.SenkouA.Current.Value;
        var senkouB = _ichimoku.SenkouB.Current.Value;
        var previousTenkanAbove = _ichimoku.Tenkan.Previous.Value > _ichimoku.Kijun.Previous.Value;
        var currentTenkanAbove = tenkan > kijun;
        var crossedAbove = currentTenkanAbove && !previousTenkanAbove;
        var crossedBelow = !currentTenkanAbove && previousTenkanAbove;
        // Cloud top = max(Senkou A, Senkou B); only go long when price sits above the cloud
        var cloudTop = Math.Max(senkouA, senkouB);
        if (!Portfolio.Invested && crossedAbove && Securities[_qqq].Price > cloudTop)
        {
            SetHoldings(_qqq, 1m);
        }
        else if (Portfolio.Invested && crossedBelow)
        {
            Liquidate();
        }
    }
}
