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
public class TelegramNotificationAlgorithm : QCAlgorithm
{
    private Symbol _spy;
    private ExponentialMovingAverage _ema50;
    private ExponentialMovingAverage _ema200;
    private bool? _previousEma50Above;

    public override void Initialize()
    {
        SetStartDate(2024, 9, 1);
        SetEndDate(2024, 12, 31);
        SetCash(100000);

        Settings.AutomaticIndicatorWarmUp = true;

        _spy = AddEquity("SPY", Resolution.Minute).Symbol;

        _ema50 = EMA(_spy, 50, Resolution.Daily);
        _ema200 = EMA(_spy, 200, Resolution.Daily);

        _previousEma50Above = null;
    }

    public override void OnData(Slice slice)
    {
        if (!_ema50.IsReady || !_ema200.IsReady)
        {
            return;
        }

        var ema50Above = _ema50.Current.Value > _ema200.Current.Value;

        if (_previousEma50Above == null)
        {
            _previousEma50Above = ema50Above;
            return;
        }

        if (ema50Above && _previousEma50Above == false)
        {
            SetHoldings(_spy, 1.0);
        }
        else if (!ema50Above && _previousEma50Above == true)
        {
            SetHoldings(_spy, -1.0);
        }

        _previousEma50Above = ema50Above;
    }

    public override void OnOrderEvent(OrderEvent orderEvent)
    {
        if (orderEvent.Status != OrderStatus.Filled || !LiveMode)
        {
            return;
        }

        var direction = orderEvent.Direction == OrderDirection.Buy ? "BUY" : "SELL";
        var message = $"SPY {direction} filled: {Math.Abs(orderEvent.FillQuantity)} @ {orderEvent.FillPrice:F2}";

        var url = "https://api.telegram.org/bot<YOUR_BOT_TOKEN>/sendMessage";
        var body = $"{{\"chat_id\":\"<YOUR_CHAT_ID>\",\"text\":\"{message}\"}}";
        var headers = new Dictionary<string, string> { { "Content-Type", "application/json" } };

        Notify.Web(url, body, headers);
        Log($"Notification sent: {message}");
    }
}
