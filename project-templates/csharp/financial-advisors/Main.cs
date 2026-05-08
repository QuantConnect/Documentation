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
public class FinancialAdvisorAlgorithm : QCAlgorithm
{
    private bool _connected;

    public override void Initialize()
    {
        SetStartDate(2024, 9, 12);
        SetEndDate(2024, 10, 1);

        // Financial Advisor is a live trading feature of the Interactive Brokers integration.
        SetBrokerageModel(BrokerageName.InteractiveBrokersBrokerage, AccountType.Margin);

        // Set the default order properties so all orders apply these settings.
        DefaultOrderProperties = new InteractiveBrokersOrderProperties()
        {
            FaGroup = "TestGroupEQ",
            FaMethod = "Equal",
            Account = "DU123456"
        };

        // Request SPY data to trade.
        AddEquity("SPY");
    }

    public override void OnData(Slice slice)
    {
        if (!Portfolio.Invested)
        {
            SetHoldings("SPY", 1);
        }
    }

    #region Live trading features
    private void NotifyAll(string subject, string message)
    {
        Notify.Email("email@address.com", subject, message);
        message = $"{Time:yyyyMMdd}: {subject} > {message}";
        Log(message);
        // See https://www.quantconnect.com/docs/v2/writing-algorithms/live-trading/notifications
        // For all notification methods.
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
    # endregion
}
