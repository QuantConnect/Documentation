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
public class EquityParabolicSarTrailingTemplateAlgorithm : QCAlgorithm
{
    private Equity _equity;
    private ParabolicStopAndReverse _psar;

    public override void Initialize()
    {
        SetStartDate(2024, 9, 1);
        SetEndDate(2024, 12, 31);
        SetCash(100000);
        Settings.AutomaticIndicatorWarmUp = true;
        _equity = AddEquity("SPY");
        _psar = PSAR(_equity.Symbol, 0.02m, 0.02m, 0.2m, Resolution.Daily);
        // Alternatively, use a manual indicator.
        // _psar = new ParabolicStopAndReverse(0.02m, 0.02m, 0.2m);
        // WarmUpIndicator(_equity.Symbol, _psar, Resolution.Daily);
        // RegisterIndicator(_equity.Symbol, _psar, Resolution.Daily);
        PlotIndicator("SPY", _psar);
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.Count == 0 || !_psar.IsReady)
        {
            return;
        }
        var price = _equity.Close;
        var sar = _psar.Current.Value;
        Plot("SPY", "Price", price);
        if (!_equity.Holdings.Invested && sar < price)
        {
            SetHoldings(_equity.Symbol, 1m);
        }
        else if (_equity.Holdings.Invested && sar > price)
        {
            Liquidate();
        }
    }
}
