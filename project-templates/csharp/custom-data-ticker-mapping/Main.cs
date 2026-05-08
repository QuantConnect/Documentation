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
using QuantConnect.Securities.Index;
#endregion

public class CustomDataTradeProviderAlgorithm : QCAlgorithm
{
    private static readonly string Content = @"
2020-01-20,FB,100
2020-01-20,MSFT,200
2020-01-20,NVDA,300
2024-09-03,META,-100
2024-09-03,MSFT,-200
2024-09-03,NVDA,-300";


    public override void Initialize()
    {
        SetStartDate(2020, 1, 1);
        SetEndDate(2024, 12, 31);

        // Save the data to the object store.
        if (!ObjectStore.ContainsKey("selected_trades.csv"))
            ObjectStore.Save("selected_trades.csv", Content);

        Settings.SeedInitialPrices = true;

        AddData<SelectedTrades>("X");
    }

    public override void OnData(Slice slice)
    {
        foreach (var (symbol, data) in slice.Get<SelectedTrades>())
        {
            if (!Securities.ContainsKey(symbol))
            {
                AddSecurity(symbol);
            }
            MarketOrder(symbol, data.Quantity);
        }
    }
}

public class SelectedTrades : BaseData
{
    public decimal Quantity { get; set; }

    public override SubscriptionDataSource GetSource(SubscriptionDataConfig config, DateTime date, bool isLiveMode)
    {
        return new SubscriptionDataSource("selected_trades.csv", SubscriptionTransportMedium.ObjectStore);
    }

    public override BaseData Reader(SubscriptionDataConfig config, string line, DateTime date, bool isLiveMode)
    {
        if (string.IsNullOrWhiteSpace(line.Trim()))
        {
            return null;
        }

        var data = line.Split(',').ToArray(x => x.Trim());

        try
        {
            var ticker = data[1];
            var time = DateTime.Parse(data[0], CultureInfo.InvariantCulture);
            // Create the SecurityIdentifier with the point-in-time ticker and the current date.
            // In this example, we trade META in 2020 when its ticker was FB.
            // Then, we will see it when it is META.
            var securityID = SecurityIdentifier.GenerateEquity(ticker, Market.USA, mappingResolveDate: time);

            if (securityID.Date.Year < 1998)
            {
                // Ticker not found in QuantConnect database on this date.
                return null;
            }

            return new SelectedTrades()
            {
                Symbol = new Symbol(securityID, ticker),
                EndTime = time,
                Time = time.AddDays(-1),
                Quantity = data[2].IfNotNullOrEmpty(s => decimal.Parse(s, NumberStyles.Any, CultureInfo.InvariantCulture))
            };
        }
        catch
        {
        }
        return null;
    }
}
