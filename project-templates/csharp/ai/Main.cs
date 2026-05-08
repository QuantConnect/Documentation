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
    // Define the size of the data used to train the model.
    // It will use _lookback sets with _inputSize members.
    // Those members are rate of return.
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

        Schedule.On(DateRules.Every(DayOfWeek.Monday),
            TimeRules.AfterMarketOpen(_equity, 10),
            TrainAndTrade);

        SetWarmUp(_roc.Window.Size, Resolution.Daily);
    }

    private void TrainAndTrade()
    {
        if (!_roc.Window.IsReady)
        {
            return;
        }

        // Convert the rolling window of rate of change into the Learn method.
        var targets = new double[_lookback];
        var inputs = new double[_lookback][];

        // Use the sign of the returns to predict the direction.
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

        // Train SupportVectorMachine using SetHoldings("SPY", percentage);.
        var teacher = new LinearCoordinateDescent();
        teacher.Learn(inputs, targets);

        var svm = teacher.Model;

        // Compute the value for the last rate of change.
        var value = svm.Score(new double[] { Math.Sign(_roc.Window[0]) });
        if (value.IsNaNOrZero()) return;

        SetHoldings(_equity, percentage: Math.Sign(value));
    }
}
