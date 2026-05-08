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
public class CustomModelsAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        SetStartDate(2024, 9, 12);
        SetEndDate(2024, 10, 1);
        SetCash(1000000);
        Settings.SeedInitialPrices = true;

        // The brokerage model sets the reality models that reflect the brokerage behavior.
        SetBrokerageModel(BrokerageName.InteractiveBrokersBrokerage, AccountType.Margin);

        // Override some of the models.
        AddSecurityInitializer(security =>
        {
            security.SetFeeModel(new CustomFeeModel(this));
            security.SetFillModel(new CustomFillModel(this));
            security.SetSlippageModel(new CustomSlippageModel(this));

            // We can set different models for different asset classes.
            if (security.Type.IsOption())
            {
                security.SetBuyingPowerModel(BuyingPowerModel.Null);
            }
            else
            {
                security.SetBuyingPowerModel(new CustomBuyingPowerModel(this));
            }
        });

        // Request SPY data to trade after we set the model and security initializer.
        AddEquity("SPY");
    }

    public override void OnData(Slice slice)
    {
        if (!Portfolio.Invested)
        {
            SetHoldings("SPY", 1);
        }
    }

    public class CustomFillModel : ImmediateFillModel
    {
        private readonly QCAlgorithm _algorithm;
        private readonly Random _random = new Random(387510346); // seed it for reproducibility
        private readonly Dictionary<long, decimal> _absoluteRemainingByOrderId = new Dictionary<long, decimal>();

        public CustomFillModel(QCAlgorithm algorithm)
        {
            _algorithm = algorithm;
        }

        public override OrderEvent MarketFill(Security asset, MarketOrder order)
        {
            // This model randomly fills market orders.

            decimal absoluteRemaining;
            if (!_absoluteRemainingByOrderId.TryGetValue(order.Id, out absoluteRemaining))
            {
                absoluteRemaining = order.AbsoluteQuantity;
                _absoluteRemainingByOrderId.Add(order.Id, order.AbsoluteQuantity);
            }

            var fill = base.MarketFill(asset, order);
            var absoluteFillQuantity = (int)(Math.Min(absoluteRemaining, _random.Next(0, 2*(int)order.AbsoluteQuantity)));
            fill.FillQuantity = Math.Sign(order.Quantity) * absoluteFillQuantity;

            if (absoluteRemaining == absoluteFillQuantity)
            {
                fill.Status = OrderStatus.Filled;
                _absoluteRemainingByOrderId.Remove(order.Id);
            }
            else
            {
                absoluteRemaining = absoluteRemaining - absoluteFillQuantity;
                _absoluteRemainingByOrderId[order.Id] = absoluteRemaining;
                fill.Status = OrderStatus.PartiallyFilled;
            }

            _algorithm.Log($"CustomFillModel: {fill}");

            return fill;
        }
    }

    public class CustomFeeModel : FeeModel
    {
        private readonly QCAlgorithm _algorithm;

        public CustomFeeModel(QCAlgorithm algorithm)
        {
            _algorithm = algorithm;
        }

        public override OrderFee GetOrderFee(OrderFeeParameters parameters)
        {
            // Custom fee math.
            var fee = Math.Max(
                1m,
                parameters.Security.Price*parameters.Order.AbsoluteQuantity*0.00001m);

            _algorithm.Log($"CustomFeeModel: {fee}");
            return new OrderFee(new CashAmount(fee, "USD"));
        }
    }

    public class CustomSlippageModel : ISlippageModel
    {
        private readonly QCAlgorithm _algorithm;

        public CustomSlippageModel(QCAlgorithm algorithm)
        {
            _algorithm = algorithm;
        }

        public decimal GetSlippageApproximation(Security asset, Order order)
        {
            // Custom slippage math.
            var slippage = asset.Price*0.0001m*(decimal) Math.Log10(2*(double) order.AbsoluteQuantity);

            _algorithm.Log($"CustomSlippageModel: {slippage}");
            return slippage;
        }
    }

    public class CustomBuyingPowerModel : BuyingPowerModel
    {
        private readonly QCAlgorithm _algorithm;

        public CustomBuyingPowerModel(QCAlgorithm algorithm)
        {
            _algorithm = algorithm;
        }

        public override HasSufficientBuyingPowerForOrderResult HasSufficientBuyingPowerForOrder(
            HasSufficientBuyingPowerForOrderParameters parameters)
        {
            // Custom behavior: this model will assume that there is always enough buying power.
            var hasSufficientBuyingPowerForOrderResult = new HasSufficientBuyingPowerForOrderResult(true);
            _algorithm.Log($"CustomBuyingPowerModel: {hasSufficientBuyingPowerForOrderResult.IsSufficient}");

            return hasSufficientBuyingPowerForOrderResult;
        }
    }

    /// <summary>
    /// The simple fill model shows how to implement a simpler version of
    /// the most popular order fills: Market, Stop Market and Limit
    /// </summary>
    public class SimpleCustomFillModel : FillModel
    {
        private static OrderEvent CreateOrderEvent(Security asset, Order order)
        {
            var utcTime = asset.LocalTime.ConvertToUtc(asset.Exchange.TimeZone);
            return new OrderEvent(order, utcTime, OrderFee.Zero);
        }

        private static OrderEvent SetOrderEventToFilled(OrderEvent fill, decimal fillPrice, decimal fillQuantity)
        {
            fill.Status = OrderStatus.Filled;
            fill.FillQuantity = fillQuantity;
            fill.FillPrice = fillPrice;
            return fill;
        }

        private static TradeBar GetTradeBar(Security asset, OrderDirection orderDirection)
        {
            var tradeBar = asset.Cache.GetData<TradeBar>();
            if (tradeBar != null) return tradeBar;

            // Tick-resolution data doesn't have TradeBar, use the asset price.
            var price = asset.Price;
            return new TradeBar(asset.LocalTime, asset.Symbol, price, price, price, price, 0);
        }

        public override OrderEvent MarketFill(Security asset, MarketOrder order)
        {
            var fill = CreateOrderEvent(asset, order);
            if (order.Status == OrderStatus.Canceled) return fill;

            return SetOrderEventToFilled(fill,
                order.Direction == OrderDirection.Buy
                    ? asset.Cache.AskPrice
                    : asset.Cache.BidPrice,
                order.Quantity);
        }

        public override OrderEvent StopMarketFill(Security asset, StopMarketOrder order)
        {
            var fill = CreateOrderEvent(asset, order);
            if (order.Status == OrderStatus.Canceled) return fill;

            var stopPrice = order.StopPrice;
            var tradeBar = GetTradeBar(asset, order.Direction);

            return order.Direction switch
            {
                OrderDirection.Buy => tradeBar.Low < stopPrice
                    ? SetOrderEventToFilled(fill, stopPrice, order.Quantity)
                    : fill,
                OrderDirection.Sell => tradeBar.High > stopPrice
                    ? SetOrderEventToFilled(fill, stopPrice, order.Quantity)
                    : fill,
                _ => fill
            };
        }

        public override OrderEvent LimitFill(Security asset, LimitOrder order)
        {
            var fill = CreateOrderEvent(asset, order);
            if (order.Status == OrderStatus.Canceled) return fill;

            var limitPrice = order.LimitPrice;
            var tradeBar = GetTradeBar(asset, order.Direction);

            return order.Direction switch
            {
                OrderDirection.Buy => tradeBar.High > limitPrice
                    ? SetOrderEventToFilled(fill, limitPrice, order.Quantity)
                    : fill,
                OrderDirection.Sell => tradeBar.Low < limitPrice
                    ? SetOrderEventToFilled(fill, limitPrice, order.Quantity)
                    : fill,
                _ => fill
            };
        }
    }
}
