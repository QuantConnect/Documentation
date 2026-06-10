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

public class SP500LowVolatility : QCAlgorithm
{
    private Universe _universe;
    private readonly int _lookback = 60;
    private readonly int _portfolioSize = 30;
    private List<Symbol> _selectedSymbols = [];
    private int _selectionYear;
    private int _selectionMonth;

    public override void Initialize()
    {
        SetStartDate(2022, 1, 1);
        SetEndDate(2024, 12, 31);
        SetCash(250000);
        Settings.SeedInitialPrices = true;
        Settings.MinimumOrderMarginPortfolioPercentage = 0m;
        UniverseSettings.Resolution = Resolution.Daily;
        _universe = AddUniverse(Universe.ETF("SPY", SelectAssets));
        Schedule.On(
            DateRules.MonthStart("SPY"),
            TimeRules.At(9, 31),
            Rebalance
        );
    }

    private IEnumerable<Symbol> SelectAssets(IEnumerable<ETFConstituentUniverse> constituents)
    {
        if (_selectionYear == Time.Year && _selectionMonth == Time.Month)
        {
            return Universe.Unchanged;
        }
        var volBySymbol = new Dictionary<Symbol, double>();
        foreach (var constituent in constituents)
        {
            if (!constituent.Weight.HasValue || constituent.Weight.Value == 0m)
            {
                continue;
            }
            var history = History<TradeBar>(constituent.Symbol, _lookback, Resolution.Daily).ToList();
            if (history.Count < _lookback)
            {
                continue;
            }
            var closes = history.Select(bar => (double)bar.Close).ToList();
            var returns = new List<double>();
            for (var i = 1; i < closes.Count; i++)
            {
                if (closes[i - 1] == 0)
                {
                    returns.Clear();
                    break;
                }
                returns.Add((closes[i] / closes[i - 1]) - 1d);
            }
            if (returns.Count < _lookback - 1)
            {
                continue;
            }
            var volatility = SampleStandardDeviation(returns);
            if (volatility <= 0)
            {
                continue;
            }
            volBySymbol[constituent.Symbol] = volatility;
        }
        _selectedSymbols = volBySymbol
            .OrderBy(kvp => kvp.Value)
            .Take(_portfolioSize)
            .Select(kvp => kvp.Key)
            .ToList();
        _selectionYear = Time.Year;
        _selectionMonth = Time.Month;
        return _selectedSymbols;
    }

    private void Rebalance()
    {
        if (_selectedSymbols.Count == 0)
        {
            Liquidate();
            return;
        }
        var weight = 1m / _selectedSymbols.Count;
        var targets = _selectedSymbols
            .Select(symbol => new PortfolioTarget(symbol, weight))
            .ToList();
        SetHoldings(targets, liquidateExistingHoldings: true);
    }

    private static double SampleStandardDeviation(List<double> values)
    {
        if (values.Count < 2)
        {
            return 0;
        }
        var mean = values.Average();
        var sumSquares = 0d;
        foreach (var value in values)
        {
            var diff = value - mean;
            sumSquares += diff * diff;
        }
        return Math.Sqrt(sumSquares / (values.Count - 1));
    }
}
