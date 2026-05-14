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
public class KOPepPairsTrading : QCAlgorithm
{
    private Symbol _ko;
    private Symbol _pep;

    private double _alpha = 0.0;
    private double _beta = 1.0;
    private double _spreadMean = 0.0;
    private double _spreadStd = 1.0;
    private int _state = 0;  // 0: flat, 1: long spread, -1: short spread

    // Demonstrate RollingWindow usage by tracking recent z-scores
    private readonly RollingWindow<double> _zWindow = new RollingWindow<double>(5);

    public override void Initialize()
    {
        SetStartDate(2022, 1, 1);
        SetEndDate(2024, 12, 31);
        SetCash(100000);

        var ko = AddEquity("KO", Resolution.Minute);
        var pep = AddEquity("PEP", Resolution.Minute);
        _ko = ko.Symbol;
        _pep = pep.Symbol;

        // Cache 60 daily market sessions per security to fit the OLS
        // hedge ratio without making history requests.
        ko.Session.Size = 60;
        pep.Session.Size = 60;

        SetWarmUp(60, Resolution.Daily);

        // Re-fit the hedge ratio weekly on the first trading day of the week
        Schedule.On(DateRules.WeekStart(), TimeRules.At(9, 31), UpdateModel);

        // Evaluate the spread signal daily shortly after the open
        Schedule.On(DateRules.EveryDay(), TimeRules.At(10, 0), TradeLogic);
    }

    public override void OnWarmupFinished()
    {
        UpdateModel();
    }

    private void UpdateModel()
    {
        // Fit the OLS hedge ratio from the cached daily market sessions.
        var koSession = Securities[_ko].Session;
        var pepSession = Securities[_pep].Session;
        if (koSession.Count < 60 || pepSession.Count < 60)
        {
            return;
        }

        // Session windows iterate newest-first; reverse to chronological order.
        var koCloses = koSession.Select(x => (double)x.Close).Reverse().ToList();
        var pepCloses = pepSession.Select(x => (double)x.Close).Reverse().ToList();

        // OLS: KO = beta * PEP + alpha
        var n = pepCloses.Count;
        var pepMean = pepCloses.Average();
        var koMean = koCloses.Average();
        var covariance = 0.0;
        var variance = 0.0;
        for (var i = 0; i < n; i++)
        {
            covariance += (pepCloses[i] - pepMean) * (koCloses[i] - koMean);
            variance += (pepCloses[i] - pepMean) * (pepCloses[i] - pepMean);
        }
        _beta = covariance / variance;
        _alpha = koMean - _beta * pepMean;

        var spread = new double[n];
        for (var i = 0; i < n; i++)
        {
            spread[i] = koCloses[i] - (_beta * pepCloses[i] + _alpha);
        }
        _spreadMean = spread.Average();
        var sumSquares = 0.0;
        foreach (var value in spread)
        {
            sumSquares += (value - _spreadMean) * (value - _spreadMean);
        }
        _spreadStd = Math.Sqrt(sumSquares / n);

        if (_spreadStd == 0)
        {
            _spreadStd = 1e-6;
        }
    }

    private void TradeLogic()
    {
        if (IsWarmingUp || _spreadStd == 0)
        {
            return;
        }

        var koPrice = (double)Securities[_ko].Close;
        var pepPrice = (double)Securities[_pep].Close;

        var currentSpread = koPrice - (_beta * pepPrice + _alpha);
        var zScore = (currentSpread - _spreadMean) / _spreadStd;
        _zWindow.Add(zScore);

        if (zScore < -2 && _state != 1)
        {
            // Long spread -> long KO, short PEP (dollar-neutral)
            SetHoldings(new List<PortfolioTarget>
            {
                new PortfolioTarget(_ko, 0.5m),
                new PortfolioTarget(_pep, -0.5m)
            }, liquidateExistingHoldings: true);
            _state = 1;
        }
        else if (zScore > 2 && _state != -1)
        {
            // Short spread -> short KO, long PEP (dollar-neutral)
            SetHoldings(new List<PortfolioTarget>
            {
                new PortfolioTarget(_ko, -0.5m),
                new PortfolioTarget(_pep, 0.5m)
            }, liquidateExistingHoldings: true);
            _state = -1;
        }
        else if (Math.Abs(zScore) < 0.5 && _state != 0)
        {
            Liquidate();
            _state = 0;
        }
    }

    public override void OnEndOfAlgorithm()
    {
        Liquidate();
    }
}
