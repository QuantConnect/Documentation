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

public class EODHDMacroGDPGrowthRotationAlgorithm : QCAlgorithm
{
    private List<Equity> _etfs = [];

    public override void Initialize()
    {
        SetStartDate(2018, 1, 1);
        SetEndDate(2024, 12, 31);
        SetCash(100000);
        
        // Pair each country's GDP-growth indicator with a tradable country ETF.
        var indicatorByEtf = new Dictionary<string, string>
        {
            { "SPY", EODHD.MacroIndicators.UnitedStates.GdpGrowthAnnual },
            { "EWJ", EODHD.MacroIndicators.Japan.GdpGrowthAnnual },
            { "EWG", EODHD.MacroIndicators.Germany.GdpGrowthAnnual },
            { "EWU", EODHD.MacroIndicators.UnitedKingdom.GdpGrowthAnnual },
            { "EWA", EODHD.MacroIndicators.Australia.GdpGrowthAnnual },
            { "EWC", EODHD.MacroIndicators.Canada.GdpGrowthAnnual },
            { "MCHI", EODHD.MacroIndicators.China.GdpGrowthAnnual },
            { "INDA", EODHD.MacroIndicators.India.GdpGrowthAnnual }
        };
        // Daily resolution on the ETFs is plenty for an annual rebalance cadence.
        foreach (var kvp in indicatorByEtf)
        {
            dynamic etf = AddEquity(kvp.Key, Resolution.Hour);
            etf.IndicatorSymbol = AddData<EODHDMacroIndicators>(kvp.Value).Symbol;
            _etfs.Add(etf);
        }
    }

    public override void OnData(Slice slice)
    {
        // Most slices carry no new macro reading; only react when at least one fires.
        var gdpGrowthByEtf = new Dictionary<Equity, decimal>();
        foreach (dynamic etf in _etfs)
        {
            if (!slice.ContainsKey(etf.IndicatorSymbol))
                continue;
            var indicators = slice.Get<EODHDMacroIndicators>(etf.IndicatorSymbol);
            if (indicators == null || indicators.Data == null)
                continue;
            // A single release can deliver several points (revisions); keep the last value.
            foreach (var point in indicators.Data)
            {
                gdpGrowthByEtf[etf] = point.Value;
            }
        }
        if (gdpGrowthByEtf.Count == 0)
            return;
        // Long the 3 fastest-growing economies' ETFs, equal-weighted.
        var topEtfs = gdpGrowthByEtf
            .OrderBy(kvp => kvp.Value)
            .TakeLast(3)
            .Select(kvp => kvp.Key)
            .ToList();
        var weight = 1m / topEtfs.Count;
        var targets = topEtfs.Select(etf => new PortfolioTarget(etf.Symbol, weight)).ToList();
        SetHoldings(targets, true);
    }
}
