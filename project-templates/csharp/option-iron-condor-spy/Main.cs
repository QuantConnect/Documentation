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
public class OptionIronCondorSpyAlgorithm : QCAlgorithm
{
    private Option _option;

    public override void Initialize()
    {
        SetStartDate(2024, 9, 1);
        SetEndDate(2024, 12, 31);
        SetCash(100000);
        Settings.SeedInitialPrices = true;
        // Add the Option and define the chain filter.
        _option = AddOption("SPY");
        _option.SetFilter(u => u.Strikes(-10, 10).Expiration(0, 7));
        // Sell weekly iron condors on Monday's open and close them by Friday's close.
        Schedule.On(DateRules.Every(DayOfWeek.Monday), TimeRules.AfterMarketOpen(_option.Symbol, 1), OpenIronCondor);
        Schedule.On(DateRules.Every(DayOfWeek.Friday), TimeRules.BeforeMarketClose(_option.Symbol, 5), Close);
    }

    private void OpenIronCondor()
    {
        // Target the upcoming Friday expiration.
        var nextFriday = Time.AddDays(4).Date;
        var chain = OptionChain(_option.Symbol).Where(x => x.Expiry.Date == nextFriday).ToList();
        if (chain.Count == 0) return;

        var calls = chain.Where(x => x.Right == OptionRight.Call).OrderBy(x => x.Strike).ToList();
        var puts = chain.Where(x => x.Right == OptionRight.Put).OrderBy(x => x.Strike).ToList();
        if (calls.Count < 4 || puts.Count < 4) return;

        // Short legs nearest 10-delta on each side; calls have positive delta, puts negative.
        var shortCall = calls.OrderBy(x => Math.Abs(x.Greeks.Delta - 0.10m)).First();
        var shortPut = puts.OrderBy(x => Math.Abs(x.Greeks.Delta + 0.10m)).First();

        // Long wings sit 3 strikes further out from each short.
        var callStrikes = calls.Select(c => c.Strike).Distinct().OrderBy(s => s).ToList();
        var putStrikes = puts.Select(p => p.Strike).Distinct().OrderBy(s => s).ToList();
        var scIdx = callStrikes.IndexOf(shortCall.Strike);
        var spIdx = putStrikes.IndexOf(shortPut.Strike);
        if (scIdx + 3 >= callStrikes.Count || spIdx - 3 < 0) return;

        var longCall = calls.First(c => c.Strike == callStrikes[scIdx + 3]);
        var longPut = puts.First(p => p.Strike == putStrikes[spIdx - 3]);

        // Skip if any leg is missing a quote we can trade against.
        var legsContracts = new[] { shortCall, longCall, shortPut, longPut };
        if (legsContracts.Any(c => c.BidPrice <= 0 || c.AskPrice <= 0)) return;

        // Use OptionStrategies helper to create the iron condor.
        var optionStrategy = OptionStrategies.IronCondor(
            _option.Symbol,
            longPut.Strike,
            shortPut.Strike,
            shortCall.Strike,
            longCall.Strike,
            nextFriday
        );
        Buy(optionStrategy, 1);
    }

    private void Close()
    {
        // Close any open legs (or assigned underlying) at expiry.
        Liquidate();
    }
}
