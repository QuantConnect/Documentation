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

public class OptionChainFullExample : QCAlgorithm
{
    private Symbol _optionChainSymbol;
    private List<OptionContract> _chain = [];
    private const int _minStrike = -1;
    private const int _maxStrike = 1;

    public override void Initialize()
    {
        SetStartDate(2024, 9, 1);
        SetEndDate(2024, 12, 31);
        SetCash(500000);
        UniverseSettings.MinimumTimeInUniverse = TimeSpan.Zero;

        // Warm-up the option contracts as soon as it is added to the algorithm.
        Settings.SeedInitialPrices = true;

        // Create the option chain symbol for the SPXW weekly index option.
        var index = AddIndex("SPX");
        _optionChainSymbol = QuantConnect.Symbol.CreateCanonicalOption(index, "SPXW", Market.USA, "?SPXW");

        // Populate the updated option chain immediately to trade with.
        PopulateOptionChain();

        var dateRules = DateRules.EveryDay(_optionChainSymbol);
        // Set a schedule event to populate the option chain when the market opens since the option contracts are updated daily.
        Schedule.On(dateRules, TimeRules.AfterMarketOpen(_optionChainSymbol, 1), PopulateOptionChain);
        // Set a scheduled event to filter the closed ATM calls every 5 minutes.
        Schedule.On(dateRules, TimeRules.Every(TimeSpan.FromMinutes(5)), Filter);
    }

    private void PopulateOptionChain()
    {
        // Filter the expiry daily only since the contract list is updated daily.
        var chain = OptionChain(_optionChainSymbol);
        var expiry = chain.Min(x => x.Expiry);
        _chain = [.. chain.Where(x => x.Expiry==expiry)];
    }

    public void Filter()
    {
        if (_chain.IsNullOrEmpty())
            return;
        var underlying = Securities[_optionChainSymbol.Underlying];

        // Filter the contracts with strike range spread between the preset level.
        var strikes = _chain.Select(x => x.Strike).OrderBy(x => x).Distinct().ToList();
        var spot = underlying.Price;
        var atm = strikes.OrderBy(x => Math.Abs(spot - x)).FirstOrDefault();
        var index = strikes.IndexOf(atm);
        var minStrike = strikes[Math.Max(0, index + _minStrike)];
        var maxStrike = strikes[Math.Min(strikes.Count - 1, index + _maxStrike)];
        var contracts = _chain.Where(x => minStrike <= x.Strike && x.Strike <= maxStrike);

        // Request data of the newly identified ATM contracts.
        foreach (var contract in contracts)
        {
            AddOptionContract(contract);
        }
        // Since we are trading 0DTE, they will expire on end of day.
        // So we don't need to remove them explicitly.
    }

    public override void OnData(Slice slice)
    {
        // Only trade on updated data.
        if (!slice.OptionChains.TryGetValue(_optionChainSymbol, out var chain))
            return;

        // Filter the closest ATM call contract and trade.
        var expiry = chain.Min(x => x.Expiry);
        var atmCall = chain
            .Where(x => x.Expiry == expiry && x.Right == OptionRight.Call && Securities[x].IsTradable)
            .OrderBy(x => Math.Abs(chain.Underlying.Price - x.Strike))
            .FirstOrDefault();

        // We will buy the ATM call if we don't have it.
        // We are not selling the calls we have purchased previously.
        // So, we will buy a lot of contracts if underlying price moves a lot.
        if (atmCall != null && !Portfolio[atmCall].Invested)
            MarketOrder(atmCall, 1);
    }
}
