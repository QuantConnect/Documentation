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
public class ObvDivergenceAlgorithm : QCAlgorithm
{
    private Equity _spy;
    private OnBalanceVolume _obv;
    private RateOfChange _priceRoc;
    private RateOfChange _obvRoc;
    private int _bullishCount = 0;
    private int _bearishCount = 0;
    private const int _requiredConfirmation = 5;

    public override void Initialize()
    {
        SetStartDate(2022, 1, 1);
        SetEndDate(2024, 12, 31);
        SetCash(100000);

        // AutomaticIndicatorWarmUp only supports automatic indicators, not manual indicators.

        Settings.AutomaticIndicatorWarmUp = true;

        _spy = AddEquity("SPY", Resolution.Minute);

        // OBV on SPY at daily resolution
        _obv = OBV(_spy.Symbol, Resolution.Daily);
        // Alternatively, use a manual indicator.
        // _obv = new OnBalanceVolume();
        // WarmUpIndicator<IndicatorDataPoint>(_spy.Symbol, _obv, Resolution.Daily);
        // RegisterIndicator(_spy.Symbol, _obv, Resolution.Daily);

        // Price ROC(20) on SPY at daily resolution
        _priceRoc = ROC(_spy.Symbol, 20, Resolution.Daily);
        // Alternatively, use a manual indicator.
        // _priceRoc = new RateOfChange(20);
        // WarmUpIndicator<IndicatorDataPoint>(_spy.Symbol, _priceRoc, Resolution.Daily);
        // RegisterIndicator(_spy.Symbol, _priceRoc, Resolution.Daily);

        // ROC of OBV using IndicatorExtensions
        _obvRoc = new RateOfChange(20).Of(_obv);

        // Plot indicators
        PlotIndicator("OBV", _obv);
        PlotIndicator("Price ROC", _priceRoc);
        PlotIndicator("OBV ROC", _obvRoc);

        Schedule.On(
            DateRules.EveryDay("SPY"),
            TimeRules.AfterMarketOpen("SPY", 30),
            CheckDivergence
        );
    }

    private void CheckDivergence()
    {
        if (!_priceRoc.IsReady || !_obvRoc.IsReady)
        {
            return;
        }

        var priceRocValue = _priceRoc.Current.Value;
        var obvRocValue = _obvRoc.Current.Value;

        var bullishDivergence = priceRocValue < 0m && obvRocValue > 0m;
        var bearishDivergence = priceRocValue > 0m && obvRocValue < 0m;

        var quantity = _spy.Holdings.Quantity;

        if (bullishDivergence)
        {
            _bullishCount += 1;
            _bearishCount = 0;
        }
        else if (bearishDivergence)
        {
            _bearishCount += 1;
            _bullishCount = 0;
        }
        else
        {
            _bullishCount = 0;
            _bearishCount = 0;
            if (quantity != 0)
            {
                Liquidate(_spy.Symbol);
            }
            return;
        }

        if (_bullishCount >= _requiredConfirmation && quantity <= 0)
        {
            SetHoldings(_spy.Symbol, 1m);
        }
        else if (_bearishCount >= _requiredConfirmation && quantity >= 0)
        {
            SetHoldings(_spy.Symbol, -1m);
        }
    }
}
