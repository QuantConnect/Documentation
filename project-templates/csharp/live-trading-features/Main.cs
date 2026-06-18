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
public class LiveTradingFeaturesAlgorithm : QCAlgorithm
{
    private dynamic _spy;
    private bool _connected;

    public override void Initialize()
    {
        SetStartDate(2024, 9, 12);
        SetEndDate(2024, 10, 1);

        // AutomaticIndicatorWarmUp only supports automatic indicators, not manual indicators.
        Settings.AutomaticIndicatorWarmUp = true;

        // Request SPY data to trade.
        _spy = AddEquity("SPY");
        // Create an EMA indicator to generate trade signals.
        _spy.Ema = EMA(_spy, 20, Resolution.Daily);
        // Alternatively, use a manual indicator.
        // _spy.Ema = new ExponentialMovingAverage(20);
        // WarmUpIndicator<IndicatorDataPoint>(_spy.Symbol, _spy.Ema, Resolution.Daily);
        // RegisterIndicator(_spy.Symbol, _spy.Ema, Resolution.Daily);
    }

    private void NotifyAll(string subject, string message)
    {
        Notify.Email("email@address.com", subject, message);
        message = $"{Time:yyyyMMdd}: {subject} > {message}";
        Log(message);
        // See https://www.quantconnect.com/docs/v2/writing-algorithms/live-trading/notifications
        // for all notification methods
        Notify.Sms("+16191234567", message);
    }

    public override void OnBrokerageDisconnect()
    {
        NotifyAll($"Brokerage disconnected on {Time}", "-");
        _connected = false;
    }

    public override void OnBrokerageReconnect()
    {
        NotifyAll($"Brokerage reconnected on {Time}", "-");
        _connected = true;
    }

    public override void OnBrokerageMessage(BrokerageMessageEvent messageEvent)
    {
        switch (messageEvent.Type)
        {
            case BrokerageMessageType.Error:
                NotifyAll($"Brokerage Message", messageEvent.ToString());
                break;
            default:
                Log(messageEvent.ToString());
                break;
        }
    }

    public override void OnData(Slice slice)
    {
        NotifyEmaCross(slice);
    }

    /// <summary>
    // Trend-following strategy using price and EMA.
    // If the price is above EMA, SPY is in an uptrend, and we buy it.
    // We sent a link to our email address and await confirmation.
    /// </summary>
    private void NotifyEmaCross(Slice slice)
    {
        if (LiveMode && slice.Bars.TryGetValue(_spy, out TradeBar bar))
        {
            string link;
            if (_spy.Price > _spy.Ema && !_spy.Holdings.IsLong)
            {
                link = Link(new {Ticker = "SPY", Size = 1});
                NotifyAll("Trade Confirmation Needed", $"Click here to run: {link}");
            }
            else if (_spy.Price < _spy.Ema && !_spy.Holdings.IsShort)
            {
                link = Link(new {Ticker = "SPY", Size = -1});
                NotifyAll("Trade Confirmation Needed", $"Click here to run: {link}");
            }
        }
    }

    public override bool? OnCommand(dynamic data)
    {
        if (!_connected)
        {
            NotifyAll($"OnCommand :: brokerage disconnected", $"Cannot place order for {data}");
            return false;
        }

        // If we click the email link to confirm the trade, the algorithm will place the order.
        SetHoldings(data.Ticker, data.Size);
        return true;
    }
}
