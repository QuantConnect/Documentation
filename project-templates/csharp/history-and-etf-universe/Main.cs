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
public class ETFUniverseAlgorithm : QCAlgorithm
{
    private Universe _universe;
    private readonly Dictionary<Symbol, decimal> _weightBySymbol = [];

    public override void Initialize()
    {
        SetStartDate(2024, 9, 1);
        SetEndDate(2024, 12, 31);
        SetCash(100000);
        Settings.SeedInitialPrices = true;
        // Select QQQ constituents first, then by fundamental data.
        _universe = AddUniverse(Universe.ETF("QQQ", ETFConstituentsFilter));
        Schedule.On(DateRules.EveryDay("QQQ"), TimeRules.BeforeMarketOpen("QQQ", 30), PlaceOrders);
    }

    private IEnumerable<Symbol> ETFConstituentsFilter(IEnumerable<ETFConstituentUniverse> constituents)
    {
        var atrBySymbol = constituents.Select(
            c =>
            {
                var atr = new AverageTrueRange(14);
                WarmUpIndicator(c.Symbol, atr, Resolution.Daily);
                return new {c.Symbol, ATR = atr};
            })
            .OrderByDescending(x => x.ATR)
            .Take(10)
            .ToDictionary(x => x.Symbol, x => x.ATR);

        // Select all QQQ constituents by high ATR value.
        _weightBySymbol.Clear();
        constituents
            .Where(c=> atrBySymbol.ContainsKey(c.Symbol))
            .DoForEach(c => _weightBySymbol.Add(c.Symbol, c.Weight ?? 0));

        return atrBySymbol.Keys;
    }

    private void PlaceOrders()
    {
        // We will keep the ETF weights by scale it up to sum 1.
        var sumOfWeight = _universe.Selected.Sum(x => _weightBySymbol[x]);
        Plot("Universe", "Sum Of Weight (%)", sumOfWeight * 100m);
        var targets = _universe.Selected.Select(x => new PortfolioTarget(x, _weightBySymbol[x] / sumOfWeight)).ToList();
        SetHoldings(targets, true);
    }
}
