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
public class AtrChandelierTrailingStopAlgorithm : QCAlgorithm
{
    private Equity _spy;
    private AverageTrueRange _atr;
    private decimal _trailingHigh;
    private bool _waitingToReenter;

    public override void Initialize()
    {
        SetStartDate(2022, 1, 1);
        SetEndDate(2024, 12, 31);
        SetCash(100000);
        // AutomaticIndicatorWarmUp only supports automatic indicators, not manual indicators.
        Settings.AutomaticIndicatorWarmUp = true;

        _spy = AddEquity("SPY", Resolution.Minute);
        _atr = ATR(_spy.Symbol, 14);
        // Alternatively, use a manual indicator.
        // _atr = new AverageTrueRange(14);
        // WarmUpIndicator(_spy.Symbol, _atr);
        // RegisterIndicator(_spy.Symbol, _atr);
        _trailingHigh = 0m;
        _waitingToReenter = false;

        Plot("Stop", "ATR Stop", 0);
        Plot("Stop", "Trailing High", 0);
    }

    public override void OnData(Slice data)
    {
        if (!_atr.IsReady)
        {
            return;
        }

        if (!data.Bars.TryGetValue(_spy.Symbol, out var bar))
        {
            return;
        }

        var close = bar.Close;

        if (_waitingToReenter)
        {
            SetHoldings(_spy.Symbol, 1.0);
            _trailingHigh = close;
            _waitingToReenter = false;
            return;
        }

        if (!Portfolio.Invested)
        {
            SetHoldings(_spy.Symbol, 1.0);
            _trailingHigh = close;
            return;
        }

        if (close > _trailingHigh)
        {
            _trailingHigh = close;
        }

        var atrValue = _atr.Current.Value;
        var stopLevel = _trailingHigh - 3.0m * atrValue;

        Plot("Stop", "ATR Stop", stopLevel);
        Plot("Stop", "Trailing High", _trailingHigh);

        if (close < stopLevel)
        {
            Liquidate(_spy.Symbol);
            _waitingToReenter = true;
            _trailingHigh = 0m;
        }
    }
}
