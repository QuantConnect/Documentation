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

public class UpcomingEarningsExampleAlgorithm : QCAlgorithm
{
    // A dictionary to save the underlying-call,put pair for position open/close management.
    private readonly Dictionary<Security, (Symbol Call, Symbol Put)> _optionsBySymbol = [];

    public override void Initialize()
    {
        SetStartDate(2024, 12, 1);
        SetEndDate(2024, 12, 31);
        SetCash(100000);

        // Seed the last price as price since we need to use the underlying price for option contract filtering when it join the universe.
        Settings.SeedInitialPrices = true;
        // Trade on daily basis based on daily upcoming earnings signals.
        UniverseSettings.Resolution = Resolution.Minute;
        // Option trading requires raw price for strike price comparison.
        UniverseSettings.DataNormalizationMode = DataNormalizationMode.Raw;
        // Universe consists of Equities with upcoming earnings events.
        AddUniverse<EODHDUpcomingEarnings>((earnings) => {
            foreach(var i in Enumerable.Range(1,5).Reverse())
            {
                var targetDate = Time.AddDays(i);
                var symbols = earnings.OfType<EODHDUpcomingEarnings>()
                    // We do not want to lock our fund too early, so filter for stocks is in lower volatility but will go up.
                    // Assuming 5 days before the earnings report publish is less volatile.
                    // We do not want depository due to their low liquidity.
                    .Where(datum => datum.ReportDate == targetDate)
                    .ToHashSet(datum => datum.Symbol);
                if (symbols.IsNullOrEmpty()) continue;
                Plot("Universe", "Count", symbols.Count);
                return symbols;
            }
            return [];
        });
    }

    public override void OnSecuritiesChanged(SecurityChanges changes)
    {
        // Actions only based on the Equity universe changes.
        foreach (var security in changes.RemovedSecurities.Where(x=> x.Type == SecurityType.Equity))
        {
            // Liquidate any assigned position.
            Liquidate(security);
            // Liquidate the option positions and capitalize the volatility 1-day after the earnings announcement.
            if (_optionsBySymbol.Remove(security, out var options))
            {
                RemoveOptionContract(options.Call);
                RemoveOptionContract(options.Put);
            }
        }

        // Actions only based on the Equity universe changes.
        foreach (var security in changes.AddedSecurities.Where(x=> x.Type == SecurityType.Equity))
        {
            // Select the option contracts to construct a straddle to trade the volatility.
            var (call, put) = SelectOptionContracts(security);
            if (call == null || put == null)
            {
                continue;
            }
            _optionsBySymbol[security] = (call, put);
            // Request the option contract data for trading.
            call = AddOptionContract(call).Symbol;
            put = AddOptionContract(put).Symbol;
            // Long a straddle by shorting the selected ATM call and put.
            ComboMarketOrder(
                [
                    Leg.Create(call, 1),
                    Leg.Create(put, 1)
                ],
                1
            );
        }
    }

    private (Symbol, Symbol) SelectOptionContracts(Security underlying)
    {
        // Get all tradable option contracts for filtering.
        var optionContractList = OptionChain(underlying);

        // Expiry at least 30 days later to have a smaller theta to reduce time decay loss.
        // Yet also be ensure liquidity over the volatility fluctuation hence we take the closet expiry after that.
        var longExpiries = optionContractList.Where(x => x.Expiry >= Time.AddDays(30)).ToList();
        if (longExpiries.Count < 2)
        {
            return (null, null);
        }
        var expiry = longExpiries.Min(x => x.Expiry);
        var filteredContracts = optionContractList.Where(x => x.Expiry == expiry).ToList();

        // Select ATM call and put to form a straddle for trading the volatility.
        var strike = filteredContracts.MinBy(x => Math.Abs(x.Strike - underlying.Price))
            .Strike;
        var atmContracts = filteredContracts.Where(x => x.Strike == strike).ToList();
        if (atmContracts.Count < 2)
        {
            return (null, null);
        }

        var atmCall = atmContracts.Single(x => x.Right == OptionRight.Call);
        var atmPut = atmContracts.Single(x => x.Right == OptionRight.Put);
        return (atmCall, atmPut);
    }
}
