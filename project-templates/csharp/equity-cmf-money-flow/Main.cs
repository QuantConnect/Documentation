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
public class ChaikinMoneyFlowXlfAlgorithm : QCAlgorithm
{
    private const decimal _longThreshold = 0.10m;
    private const decimal _shortThreshold = -0.10m;

    private Equity _xlf;
    private ChaikinMoneyFlow _cmf;
    private int _previousSignal = 0;  // 1 = long, -1 = short, 0 = flat

    public override void Initialize()
    {
        SetStartDate(2022, 1, 1);
        SetEndDate(2024, 12, 31);
        SetCash(100000);

        // AutomaticIndicatorWarmUp only supports automatic indicators, not manual indicators.

        Settings.AutomaticIndicatorWarmUp = true;

        _xlf = AddEquity("XLF", Resolution.Minute);
        _cmf = CMF(_xlf.Symbol, 20, Resolution.Minute);
        // Alternatively, use a manual indicator.
        // _cmf = new ChaikinMoneyFlow(20);
        // WarmUpIndicator<IndicatorDataPoint>(_xlf.Symbol, _cmf);
        // RegisterIndicator(_xlf.Symbol, _cmf);
        PlotIndicator("CMF", _cmf);

        Schedule.On(
            DateRules.EveryDay(_xlf.Symbol),
            TimeRules.AfterMarketOpen(_xlf.Symbol, 30),
            Rebalance
        );
    }

    private void Rebalance()
    {
        if (!_cmf.IsReady)
        {
            return;
        }

        var cmfValue = _cmf.Current.Value;
        var signal = 0;
        if (cmfValue > _longThreshold)
        {
            signal = 1;
        }
        else if (cmfValue < _shortThreshold)
        {
            signal = -1;
        }

        if (signal == _previousSignal)
        {
            return;
        }

        _previousSignal = signal;

        if (signal == 1)
        {
            SetHoldings(_xlf.Symbol, 1m);
        }
        else if (signal == -1)
        {
            SetHoldings(_xlf.Symbol, -1m);
        }
        else
        {
            Liquidate(_xlf.Symbol);
        }
    }
}
