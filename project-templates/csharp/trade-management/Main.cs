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

public class OneCancelOtherExampleAlgorithm : QCAlgorithm
{
    private dynamic _spy;
    public override void Initialize()
    {
        SetStartDate(2024, 9, 1);
        SetEndDate(2024, 12, 31);
        // AutomaticIndicatorWarmUp only supports automatic indicators, not manual indicators.
        Settings.AutomaticIndicatorWarmUp = true;
        _spy = AddEquity("SPY");
        _spy.Ema = EMA(_spy, 14, Resolution.Daily);
        // Alternatively, use a manual indicator.
        // _spy.Ema = new ExponentialMovingAverage(14);
        // WarmUpIndicator<IndicatorDataPoint>(_spy.Symbol, _spy.Ema, Resolution.Daily);
        // RegisterIndicator(_spy.Symbol, _spy.Ema, Resolution.Daily);
        _spy.hasOCO = false;
    }

    public override void OnData(Slice slice)
    {
        // If we have open stop loss and take profit orders, we won't place new orders
        if (_spy.hasOCO || !slice.Bars.TryGetValue(_spy, out TradeBar bar))
            return;

        // If the price is above the EMA, we will buy 75% of the portfolio value
        // and place the OCO orders to sell it.
        // Otherwise, we will short 75% of the portfolio value
        // and place OCO orders to rebuy.
        var ema = _spy.Ema;
        var price = _spy.Price;
        var weight = ema > price ? .75m : -.75m;
        var stopPrice = price * (ema > price ? 0.95m : 1.05m);
        var limitPrice = price * (ema > price ? 1.05m : 0.95m);

        var quantity = CalculateOrderQuantity(_spy, weight);
        MarketOrder(_spy, quantity);
        _spy.stopLoss = StopMarketOrder(_spy, -quantity, stopPrice);
        _spy.takeProfit = LimitOrder(_spy, -quantity, limitPrice);
        _spy.hasOCO = true;
    }

    public override void OnOrderEvent(OrderEvent orderEvent)
    {
        if (orderEvent.Status == OrderStatus.Filled)
        {
            dynamic equity = Securities[orderEvent.Symbol];
            switch (orderEvent.Ticket.OrderType)
            {
                case OrderType.StopMarket:
                    equity.takeProfit.Cancel();
                    equity.hasOCO = false;
                    break;
                case OrderType.Limit:
                    equity.stopLoss.Cancel();
                    equity.hasOCO = false;
                    break;
            }
        }
    }
}
