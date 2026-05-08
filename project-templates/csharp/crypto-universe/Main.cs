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

public class LargeCapCryptoUniverseAlgorithm : QCAlgorithm
{
    private Universe _universe;

    public override void Initialize()
    {
        SetStartDate(2024, 9, 1);
        SetEndDate(2024, 12, 31);
        Settings.SeedInitialPrices = true;
        // Set the account currency to USDT.
        SetAccountCurrency("USDT");
        SetBrokerageModel(BrokerageName.Coinbase, AccountType.Cash);

        // Add the Crypto universe and define the selection function.
        _universe = AddUniverse(
            CryptoUniverse.Coinbase(data => data
                .Where(x => x.VolumeInUsd != null && x.Symbol.Value.EndsWith(AccountCurrency))
                .OrderByDescending(x => x.VolumeInUsd)
                .Take(10)
                .Select(x => x.Symbol)
            )
        );
        // Add a Sheduled Event to rebalance the portfolio.
        Schedule.On(DateRules.EveryDay(), TimeRules.At(12, 0), Rebalance);
    }

    private void Rebalance()
    {
        if (_universe.Selected == null || _universe.Selected.Count == 0)
        {
            return;
        }
        // Form an equal weighted portfolio of the coins in the universe.
        var targets = _universe.Selected.Select(pair => new PortfolioTarget(pair, 0.5m/_universe.Selected.Count())).ToList();
        // Place orders to rebalance the portfolio.
        SetHoldings(targets, true);
    }
}
