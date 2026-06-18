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
public class EquityMacdHistogramDivergenceAlgorithm : QCAlgorithm
{
    private Equity _equity;
    private MovingAverageConvergenceDivergence _macd;

    public override void Initialize()
    {
        SetStartDate(2024, 9, 1);
        SetEndDate(2024, 12, 31);
        SetCash(100000);
        // Add AAPL with daily resolution.
        _equity = AddEquity("AAPL");
        // Create MACD indicator with standard parameters.
        _macd = MACD(_equity.Symbol, 12, 26, 9, MovingAverageType.Exponential, Resolution.Daily);
        // Alternatively, use a manual indicator.
        // _macd = new MovingAverageConvergenceDivergence(12, 26, 9, MovingAverageType.Exponential);
        // WarmUpIndicator(_equity.Symbol, _macd, Resolution.Daily);
        // RegisterIndicator(_equity.Symbol, _macd, Resolution.Daily);
        // Warm up the indicator and its windows using historical data.
        IndicatorHistory(_macd, _equity.Symbol, _macd.WarmUpPeriod + 1);
        // Plot MACD components for visualization.
        PlotIndicator("MACD", _macd, _macd.Signal);
        PlotIndicator("Histogram", _macd.Histogram);
    }

    public override void OnData(Slice data)
    {
        // Wait for indicator to be ready and have at least 2 values in windows.
        if (_macd.Samples < _macd.WarmUpPeriod + 1)
        {
            return;
        }
        // Get current and previous histogram values from built-in window.
        var histogramNow = _macd.Histogram.Window[0].Value;
        var histogramPrev = _macd.Histogram.Window[1].Value;
        // Detect zero crossovers in the histogram.
        var crossedAbove = histogramPrev <= 0m && histogramNow > 0m;
        var crossedBelow = histogramPrev >= 0m && histogramNow < 0m;
        // Check if signal line is rising for confirmation.
        var signalRising = _macd.Signal.Window[0].Value > _macd.Signal.Window[1].Value;
        // Entry: Buy when histogram crosses above zero and signal is rising.
        if (!_equity.Invested && crossedAbove && signalRising)
        {
            SetHoldings(_equity.Symbol, 1m);
        }
        // Exit: Sell when histogram crosses below zero.
        else if (_equity.Invested && crossedBelow)
        {
            Liquidate();
        }
    }
}
