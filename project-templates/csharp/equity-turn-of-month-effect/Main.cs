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
public class MonthlyEdgeStrategy : QCAlgorithm
{
    private Symbol _spy;
    private List<DateTime> _monthTradingDays = new List<DateTime>();
    private (int Year, int Month)? _currentMonth;

    public override void Initialize()
    {
        SetStartDate(2022, 1, 1);
        SetEndDate(2024, 12, 31);
        SetCash(100000);

        _spy = AddEquity("SPY", Resolution.Minute).Symbol;

        Schedule.On(
            DateRules.EveryDay(_spy),
            TimeRules.AfterMarketOpen(_spy, 1),
            Rebalance);
    }

    private void Rebalance()
    {
        var today = Time.Date;
        var currentMonth = (today.Year, today.Month);

        if (currentMonth != _currentMonth)
        {
            _currentMonth = currentMonth;
            _monthTradingDays = GetMonthTradingDays(today);
        }

        if (_monthTradingDays.Count == 0)
        {
            return;
        }

        var isHold = _monthTradingDays.Take(3).Contains(today)
            || _monthTradingDays.Skip(Math.Max(0, _monthTradingDays.Count - 4)).Contains(today);

        if (isHold && !Portfolio[_spy].Invested)
        {
            SetHoldings(_spy, 1.0);
        }
        else if (!isHold && Portfolio[_spy].Invested)
        {
            Liquidate(_spy);
        }
    }

    private List<DateTime> GetMonthTradingDays(DateTime anchorDate)
    {
        var start = new DateTime(anchorDate.Year, anchorDate.Month, 1);
        var lastDay = DateTime.DaysInMonth(anchorDate.Year, anchorDate.Month);
        var end = new DateTime(anchorDate.Year, anchorDate.Month, lastDay);

        return TradingCalendar.GetTradingDays(start, end)
            .Select(d => d.Date.Date)
            .ToList();
    }
}
