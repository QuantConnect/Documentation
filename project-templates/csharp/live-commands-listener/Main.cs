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

public class CommandPauseEmaCrossAlgorithm : QCAlgorithm
{
    private Symbol _spy;
    private ExponentialMovingAverage _emaFast;
    private ExponentialMovingAverage _emaSlow;
    private bool _paused;

    public override void Initialize()
    {
        SetStartDate(2024, 9, 1);
        SetEndDate(2024, 12, 31);
        SetCash(100000);

        // Request SPY at minute resolution so OnData (and the pause check) fires every minute
        _spy = AddEquity("SPY", Resolution.Minute).Symbol;

        // Enable automatic indicator warm-up
        Settings.AutomaticIndicatorWarmUp = true;

        // Create EMA indicators for trend detection
        _emaFast = EMA(_spy, 50, Resolution.Daily);
        _emaSlow = EMA(_spy, 200, Resolution.Daily);

        // Restore pause state from Object Store so the flag survives restarts
        if (ObjectStore.ContainsKey("paused"))
        {
            var stored = ObjectStore.Read("paused");
            if (!string.IsNullOrEmpty(stored))
            {
                _paused = stored.Trim().ToLowerInvariant() == "true";
                Log($"Restored pause state: paused={_paused}");
            }
        }
    }

    public override void OnData(Slice slice)
    {
        // Do not trade while paused
        if (_paused) return;

        // Wait for indicators to warm up
        if (!_emaFast.IsReady || !_emaSlow.IsReady) return;

        var fast = _emaFast.Current.Value;
        var slow = _emaSlow.Current.Value;

        // EMA cross trend-following strategy
        if (fast > slow && !Portfolio[_spy].IsLong)
        {
            SetHoldings(_spy, 1.0);
            Log($"Buy SPY at {slice.Time}: EMA50={fast:F2} > EMA200={slow:F2}");
        }
        else if (fast < slow && !Portfolio[_spy].IsShort)
        {
            SetHoldings(_spy, -1.0);
            Log($"Short SPY at {slice.Time}: EMA50={fast:F2} < EMA200={slow:F2}");
        }
    }

    public override bool? OnCommand(dynamic data)
    {
        // Handles pause/resume payload sent via REST or LEAN CLI:
        // {"Command": "pause", "Paused": true|false}
        // Going through OnCommand (instead of a Command subclass) lets us
        // mutate algorithm-private state like _paused directly.
        // Sender-script docs:
        // https://www.quantconnect.com/docs/v2/writing-algorithms/live-trading/commands#06-Send-Commands-by-API
        if ((string)data.Command != "pause") return false;
        _paused = (bool)data.Paused;
        ObjectStore.Save("paused", _paused ? "true" : "false");
        Log($"OnCommand pause: paused={_paused}");
        return true;
    }
}
