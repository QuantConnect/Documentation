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
public class PermanentPortfolioAlgorithm : QCAlgorithm
{
    private readonly List<PortfolioTarget> _targets = new();
    private const decimal _weight = 0.25m;
    private const decimal _driftThreshold = 0.03m;

    public override void Initialize()
    {
        SetStartDate(2022, 1, 1);
        SetEndDate(2024, 12, 31);
        SetCash(100000);
        Settings.SeedInitialPrices = true;

        foreach (var ticker in new[] { "SPY", "TLT", "GLD", "SHY" })
        {
            var symbol = AddEquity(ticker, Resolution.Minute).Symbol;
            _targets.Add(new PortfolioTarget(symbol, _weight));
        }

        Schedule.On(
            DateRules.YearStart("SPY", 2),
            TimeRules.AfterMarketOpen("SPY", 5),
            Rebalance);
    }

    public override void OnWarmupFinished()
    {
        SetHoldings(_targets, liquidateExistingHoldings: true);
    }

    private void Rebalance()
    {
        foreach (var target in _targets)
        {
            var currentWeight = Portfolio[target.Symbol].HoldingsValue / Portfolio.TotalHoldingsValue;
            if (Math.Abs(currentWeight - _weight) > _driftThreshold)
            {
                SetHoldings(_targets, liquidateExistingHoldings: true);
                return;
            }
        }
    }
}
