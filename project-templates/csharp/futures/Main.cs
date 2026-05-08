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

public class BasicFutureAlgorithm : QCAlgorithm
{
    private Future _future;
    public override void Initialize()
    {
        SetStartDate(2024, 9, 1);
        SetEndDate(2024, 12, 31);
        UniverseSettings.Asynchronous = true;
        _future = AddFuture(
            Futures.Indices.SP500EMini,
            extendedMarketHours: true,
            dataMappingMode: DataMappingMode.LastTradingDay,
            dataNormalizationMode: DataNormalizationMode.BackwardsRatio,
            contractDepthOffset: 0
        );
        _future.SetFilter(0, 92);
    }

    public override void OnData(Slice data)
    {
        if (Portfolio.Invested)
        {
            return;
        }
        MarketOrder(_future.Mapped, 1);
    }

    // Track events when security changes its ticker, allowing the algorithm to adapt to these changes.
    public override void OnSymbolChangedEvents(SymbolChangedEvents symbolChangedEvents)
    {
        foreach (var (symbol, changedEvent) in symbolChangedEvents)
        {
            var oldSymbol = Symbol(changedEvent.OldSymbol);
            var newSymbol = Symbol(changedEvent.NewSymbol);
            var quantity = Portfolio[oldSymbol].Quantity;

            // Rolling over: to liquidate any position of the old mapped contract and switch to the newly mapped contract.
            var tag = $"Rollover - Symbol changed at {Time}: {oldSymbol.Value} -> {newSymbol.Value}";
            Liquidate(symbol: oldSymbol, tag: tag);
            if (quantity != 0) MarketOrder(newSymbol, quantity, tag: tag);
        }
    }
}
