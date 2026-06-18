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
using Serilog.Debugging;
#endregion

public class OptionChainFullExample : QCAlgorithm
{
    private Symbol _optionChainSymbol;
    private OrderTicket _lastTicket;

    public override void Initialize()
    {
        SetStartDate(2024, 9, 1);
        SetEndDate(2024, 9, 5);
        SetCash(500000);
        Settings.AutomaticIndicatorWarmUp = true;
        UniverseSettings.MinimumTimeInUniverse = TimeSpan.Zero;

        // Warm-up the option contracts as soon as it is added to the algorithm
        Settings.SeedInitialPrices = true;

        // The EMA/price cross will determine we trade ATM contracts 
        var index = AddIndex("SPX");
        EMA(index.Symbol, 60).Updated += TradeAtTheMoneyContract;
        // Alternatively, use a manual indicator.
        // var ema = new ExponentialMovingAverage(60);
        // WarmUpIndicator(index.Symbol, ema);
        // RegisterIndicator(index.Symbol, ema);
        // ema.Updated += TradeAtTheMoneyContract;

        _optionChainSymbol = QuantConnect.Symbol.CreateCanonicalOption(index, "SPXW", Market.USA, "?SPXW");
    }

    public void TradeAtTheMoneyContract(object sender, IndicatorDataPoint current)
    {
        // Pace trades every 10 minutes
        var lastTrateTime = _lastTicket?.Time ?? DateTime.MinValue;
        if ((UtcTime-lastTrateTime).TotalMinutes < 10) return;

        var ema = sender as ExponentialMovingAverage;
        if (!ema.IsReady) return;

        var spot = Securities[current.Symbol].Price;
        
        if (spot > current && spot > ema[-1])
        {
            var atmCall = GetAtTheMoneyContract(OptionRight.Call, spot);
            if (atmCall != null && !atmCall.Holdings.Invested)
            {
                _lastTicket = MarketOrder(atmCall, 1);
            }
        }

        if (spot < current && spot < ema[-1])
        {
            var atmPut = GetAtTheMoneyContract(OptionRight.Put, spot);
            if (atmPut != null && !atmPut.Holdings.Invested)
            {
                _lastTicket = MarketOrder(atmPut, 1);
            }
        }
    }

    private Option GetAtTheMoneyContract(OptionRight right, decimal spot)
    {
        var chain = OptionChain(_optionChainSymbol);
        var expiry = chain.Min(x => x.Expiry);

        var atm = chain
            .Where(x => x.Expiry == expiry && x.Right == right)
            .OrderBy(x => Math.Abs(spot - x.Strike))
            .FirstOrDefault();

        if (atm == null)
        {
            return null;
        }
        
        return AddOptionContract(atm);
    }
}
