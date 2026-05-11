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

using Accord.MachineLearning.VectorMachines.Learning;

/// <summary>
/// Machine Learning example using Accord VectorMachines Learning
/// In this example, the algorithm forecasts the direction based on the last 5 days of rate of return
/// </summary>
public class AccordVectorMachinesAlgorithm : QCAlgorithm
{
    // Define the training data dimensions for the SVM model.
    // Use 30 historical periods with 5 rate of change values each.
    // The model predicts direction based on signed ROC values.
    private const int _lookback = 30;
    private const int _inputSize = 5;
    private Equity _equity;
    private RateOfChange _roc;

    public override void Initialize()
    {
        SetStartDate(2024, 9, 1);
        SetEndDate(2024, 12, 31);
        SetCash(100000);
        Settings.SeedInitialPrices = true;
        Accord.Math.Random.Generator.Seed = 0;
        _equity = AddEquity("SPY");
        _roc = ROC(_equity, 1, Resolution.Daily);
        _roc.Window.Size = _inputSize * _lookback + 2;
        Schedule.On(
            DateRules.Every(DayOfWeek.Monday),
            TimeRules.AfterMarketOpen(_equity, 10),
            TrainAndTrade
        );
        SetWarmUp(_roc.Window.Size, Resolution.Daily);
    }

    private void TrainAndTrade()
    {
        if (!_roc.Window.IsReady)
        {
            return;
        }
        // Prepare training data from the ROC window for the SVM model.
        var targets = new double[_lookback];
        var inputs = new double[_lookback][];
        // Extract signed ROC values as input features for each training sample.
        for (var i = 0; i < _lookback; i++)
        {
            var returns = new double[_inputSize];
            for (var j = 0; j < _inputSize; j++)
            {
                returns[j] = Math.Sign(_roc.Window[i + j + 1]);
            }
            targets[i] = Math.Sign(_roc.Window[i]);
            inputs[i] = returns;
        }
        // Train the Support Vector Machine using Linear Coordinate Descent.
        var teacher = new LinearCoordinateDescent();
        teacher.Learn(inputs, targets);
        var svm = teacher.Model;
        // Score the current market direction using the trained model.
        var value = svm.Score([Math.Sign(_roc.Window[0])]);
        if (value.IsNaNOrZero()) return;
        SetHoldings(_equity, percentage: Math.Sign(value));
    }
}
