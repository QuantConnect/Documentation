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
public class CryptoOnChainNetworkActivityAlgorithm : QCAlgorithm
{
    private Crypto _btc;
    private Symbol _metadata;
    // 30-day moving average baselines of two distinct on-chain properties.
    private SimpleMovingAverage _txSma = new(30);
    private SimpleMovingAverage _hashSma = new(30);

    public override void Initialize()
    {
        SetStartDate(2024, 9, 1);
        SetEndDate(2024, 12, 31);
        SetCash(100000);
        // Trade BTCUSD on Coinbase.
        _btc = AddCrypto("BTCUSD", market: Market.Coinbase);
        // Subscribe to the Blockchain Bitcoin Metadata dataset for network-fundamentals signals.
        _metadata = AddData<BitcoinMetadata>(_btc.Symbol).Symbol;
        // Warm up via history so the strategy can trade from the first bar.
        var history = History<BitcoinMetadata>(_metadata, 30, Resolution.Daily);
        foreach (var data in history)
        {
            UpdateIndicators(data);
        }
    }

    private bool UpdateIndicators(BitcoinMetadata data)
    {
        // Update on-chain indicators with new data.
        return _txSma.Update(data.EndTime, data.NumberofTransactions)
                & _hashSma.Update(data.EndTime, data.HashRate);
    }

    public override void OnData(Slice slice)
    {
        // On-chain dataset publishes once per day; act only when fresh data arrives.
        if (!slice.ContainsKey(_metadata))
        {
            return;
        }
        var data = slice.Get<BitcoinMetadata>(_metadata);
        // Update both indicators with today's on-chain readings.
        if (!UpdateIndicators(data))
        {
            return;
        }
        // Long when network demand (transactions) AND supply (hash rate) both expand.
        var bullish = data.NumberofTransactions > _txSma.Current.Value
                    && data.HashRate > _hashSma.Current.Value;
        if (!_btc.Invested && bullish)
        {
            SetHoldings(_btc.Symbol, 1m);
        }
        else if (_btc.Invested && !bullish)
        {
            Liquidate();
        }
    }
}