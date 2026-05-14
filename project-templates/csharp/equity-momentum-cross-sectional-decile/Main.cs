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

public class SP500Momentum : QCAlgorithm
{
    private Universe _universe;

    private readonly int _lookback = 252;
    private readonly int _universeCap = 100;
    private readonly double _topDecilePct = 0.1;

    // One momentum indicator per constituent, fed from the universe stream.
    private readonly Dictionary<Symbol, MomentumPercent> _momentumBySymbol = new();

    public override void Initialize()
    {
        SetStartDate(2022, 1, 1);
        SetEndDate(2024, 12, 31);
        SetCash(250000);

        Settings.SeedInitialPrices = true;
        Settings.MinimumOrderMarginPortfolioPercentage = 0m;

        // Chain SPY ETF constituents into a fundamental universe.
        UniverseSettings.Resolution = Resolution.Minute;
        _universe = AddUniverse(Universe.ETF("SPY"), SelectAssets);

        // Warm up the per-symbol momentum indicators with daily bars.
        SetWarmUp(_lookback + 1, Resolution.Daily);

        // Monthly rebalance shortly after the open
        Schedule.On(DateRules.MonthStart("SPY"), TimeRules.At(9, 31), Rebalance);
    }

    private IEnumerable<Symbol> SelectAssets(IEnumerable<Fundamental> fundamentals)
    {
        var constituents = fundamentals.ToList();

        // Stream each constituent's adjusted price into its momentum indicator.
        var topByVolume = constituents
            .Where(f => 
            {
                if (!_momentumBySymbol.TryGetValue(f.Symbol, out var momentum))
                {
                    momentum = new MomentumPercent(_lookback);
                    _momentumBySymbol[f.Symbol] = momentum;
                }
                return momentum.Update(f.EndTime, f.AdjustedPrice);
            })
            // Cap the universe at the top 100 constituents by fundamental dollar volume.
            .OrderByDescending(f => f.DollarVolume)
            .Take(_universeCap)
            .ToList();

        if (IsWarmingUp || topByVolume.Count == 0)
        {
            return Universe.Unchanged;
        }

        // Select the top decile by momentum.
        var decileCount = Math.Max(1, (int)(topByVolume.Count * _topDecilePct));
        return topByVolume
            .OrderByDescending(f => _momentumBySymbol[f.Symbol])
            .Take(decileCount)
            .Select(f => f.Symbol);
    }

    private void Rebalance()
    {
        if (_universe.Selected.Count == 0)
        {
            return;
        }
        var weight = 1m / _universe.Selected.Count;
        var targets = _universe.Selected
            .Select(symbol => new PortfolioTarget(symbol, weight))
            .ToList();
        SetHoldings(targets, liquidateExistingHoldings: true);
    }
}