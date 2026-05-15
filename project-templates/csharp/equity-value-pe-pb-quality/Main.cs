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

public class LowPePbHighRoeAlgorithm : QCAlgorithm
{
    private Universe _universe;

    public override void Initialize()
    {
        SetStartDate(2022, 1, 1);
        SetEndDate(2024, 12, 31);
        SetCash(250000);

        Settings.SeedInitialPrices = true;

        UniverseSettings.Resolution = Resolution.Minute;
        UniverseSettings.Leverage = 2.0m;

        _universe = AddUniverse(SelectFundamentals);

        Schedule.On(
            DateRules.MonthStart(),
            TimeRules.At(9, 35),
            Rebalance
        );
    }

    private IEnumerable<Symbol> SelectFundamentals(IEnumerable<Fundamental> fundamentals)
    {
        var filtered = new List<(Symbol Symbol, double Pe, double Pb, double Roe)>();
        foreach (var f in fundamentals)
        {
            var pe = (double)f.ValuationRatios.PERatio;
            var pb = (double)f.ValuationRatios.PBRatio;
            var roe = (double)f.OperationRatios.ROE.OneYear;

            if (f.MarketCap > 10_000_000_000L &&
                f.DollarVolume > 10_000_000.0 &&
                f.Price > 5.0m &&
                double.IsFinite(pe) && double.IsFinite(pb) && double.IsFinite(roe))
            {
                filtered.Add((f.Symbol, pe, pb, roe));
            }
        }

        if (filtered.Count < 20)
        {
            return Universe.Unchanged;
        }

        var peValues = filtered.Select(x => -x.Pe).ToList();
        var pbValues = filtered.Select(x => -x.Pb).ToList();
        var roeValues = filtered.Select(x => x.Roe).ToList();

        var zPe = ZScore(peValues);
        var zPb = ZScore(pbValues);
        var zRoe = ZScore(roeValues);

        return filtered
            .Select((entry, i) => (entry.Symbol, Composite: (zPe[i] + zPb[i] + zRoe[i]) / 3.0))
            .OrderByDescending(x => x.Composite)
            .Take(20)
            .Select(x => x.Symbol)
            .ToList();
    }

    private static List<double> ZScore(List<double> values)
    {
        var n = values.Count;
        var mean = values.Sum() / n;
        var variance = values.Sum(v => (v - mean) * (v - mean)) / n;
        var std = Math.Sqrt(variance);
        if (std == 0.0)
        {
            return Enumerable.Repeat(0.0, n).ToList();
        }
        return values.Select(v => (v - mean) / std).ToList();
    }

    public override void OnWarmupFinished()
    {
        Rebalance();
    }

    private void Rebalance()
    {
        var selected = _universe.Selected.ToList();
        if (selected.Count == 0) return;

        var weight = 1m / selected.Count;
        var targets = selected.Select(s => new PortfolioTarget(s, weight)).ToList();
        SetHoldings(targets, liquidateExistingHoldings: true);
    }
}
