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

public class FutureOptionAlgorithm : QCAlgorithm
{
    private Future _underlying;

    public override void Initialize()
    {
        SetStartDate(2024, 9, 1);
        SetEndDate(2024, 12, 31);
        // Filter the underlying continuous Futures to narrow the FOP spectrum.
        _underlying = AddFuture(
            Futures.Indices.SP500EMini,
            extendedMarketHours: true,
            dataMappingMode: DataMappingMode.OpenInterest,
            dataNormalizationMode: DataNormalizationMode.BackwardsRatio,
            contractDepthOffset: 0
        );
        _underlying.SetFilter(0, 182);
        // Use CallSpread filter to obtain the 2 best-matched contracts that forms a call spread.
        // It simplifies from further filtering and reduce computation on redundant subscription.
        AddFutureOption(_underlying, (u) => u.CallSpread(5, 5, -5));
    }

    public override void OnData(Slice slice)
    {
        if (Portfolio.Invested)
            return;

        // Create canonical symbol for the mapped future contract, since we need that to access the option chain.
        var symbol = QuantConnect.Symbol.CreateCanonicalOption(_underlying.Mapped);

        // Get option chain data for the mapped future only.
        // It requires 2 contracts with different strikes to form a call spread, so we make sure at least 2 contracts are present.
        if (!slice.OptionChains.TryGetValue(symbol, out var chain) || chain.Count() < 2)
            return;

        // Separate the contracts by strike, as we need to access their strike.
        var expiry = chain.Min(x => x.Expiry);
        var itmStrike = chain.Min(x => x.Strike);
        var otmStrike = chain.Max(x => x.Strike);

        // Use abstraction method to order a bull call spread to avoid manual error.
        var optionStrategy = OptionStrategies.BullCallSpread(symbol, itmStrike, otmStrike, expiry);
        Buy(optionStrategy, 1);
    }
}
