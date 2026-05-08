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
public class CalendarConsolidatorExampleAlgorithm : QCAlgorithm
{
    private dynamic _pair;

    public override void Initialize()
    {
        SetStartDate(2024, 9, 1);
        SetEndDate(2024, 12, 31);
        // Add the USDJPY Forex pair.
        _pair = AddForex("USDJPY");
        // Create an EMA indicator for the pair.
        _pair.Ema = new ExponentialMovingAverage(10);
        // Create a QuoteBar consolidator with a custom consolidation period.
        var consolidator = new QuoteBarConsolidator(DailyForexConsolidationPeriod);
        // You can also create a consolidator with a period of one day and start time of 17.
        // Var consolidator = new QuoteBarConsolidator(TimeSpan.FromDays(1), TimeSpan.FromHours(17));.
        // Attach a consolidation handler that will receive the consolidated bars.
        consolidator.DataConsolidated += ConsolidationHandler;
        // Subscribe the consolidator for automatic updates with the prices of the pair.
        SubscriptionManager.AddConsolidator(_pair, consolidator);
        // Register the indicator for automatic updates with the consolidated bars.
        RegisterIndicator<IndicatorDataPoint>(_pair, _pair.Ema, consolidator);
        // Warm up the consolidator and indicator.
        var history = History<QuoteBar>(_pair, 29000, Resolution.Minute);
        foreach (var bar in history)
        {
            consolidator.Update(bar);
        }
    }

    // Define the consolidation period.
    private CalendarInfo DailyForexConsolidationPeriod(DateTime dt)
    {
        // Set the start of the bar to be 5 PM ET.
        var start = dt.Date;
        if (dt.Hour < 17)
        {
            start = start.AddHours(-7);
        }
        else
        {
            start = start.AddHours(17);
        }
        // Set the end of the bar to be 5 PM ET the next day.
        return new CalendarInfo(start, TimeSpan.FromDays(1));
    }

    private void ConsolidationHandler(object sender, QuoteBar consolidatedBar)
    {
        // Wait until the indicator is ready and the algorithm is running.
        if (!_pair.Ema.IsReady || IsWarmingUp)
        {
            return;
        }
        // Plot the closing price and the EMA.
        Plot(consolidatedBar.Symbol.Value, "Close", consolidatedBar.Close);
        Plot(consolidatedBar.Symbol.Value, "EMA", _pair.Ema.Current.Value);

        if (!_pair.Holdings.IsLong && consolidatedBar.Close > _pair.Ema)
        {
            SetHoldings(_pair, 1);
        }
        if (consolidatedBar.Close < _pair.Ema)
        {
            Liquidate();
        }
    }
}
