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
        Settings.AutomaticIndicatorWarmUp = true;
        // Select QQQ constituents first, then by fundamental data.
        _universe = AddUniverse(Universe.ETF("QQQ", ETFConstituentsFilter));
        Schedule.On(DateRules.EveryDay("QQQ"), TimeRules.Every(TimeSpan.FromMinutes(15)), PlaceOrders);
    }

    public override void OnSecuritiesChanged(SecurityChanges changes)
    {
        foreach (dynamic security in changes.AddedSecurities)
        {
            security.Atr = ATR(security, 60, resolution: Resolution.Minute);
        }
        foreach (dynamic security in changes.RemovedSecurities)
        {
            DeregisterIndicator(security.Atr);
        }

    }

    private IEnumerable<Symbol> ETFConstituentsFilter(IEnumerable<ETFConstituentUniverse> constituents)
    {
        // Select all QQQ constituents by high ATR value.
        _weightBySymbol.Clear();
        constituents.DoForEach(c => _weightBySymbol.Add(c.Symbol, c.Weight ?? 0));
        return _weightBySymbol.Keys;
    }

    private void PlaceOrders()
    {
        if (!(IsMarketOpen("QQQ") && _universe.Selected != null))
        {
            return;
        }
        var selected = _universe.Selected
            .Select(symbol => Securities[symbol] as dynamic)
            .Where(security => security.Atr.IsReady)
            .OrderBy(security => security.Atr)
            .TakeLast(10)
            .Select(security => security.Symbol as Symbol);
        // We will keep the ETF weights by scale it up to sum 1.
        var sumOfWeight = selected.Sum(x => _weightBySymbol[x]);
        if (sumOfWeight == 0m)
        {
            return;
        }
        Plot("Universe", "Sum Of Weight (%)", sumOfWeight * 100m);
        var targets = selected.Select(x => new PortfolioTarget(x, _weightBySymbol[x] / sumOfWeight)).ToList();
        // Remove securities that are not top ATR.
        SetHoldings(targets, true);
    }
}
