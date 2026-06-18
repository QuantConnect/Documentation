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
public class EquityStochasticOscillatorAlgorithm : QCAlgorithm
{
    private Equity _equity;
    private Stochastic _stoch;

    public override void Initialize()
    {
        SetStartDate(2024, 9, 1);
        SetEndDate(2024, 12, 31);
        SetCash(100000);
        Settings.AutomaticIndicatorWarmUp = true;
        _equity = AddEquity("IWM");
        _stoch = STO(_equity.Symbol, 14, 3, 3, Resolution.Daily);
        // Alternatively, use a manual indicator.
        // _stoch = new Stochastic(14, 3, 3);
        // WarmUpIndicator(_equity.Symbol, _stoch, Resolution.Daily);
        // RegisterIndicator(_equity.Symbol, _stoch, Resolution.Daily);
    }

    public override void OnData(Slice data)
    {
        if (!_stoch.IsReady)
        {
            return;
        }
        var k = _stoch.StochK.Current.Value;
        var d = _stoch.StochD.Current.Value;
        var prevK = _stoch.StochK.Previous.Value;
        var prevD = _stoch.StochD.Previous.Value;
        var crossedUp = prevK <= prevD && k > d;
        var crossedDown = prevK >= prevD && k < d;
        var quantity = _equity.Holdings.Quantity;
        if (quantity == 0 && crossedUp && d < 20m)
        {
            SetHoldings(_equity.Symbol, 1m);
        }
        else if (quantity > 0 && crossedDown && d > 80m)
        {
            Liquidate();
        }
    }
}
