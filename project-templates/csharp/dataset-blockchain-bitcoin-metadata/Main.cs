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
public class BitcoinBlockTimeAlgorithm : QCAlgorithm
{
    private Crypto _btc;
    private Symbol _metadata;
    // 30-day rate of change on median block time (network speed regime).
    private RateOfChange _roc = new(30);
    // Stale-data guard: liquidate if the daily feed lags more than 3 days.
    private DateTime? _lastMetadataTime;
    private readonly TimeSpan _staleThreshold = TimeSpan.FromDays(3);

    public override void Initialize()
    {
        SetStartDate(2023, 1, 1);
        SetEndDate(2024, 12, 31);
        SetCash(100_000);
        // BTCUSD on Coinbase, minute resolution for fills.
        _btc = AddCrypto("BTCUSD", Resolution.Minute, Market.Coinbase);
        // Blockchain Bitcoin Metadata for daily on-chain network readings.
        _metadata = AddData<BitcoinMetadata>(_btc.Symbol, Resolution.Daily).Symbol;
        // Warm up the ROC from history so we can trade from the first bar.
        var history = History<BitcoinMetadata>(_metadata, 30, Resolution.Daily);
        foreach (var data in history)
        {
            Update(data);
        }
    }

    private void Update(BitcoinMetadata data)
    {
        var blockTime = data.MedianTransactionConfirmationTime;
        if (blockTime > 0)
        {
            _roc.Update(data.EndTime, blockTime);
            _lastMetadataTime = data.EndTime;
        }
    }

    public override void OnData(Slice slice)
    {
        // Daily metadata feed: only react when a fresh reading arrives.
        if (slice.TryGet<BitcoinMetadata>(_metadata, out var data))
        {
            Update(data);
        }
        if (!_roc.IsReady)
        {
            return;
        }
        // Stop trading if the feed has gone stale (e.g. provider outage).
        if (_lastMetadataTime == null || Time - _lastMetadataTime > _staleThreshold)
        {
            Liquidate(_btc.Symbol);
            return;
        }
        // Faster blocks (block time trending down) -> long; slower -> flat.
        if (_roc.Current.Value < 0m)
        {
            SetHoldings(_btc.Symbol, 1m);
        }
        else
        {
            Liquidate(_btc.Symbol);
        }
    }
}
