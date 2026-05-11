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
public class CryptoBTCFedFundsToggleAlgorithm : QCAlgorithm
{
    private Crypto _btc, _safe;
    private Symbol _fedfunds;
    private RateOfChange _roc;

    public override void Initialize()
    {
        SetStartDate(2024, 1, 1);
        SetEndDate(2024, 12, 31);
        SetCash(100000);
        // Trade BTCUSD on Kraken, rotating to PAXGUSD (gold-backed) when the rate cycle turns hawkish.
        _btc = AddCrypto("BTCUSD", market: Market.Kraken);
        _safe = AddCrypto("PAXGUSD", market: Market.Kraken);
        // Subscribe to FRED's Federal Funds Effective Rate (series ID FEDFUNDS).
        _fedfunds = AddData<Fred>("FEDFUNDS", Resolution.Daily).Symbol;
        // 3-period rate of change ~ 3-month change in the Fed Funds Rate.
        _roc = new RateOfChange(3);
        // FEDFUNDS publishes monthly, so let's warm up the indicator.
        var history = History<Fred>(_fedfunds, 365, Resolution.Daily);
        foreach (var dataPoint in history)
        {
            _roc.Update(dataPoint.Time, dataPoint.Value);
        }
    }

    public override void OnData(Slice slice)
    {
        // FRED only emits on publication days; act when a fresh print arrives.
        if (!slice.ContainsKey(_fedfunds))
            return;
        var data = slice.Get<Fred>(_fedfunds);
        if (data == null || !_roc.Update(data.Time, data.Value))
            return;
        // Negative 3-month change = easing cycle = risk-on for BTC.
        if (_roc.Current.Value < 0 && !_btc.Invested)
        {
            SetHoldings(_btc.Symbol, 1, true);
        }
        // Non-negative = flat or hiking = rotate to PAXG (gold-backed safe asset).
        else if (_roc.Current.Value >= 0 && !_safe.Invested)
        {
            SetHoldings(_safe.Symbol, 1, true);
        }
    }
}
