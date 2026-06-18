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

public class OptionChainFullExample : QCAlgorithm
{
    private QuantConnect.Securities.Index.Index _index;
    private Symbol _optionChainSymbol;
    private readonly InterestRateProvider _interestRateModel = new();
    private DividendYieldProvider _dividendYieldModel;
    private OrderTicket _lastTicket;

    public override void Initialize()
    {
        SetStartDate(2024, 9, 1);
        SetEndDate(2024, 9, 5);
        SetCash(500000);
        // AutomaticIndicatorWarmUp only supports automatic indicators, not manual indicators.
        Settings.AutomaticIndicatorWarmUp = true;
        UniverseSettings.MinimumTimeInUniverse = TimeSpan.Zero;

        // Warm-up the option contracts as soon as it is added to the algorithm
        Settings.SeedInitialPrices = true;

        // The EMA/price cross will determine we trade ATM contracts
        _index = AddIndex("RUT");
        var ema = new ExponentialMovingAverage(60);
        WarmUpIndicator<IndicatorDataPoint>(_index, ema);
        RegisterIndicator(_index, ema);
        ema.Updated += TradeTargetDeltaContract;

        _optionChainSymbol = QuantConnect.Symbol.CreateCanonicalOption(_index, "RUTW", Market.USA, "?RUTW");
        _dividendYieldModel = new DividendYieldProvider(_index);
    }

    public void TradeTargetDeltaContract(object sender, IndicatorDataPoint current)
    {
        // Pace trades every 10 minutes
        var lastTradeTime = _lastTicket?.Time ?? DateTime.MinValue;
        if ((UtcTime-lastTradeTime).TotalMinutes < 10) return;

        var ema = sender as ExponentialMovingAverage;
        if (!ema.IsReady) return;

        var spot = _index.Price;

        if (spot > current && spot > ema[-1])
        {
            var atmCall = GetTargetDeltaContract(OptionRight.Call, spot);
            if (atmCall != null && !Portfolio[atmCall].Invested)
            {
                _lastTicket = MarketOrder(atmCall, 1);
            }
        }

        if (spot < current && spot < ema[-1])
        {
            var atmPut = GetTargetDeltaContract(OptionRight.Put, spot);
            if (atmPut != null && !Portfolio[atmPut].Invested)
            {
                _lastTicket = MarketOrder(atmPut, 1);
            }
        }
    }

    private Option GetTargetDeltaContract(OptionRight right, decimal spot, decimal targetDelta =0.4m)
    {
        var mirrorOptionRight = right == OptionRight.Call ? OptionRight.Put : OptionRight.Call;
        var chain = OptionChain(_optionChainSymbol);
        var expiry = chain.Min(x => x.Expiry);

        // We will select the 10 contracts nearest to the money and then select the nearest contract with a given delta
        var targetDeltaContract = chain
            .Where(x => x.Expiry == expiry && x.Right == right)
            .OrderBy(x => Math.Abs(spot - x.Strike))
            .Take(10)
            .Select(x=>
            {
                var mirrorOption = QuantConnect.Symbol.CreateOption(x.Symbol.Underlying, "RUT", Market.USA, OptionStyle.European, mirrorOptionRight, x.Strike, x.Expiry);
                var delta = new Delta(x, _interestRateModel, _dividendYieldModel, mirrorOption);
                WarmUpIndicator([x.Symbol, mirrorOption, x.Symbol.Underlying], delta, Resolution.Minute);
                return new {x.Symbol, Delta = Math.Abs(delta)};
            })
            .OrderBy(x => Math.Abs(targetDelta - x.Delta))
            .FirstOrDefault();

        if (targetDeltaContract == null)
        {
            return null;
        }

        return AddOptionContract(targetDeltaContract.Symbol);
    }
}
