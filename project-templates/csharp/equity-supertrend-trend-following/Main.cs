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
public class EquitySuperTrendTrendFollowingAlgorithm : QCAlgorithm
{
    private Equity _spy;
    private SuperTrend _superTrend;
    // Track the previous direction so we can act only on a flip.
    private int? _previousDirection;

    public override void Initialize()
    {
        SetStartDate(2024, 9, 1);
        SetEndDate(2024, 12, 31);
        SetCash(100_000);
        // Request SPY data to feed the SuperTrend indicator and trade.
        _spy = AddEquity("SPY");
        // Auto-updating SuperTrend (10-period ATR, 3x multiplier) — the helper wires it to the bar stream.
        _superTrend = STR(_spy.Symbol, 10, 3m, resolution: Resolution.Daily);
        // Alternatively, use a manual indicator.
        // _superTrend = new SuperTrend(10, 3m, MovingAverageType.Wilders);
        // WarmUpIndicator<IndicatorDataPoint>(_spy.Symbol, _superTrend, Resolution.Daily);
        // RegisterIndicator(_spy.Symbol, _superTrend, Resolution.Daily);
        // Register event handler to run trading logic when indicator updates.
        _superTrend.Updated += OnSuperTrendUpdated;
        // Warm up so the ATR and SuperTrend bands are valid before the first trade.
        SetWarmUp(_superTrend.WarmUpPeriod + 1, Resolution.Daily);
    }

    private void OnSuperTrendUpdated(object sender, IndicatorDataPoint updated)
    {
        // Direction is +1 when the SuperTrend line sits below price (bullish), -1 when above (bearish).
        if (!_superTrend.IsReady)
        {
            return;
        }
        var direction = _superTrend.Current.Value < _spy.Price ? 1 : -1;
        if (!IsWarmingUp && _previousDirection.HasValue)
        {
            // Long when SuperTrend sits below price; short when it sits above.
            if (_previousDirection < 0 && direction > 0)
            {
                SetHoldings(_spy.Symbol, 1d);
            }
            else if (_previousDirection > 0 && direction < 0)
            {
                SetHoldings(_spy.Symbol, -1d);
            }
            else if (!Portfolio.Invested)
            {
                SetHoldings(_spy.Symbol, 1d);
            }
        }
        _previousDirection = direction;
    }
}
