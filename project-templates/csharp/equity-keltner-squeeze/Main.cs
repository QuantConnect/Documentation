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
public class EquityKeltnerSqueezeAlgorithm : QCAlgorithm
{
    private decimal _weight;

    public override void Initialize()
    {
        SetStartDate(2024, 9, 1);
        SetEndDate(2024, 12, 31);
        SetCash(100000);
        Settings.AutomaticIndicatorWarmUp = true;
        // Trade multiple ETFs.
        var tickers = new[] { "SPY", "QQQ", "IWM", "DIA" };
        _weight = 1m / tickers.Length;
        foreach (var ticker in tickers)
        {
            dynamic equity = AddEquity(ticker);
            // BB with 1.5 std devs and KC with 2.0 ATR multiplier for squeeze detection.
            equity.BB = BB(equity.Symbol, 20, 1.5m, resolution: Resolution.Daily);
            // Alternatively, use a manual indicator.
            // equity.BB = new BollingerBands(20, 1.5m);
            // WarmUpIndicator(equity.Symbol, equity.BB, Resolution.Daily);
            // RegisterIndicator(equity.Symbol, equity.BB, Resolution.Daily);
            equity.KC = KCH(equity.Symbol, 20, 2.0m, resolution: Resolution.Daily);
            // Alternatively, use a manual indicator.
            // equity.KC = new KeltnerChannels(20, 2.0m);
            // WarmUpIndicator(equity.Symbol, equity.KC, Resolution.Daily);
            // RegisterIndicator(equity.Symbol, equity.KC, Resolution.Daily);
            equity.InSqueeze = false;
            equity.Armed = false;
        }
        // Add a Schedule Event to scan for trades daily.
        Schedule.On(
            DateRules.EveryDay("SPY"),
            TimeRules.AfterMarketOpen("SPY", 1),
            Rebalance
        );
    }

    private void Rebalance()
    {
        foreach (dynamic security in Securities.Values)
        {
            if (!security.BB.IsReady || !security.KC.IsReady)
            {
                continue;
            }
            var bbUpper = security.BB.UpperBand.Current.Value;
            var bbLower = security.BB.LowerBand.Current.Value;
            var bbMiddle = security.BB.MiddleBand.Current.Value;
            var kcUpper = security.KC.UpperBand.Current.Value;
            var kcLower = security.KC.LowerBand.Current.Value;
            var price = security.Price;
            var inSqueeze = bbUpper < kcUpper && bbLower > kcLower;
            if (inSqueeze && !security.InSqueeze)
            {
                security.Armed = true;
            }
            security.InSqueeze = inSqueeze;
            // Manage any open position: exit when price reverts to the BB midline.
            var holding = security.Holdings;
            if (holding.Invested)
            {
                if ((holding.Quantity > 0 && price <= bbMiddle) ||
                    (holding.Quantity < 0 && price >= bbMiddle))
                {
                    Liquidate(security.Symbol);
                }
                continue;
            }
            // Wait for the squeeze to release before acting on a BB break.
            if (inSqueeze || !security.Armed)
            {
                continue;
            }
            // Equal weight allocation across all securities.
            if (price > bbUpper)
            {
                SetHoldings(security.Symbol, _weight);
                security.Armed = false;
            }
            else if (price < bbLower)
            {
                SetHoldings(security.Symbol, -_weight);
                security.Armed = false;
            }
        }
    }
}
