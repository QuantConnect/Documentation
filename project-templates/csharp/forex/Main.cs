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

public class ForexExampleAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        SetStartDate(2024, 9, 1);
        SetEndDate(2024, 12, 31);
        // Add the some trading pair.
        var pairs = new [] {"EURUSD", "USDJPY", "USDCAD"};
        foreach (var pair in pairs)
        {
            dynamic forex = AddForex(pair);
            // Create a Minimum indicator to track the lowest bid-ask spread for the past 12 hours.
            forex.SpreadLow = new Minimum(12*60);
            // Warm up the indicator so it's immediately ready to use.
            foreach(var quoteBar in History<QuoteBar>(forex, 12*60))
            {
                forex.SpreadLow.Update(quoteBar.EndTime, quoteBar.Ask.Close - quoteBar.Bid.Close);
            }
        }
    }

    public override void OnData(Slice slice)
    {
        // Ensure we have quote data in the current slice.
        foreach(var (symbol, quoteBar) in slice.QuoteBars)
        {
            // Bid-ask spread = Ask price - Bid price.
            var bidAskSpread = quoteBar.Ask.Close - quoteBar.Bid.Close;
            // Update the spread minimum indicator to calculate the lowest bid-ask spread over the last 12 hours.
            dynamic forex = Securities[symbol];
            forex.SpreadLow.Update(quoteBar.EndTime, bidAskSpread);

            // Trade if the current spread is the lowest bid-ask spread,.
            // Since it is the most efficient, liquid price with lowest slippage.
            if (!forex.Invested && bidAskSpread == forex.SpreadLow.Current.Value)
            {
                MarketOrder(forex, 1000);
            }
        }
    }
}
