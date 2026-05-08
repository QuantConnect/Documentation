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
using System.Collections.Concurrent;

public class EquityOptionExampleAlgorithm : QCAlgorithm
{
    private Equity _spy;

    public override void Initialize()
    {
        SetStartDate(2024, 9, 1);
        SetEndDate(2024, 12, 31);
        SetCash(100000);
        // Seed the price of each asset with its last known price to avoid trading errors.
        Settings.SeedInitialPrices = true;
        // Set the data normalization mode as raw for option strike-price comparability.
        _spy = AddEquity("SPY", dataNormalizationMode: DataNormalizationMode.Raw);

        Schedule.On(DateRules.Every(DayOfWeek.Monday), TimeRules.AfterMarketOpen(_spy, 1), BuyCoveredCall);
    }

    /// <summary>
    /// Buy a Covered Call: Buy the underlying and sell the ATM call.
    /// </summary>
    private void BuyCoveredCall()
    {
        var nextFriday = Time.AddDays(4).Date;

        var atmCall = OptionChain(_spy)
            .Where(x => x.Right == OptionRight.Call && x.Expiry.Date == nextFriday)
            .OrderBy(x =>  Math.Abs(_spy.Price - x.Strike))
            .FirstOrDefault();

        // If we cannot find a contract expiring on Friday, it is likely a holiday.
        // For simplicity, we will not trade and close the underlying position, if any.
        if (atmCall == null)
        {
            Liquidate(_spy, tag: $"Cannot find ATM Call expiring next Friday {nextFriday:yyyyMMdd}");
            return;
        }

        var contract = AddOptionContract(atmCall);
        var contractMultiplier = contract.ContractMultiplier;

        // We will invest 100% of the portfolio value observing the contract multiplier.
        // For example, if 100% of the portfolio value is 345 shares of SPY, we will invest 300.
        // Then, we sell 3 contracts of ATM call. If we are exercised, the position is closed.
        var equityOrderQuantity = Math.Floor(CalculateOrderQuantity(_spy, 1d)  / contractMultiplier) * contractMultiplier;
        // If we are invested in the underlying, the hedge takes into account the final quantity.
        var atmCallOrderQuantity = -(_spy.Holdings.Quantity + equityOrderQuantity) / contractMultiplier;

        if (equityOrderQuantity != 0)
        {
            MarketOrder(_spy, equityOrderQuantity);
        }

        MarketOrder(atmCall, atmCallOrderQuantity);
    }
}
