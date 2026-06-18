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
public class EquityRsiOverboughtOversoldAlgorithm : QCAlgorithm
{
    private Equity _equity;
    private RelativeStrengthIndex _rsi;

    public override void Initialize()
    {
        SetStartDate(2024, 9, 1);
        SetEndDate(2024, 12, 31);
        SetCash(100000);
        Settings.AutomaticIndicatorWarmUp = true;
        _equity = AddEquity("QQQ", Resolution.Hour);
        _rsi = RSI(_equity.Symbol, 14, MovingAverageType.Wilders, Resolution.Hour);
        // Alternatively, use a manual indicator.
        // _rsi = new RelativeStrengthIndex(14, MovingAverageType.Wilders);
        // WarmUpIndicator(_equity.Symbol, _rsi);
        // RegisterIndicator(_equity.Symbol, _rsi);
        PlotIndicator("RSI", _rsi);
    }

    public override void OnData(Slice data)
    {
        if (!_rsi.IsReady)
        {
            return;
        }
        var rsi = _rsi.Current.Value;
        var quantity = _equity.Holdings.Quantity;
        if (quantity <= 0 && rsi < 30m)
        {
            SetHoldings(_equity.Symbol, 1m);
        }
        else if (quantity > 0 && rsi > 50m)
        {
            Liquidate();
        }
        else if (quantity >= 0 && rsi > 70m)
        {
            SetHoldings(_equity.Symbol, -1m);
        }
        else if (quantity < 0 && rsi < 50m)
        {
            Liquidate();
        }
    }
}
