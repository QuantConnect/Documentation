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
public class WilliamsPercentRIWMAlgorithm : QCAlgorithm
{
    private const decimal _wmrBuyThreshold = -80m;
    private const decimal _wmrSellThreshold = -20m;
    private const int _maxHoldDays = 5;

    private Symbol _symbol;
    private WilliamsPercentR _wmr;
    private int _holdDays;

    public override void Initialize()
    {
        SetStartDate(2022, 1, 1);
        SetEndDate(2024, 12, 31);
        SetCash(100000);
        // AutomaticIndicatorWarmUp only supports automatic indicators, not manual indicators.
        Settings.AutomaticIndicatorWarmUp = true;

        _symbol = AddEquity("IWM", Resolution.Minute).Symbol;
        _wmr = WILR(_symbol, 14, Resolution.Minute);
        // Alternatively, use a manual indicator.
        // _wmr = new WilliamsPercentR(14);
        // WarmUpIndicator<IndicatorDataPoint>(_symbol, _wmr);
        // RegisterIndicator(_symbol, _wmr);

        Schedule.On(
            DateRules.EveryDay(_symbol),
            TimeRules.BeforeMarketClose(_symbol, 1),
            CheckHoldDuration
        );
    }

    public override void OnData(Slice data)
    {
        if (!_wmr.IsReady)
        {
            return;
        }

        var wmrValue = _wmr.Current.Value;
        var quantity = Portfolio[_symbol].Quantity;

        if (Time.Minute % 10 == 0)
        {
            PlotWilliams(wmrValue);
        }

        if (quantity > 0 && wmrValue > _wmrSellThreshold)
        {
            Liquidate(_symbol);
            _holdDays = 0;
            PlotWilliams(wmrValue);
            return;
        }

        if (quantity <= 0 && wmrValue < _wmrBuyThreshold)
        {
            SetHoldings(_symbol, 1.0m);
            _holdDays = 0;
            PlotWilliams(wmrValue);
        }
    }

    private void PlotWilliams(decimal wmrValue)
    {
        Plot("Williams %R", "IWM", wmrValue);
        Plot("Williams %R", "-80", _wmrBuyThreshold);
        Plot("Williams %R", "-20", _wmrSellThreshold);
    }

    private void CheckHoldDuration()
    {
        if (Portfolio[_symbol].Quantity > 0)
        {
            _holdDays += 1;
            if (_holdDays >= _maxHoldDays)
            {
                Liquidate(_symbol);
                _holdDays = 0;
            }
        }
    }
}
