.. _algorithm-reference-trading-and-orders:

==================
Trading and Orders
==================

|

.. list-table::
   :header-rows: 1

   * - C#
     - Python
   * - `OrderTicketDemoAlgorithm.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/OrderTicketDemoAlgorithm.cs>`_
     - `OrderTicketDemoAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/OrderTicketDemoAlgorithm.py>`_
   * - `MarketOnOpenOnCloseAlgorithm.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/MarketOnOpenOnCloseAlgorithm.cs>`_
     - `MarketOnOpenOnCloseAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/MarketOnOpenOnCloseAlgorithm.py>`_
   * - `FinancialAdvisorDemoAlgorithm.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/FinancialAdvisorDemoAlgorithm.cs>`_
     - `FinancialAdvisorDemoAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/FinancialAdvisorDemoAlgorithm.py>`_

|

Introduction
============

QuantConnect provides dozens of methods to create, update, and cancel orders. These orders can be placed :ref:`automatically <algorithm-reference-trading-and-orders-automatic-position-sizing>` with helper methods, or manually through methods on the algorithm API. Manual orders can be :ref:`fetched, updated, and canceled <algorithm-reference-trading-and-orders-updating-orders>` with Order Tickets. As orders are filled and updated, they generate :ref:`events <algorithm-reference-trading-and-orders-tracking-order-events>` that notify your algorithm about their execution.

In backtesting, order fills are simulated using historical data. QuantConnect allows you to create your own fill, fee, slippage, and margin models via plugin points. You can control how optimistic or pessimistic these fills are with :ref:`transaction model <algorithm-reference-trading-and-orders-slippage-transaction-and-brokerage-models>` classes. In live trading, this fill price is set by your brokerage when the order is filled.

**What is an Order Ticket**

A critical concept required before diving into specific order types is the Order Ticket. When you create an order you are given an ``OrderTicket`` object to update and cancel your order. All orders return an order ticket.

In live trading, orders are asynchronous, and any operations to update the order must be requested. Whether the update is processed successfully is not guaranteed as the trade may already be filled by the time the request is sent.

.. figure:: https://cdn.quantconnect.com/docs/i/trading-and-orders-order-tickets.png

In the following sections, we'll explore how to use the Order Ticket to update and cancel orders you have created.

.. raw:: html

    <iframe width="757" height="433" src="https://www.youtube-nocookie.com/embed/HykXfstdNW0" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

|

.. _algorithm-reference-trading-and-orders-placing-orders:

Placing Orders
==============

QuantConnect provides the following API methods to place orders. All of the methods return an ``OrderTicket``, which you can use to update and cancel the orders. All orders should use the symbol object to place orders; the Symbol class uniquely identifies the security through ticker renames. Some order types are not supported by all brokerages. To confirm each order's support, click on the respective links below.

.. list-table::
   :header-rows: 1

   * - Order Type
     - Summary
     - Link
   * - Market Order
     - ``MarketOrder("IBM", 100);`` Send a market order for 100 IBM at market price
     - :ref:`algorithm-reference-trading-and-orders-market-orders`
   * - Limit Order
     - 	``LimitOrder("IBM", 100, 21.67);`` Submit a limit order for 100 IBM @ $21.67
     - :ref:`algorithm-reference-trading-and-orders-limit-orders`
   * - Stop Market Order
     - ``StopMarketOrder("IBM", 100, 21.67);`` Submit a stop limit order for 100 IBM with stop price of $21.67
     - :ref:`algorithm-reference-trading-and-orders-stop-market-orders`
   * - Stop Limit Order
     - ``StopLimitOrder("IBM", 100, 21.67, 22.00);`` Stop limit order for 100 IBM, stop price $21.67, limit of $22.00
     - :ref:`algorithm-reference-trading-and-orders-stop-limit-orders`
   * - MarketOnOpenOrder
     - ``MarketOnOpenOrder("IBM", 100);`` Market on open order for 100 IBM
     - :ref:`algorithm-reference-trading-and-orders-market-open-close`
   * - MarketOnCloseOrder
     - ``MarketOnCloseOrder("IBM", 100);`` Market on close order for 100 IBM
     - :ref:`algorithm-reference-trading-and-orders-market-open-close`

QuantConnect also provides automated order management technology via the :ref:`Algorithm Framework <algorithm-framework-overview>`, and automated position sizing via the :ref:`Automated Position Sizing (SetHoldings) <algorithm-reference-trading-and-orders-automatic-position-sizing>`.

|

.. _algorithm-reference-trading-and-orders-updating-orders:

Updating Orders
===============

To update an order, you must use its ``OrderTicket``. The specific properties that can be updated depend on the order type. Market orders are transmitted to the brokerage almost immediately, so only the tag on the order can be updated. Other orders can be updated until they are filled or the brokerage prevents modifications.

.. list-table::
   :header-rows: 1

   * - Order Type
     - Updatable Properties
   * - Market Order
     - ``Tag``
   * - Limit Order
     - ``Tag``, ``Quantity``, ``Limit Price``
   * - Stop Market Order
     - ``Tag``, ``Quantity``, ``Stop Price``
   * - Stop Limit Order
     - ``Tag``, ``Quantity``, ``Stop Price``, ``Limit Price``
   * - Market On Open Order
     - ``Tag``, ``Quantity``
   * - Market On Close Order
     - ``Tag``, ``Quantity``

Orders are updated by passing a ``UpdateOrderFields`` object to the Update method. The Update method returns an ``OrderResponse`` to signal the success or failure of the update request.

.. tabs::

   .. code-tab:: c#

        // Tag an order on creation
        var ticket = LimitOrder("SPY", 100, 221.05, tag: "New SPY trade");

        //Tag order later
        var response = ticket.Update(new UpdateOrderFields() {
          Tag = "Our New Tag for SPY Trade",
          LimitPrice = 222.00
        });

        // Check response with the OrderResponse
        if (response.IsSuccessful) {
             Debug("Order updated successfully");
        }

   .. code-tab:: py

        # Tag an order on creation
        ticket = self.LimitOrder("SPY", 100, 221.05, False, "New SPY trade")

        # Tag order later
        updateSettings = UpdateOrderFields()
        updateSettings.LimitPrice = 222.00
        updateSettings.Tag = "Limit Price Updated for SPY Trade"
        response = ticket.Update(updateSettings)

        # Validate the response is OK
        if response.IsSuccessful:
             self.Debug("Order updated successfully")

|

Cancel Orders
=============

To cancel an order, you must use its ``OrderTicket``. Market Orders are transmitted to the brokerage immediately and cannot be canceled. The ``Cancel()`` method returns an ``OrderResponse`` object to determine if the operation was successful.

.. tabs::

   .. code-tab:: c#

        // Create an order and save its ticket
        var ticket = LimitOrder("SPY", 100, 221.05, tag: "SPY Trade to Cancel");

        //Later cancel the order via the order ticket.
        var response = ticket.Cancel();

        // Use order response object to read status
        if (response.IsSuccessful) {
               Debug("Order successfully canceled");
        }

   .. code-tab:: py

        # Create an order and save its ticket
        ticket = self.LimitOrder("SPY", 100, 221.05, False, "SPY Trade to Cancel")

        # Tag order later
        response = ticket.Cancel("Canceled SPY Trade")

        # Use order response object to read status
        if response.IsSuccessful:
             self.Debug("Order successfully canceled")

|

.. _algorithm-reference-trading-and-orders-slippage-transaction-and-brokerage-models:

Slippage, Transaction, and Brokerage Models
===========================================

QuantConnect endeavors to make our backtesting as realistic as possible by providing high-resolution data, spread information, and powerful transaction models. These models are customizable on a per security basis through setters on the API.

As of November 2019, QuantConnect does not have quote data for US equities backtesting, so trades are filled at the last trade price. We hope in the coming months to have this data installed for backtesting and live trading.

**Order Slippage Impact**

By default, QuantConnect does not model slippage impact though we highly recommend you include a slippage model in your algorithm. Slippage is the difference between the expected and final fill prices of a trade. For more information on creating your own slippage models, see our :ref:`documentation <algorithm-reference-reality-modeling-slippage-models>`.

**Transaction Cost Models**

By default, transaction fees are modelled from Interactive Brokers Brokerage rates. These models are customizable by setting a new ``FeeModel``. For more information on creating your own fee models, see our :ref:`documentation <algorithm-reference-reality-modeling-fee-models>`.

**Brokerage Models**

Brokerage models aim to combine all of the models relevant for a brokerage together as a set. If you set the appropriate brokerage model, the fee models and supported order types will be appropriately set in your algorithm. For more information on setting your brokerage models, see our :ref:`documentation <algorithm-reference-reality-modeling-brokerage-models>`.

|

.. _algorithm-reference-trading-and-orders-automatic-position-sizing:

Automatic Position Sizing (SetHoldings)
=======================================

**Single Asset Targets**

The SetHoldings method automatically calculates the number of asset units to purchase according to the fraction of the portfolio value provided. This is a quick way to set up a portfolio with a set of weights for assets. If you already have holdings, you may want to liquidate the existing holdings first to free up buying power.

.. tabs::

   .. code-tab:: c#

        // Allocate 50% of portfolio value to IBM via market orders
        SetHoldings("IBM", 0.5);

        // Allocate 50% of portfolio value to IBM, but liquidate other holdings before starting
        SetHoldings("IBM", 0.5, true);

   .. code-tab:: py

        # Allocate 50% of buying power to IBM via market orders.
        self.SetHoldings("IBM", 0.5)

        # Allocate 50% of portfolio value to IBM, but liquidate other holdings before starting
        self.SetHoldings("IBM", 0.5, True)

**Portfolio of Asset Targets**

Often when trading on a weighted basket of assets, you must intelligently scale down existing positions before increasing allocations to other assets. The portfolio variant of SetHoldings was designed to do this operation for you by accepting an array of ``PortfolioTarget`` objects

If you already have holdings, the LEAN engine will calculate the delta-order required to meet your new target. When required, positions will be scaled down before other positions are scaled up in size.

.. tabs::

   .. code-tab:: c#

        // Purchase a portfolio of targets, processing orders intelligently.
        var targets = new List<PortfolioTarget>() {
              new PortfolioTarget("SPY", 0.8m),
              new PortfolioTarget("IBM", 0.2m)
        };
        SetHoldings(targets);

   .. code-tab:: py

        # Purchase a portfolio of targets, processing orders intelligently.
        self.SetHoldings([PortfolioTarget("SPY", 0.8), PortfolioTarget("IBM", 0.2)])

**Manually Calculating Quantity Targets**

If you are looking to size positions but not use market orders for the trades, you can use the ``CalculateOrderQuantity`` method to get an accurate estimate of the number of shares available to purchase with a given buying power fraction. The share quantity is calculated based on the current price of the asset and adjusted for the fee model attached to that security.

.. tabs::

   .. code-tab:: c#

        // Calculate the fee adjusted quantity of shares with given buying power
        var quantity = CalculateOrderQuantity("IBM", 0.4);
        LimitOrder("IBM", quantity, Securities["IBM"].Price);

   .. code-tab:: py

        # Calculate the fee adjusted quantity of shares with given buying power
        quantity = self.CalculateOrderQuantity("IBM", 0.4)
        self.LimitOrder("IBM", quantity, self.Securities["IBM"].Price)

**Cash Buffer Setting**

To ensure a high probability of order fills through market gaps and discontinuities the QuantConnect automatic portfolio methods assume a small built-in cash buffer. This buffer helps ensure you have sufficient buying power to handle overnight price movements. If you are seeing orders get rejected due to buying power, you can configure this buffer to provide a wider buffer. By default, the buffer is set to 2.5%. The buffer lives on the algorithm ``Settings`` property.

.. tabs::

   .. code-tab:: c#

        // Adjust the cash buffer from the default 2.5% to 5%
        Settings.FreePortfolioValuePercentage = 0.05;

   .. code-tab:: py

        # Adjust the cash buffer from the default 2.5% to 5%
        self.Settings.FreePortfolioValuePercentage = 0.05

|

Liquidating Portfolio
=====================

You can liquidate individual stocks, or your entire portfolio using the ``Liquidate()`` method. When called without a ticker provided, it will liquidate all your holdings. If you have pending open orders, it will attempt to cancel them.

.. tabs::

   .. code-tab:: c#

        // Liquidate all IBM in your portfolio
        Liquidate("IBM");

        // Liquidate entire portfolio
        Liquidate();

   .. code-tab:: py

        # Liquidate all IBM in your portfolio
        self.Liquidate("IBM")

        // Liquidate entire portfolio
        self.Liquidate()

|

Managing Orders
===============

The algorithm Transactions Manager (``SecurityTransactionManager``) is a collection of helper methods for quick access to all your orders. It is located in the ``Transactions``/``self.Transactions`` property on your algorithm.

**Fetching Single Order**

Using the Transactions Manager, you can retrieve a clone of an order by its Id. Once sent, orders cannot be changed, so the clone of the order is for informational purposes only. To :ref:`update <algorithm-reference-trading-and-orders-updating-orders>` an order's properties, you should use an ``Order`` Ticket. The method returns an Order object.

.. tabs::

   .. code-tab:: c#

        // Retrieve a clone of a previously sent order.
        var order = Transactions.GetOrderById(orderId)

   .. code-tab:: py

        # Retrieve a clone of a previously sent order.
        order = self.Transactions.GetOrderById(orderId)

**Fetching All Open Orders**

Using the Transaction Manager, you can fetch a list of all open orders for a symbol. This is helpful if you want to update multiple open orders for a specific symbol. The method returns a list of ``Order`` objects.

.. tabs::

   .. code-tab:: c#

        // Retrieve a list of all open orders for a symbol
        var openOrders = Transactions.GetOpenOrders(symbol);

   .. code-tab:: py

        # Retrieve a list of all open orders for a symbol
        openOrders = self.Transactions.GetOpenOrders(symbol)

**Canceling All Orders**

The Cancel helpers can cancel all open orders, or just those orders related with a specific symbol. The method returns a list of ``OrderTicket`` objects. This is helpful if you are simulating an "OCA / One-Cancels-All" style of order where you want to cancel other related orders.

.. tabs::

   .. code-tab:: c#

        // Cancel all open orders
        var allCancelledOrders = Transactions.CancelOpenOrders();

        // Cancel orders related to IBM, apply string tag.
        var ibmCancelledOrders = Transactions.CancelOpenOrders("IBM", "Hit stop price");

   .. code-tab:: py

        # Cancel all open orders
        allCancelledOrders = self.Transactions.CancelOpenOrders()

        # Cancel orders related to IBM, apply string tag.
        ibmCancelledOrders = self.Transactions.CancelOpenOrders("IBM", "Hit stop price")

|

.. _algorithm-reference-trading-and-orders-tracking-order-events:

Tracking Order Events
=====================

Each order generates events over its life as the status changes. These events are passed to the ``OnOrderEvent()`` method, which you can use for information about your order states. The event handler is passed an ``OrderEvent`` object, which has information about the order status.

.. tabs::

   .. code-tab:: c#

        public override void OnOrderEvent(OrderEvent orderEvent) {
            var order = Transactions.GetOrderById(orderEvent.OrderId);
            if (orderEvent.Status == OrderStatus.Filled)
                 Console.WriteLine("{0}: {1}: {2}", Time, order.Type, orderEvent);
        }

   .. code-tab:: py

        def OnOrderEvent(self, orderEvent):
            order = self.Transactions.GetOrderById(orderEvent.OrderId)
            if orderEvent.Status == OrderStatus.Filled:
                self.Log("{0}: {1}: {2}".format(self.Time, order.Type, orderEvent))

The ``OrderStatus`` enum has the following potential values.

.. list-table::
   :header-rows: 1

   * - Status
     - Description
   * - ``OrderStatus.New``
     - Order is created but has not been submitted by the brokerage.
   * - ``OrderStatus.Submitted``
     - Order has been successfully submitted to the brokerage.
   * - ``OrderStatus.PartiallyFilled``
     - Order has some of its requested quantity processed by brokerage.
   * - ``OrderStatus.Filled``
     - Order is completely filled by brokerage.
   * - ``OrderStatus.Canceled``
     - Order canceled before it was filled.
   * - ``OrderStatus.Invalid``
     - Order :ref:`invalidated <algorithm-reference-trading-and-orders-order-error-code-reference>` before it was accepted by LEAN.
   * - ``OrderStatus.CancelPending``
     - Order waiting for confirmation of cancellation.
   * - ``OrderStatus.UpdateSubmitted``
     - Order update submitted to the market.

|

Time In Force
=============

The TimeInForce property determines how long an order should remain open if unfilled. This does not apply to market orders as they are generally filled instantly. Time in force is useful to automatically cancel old trades.

.. list-table::
   :header-rows: 1

   * - Time In Force
     - Property Value
   * - Good Until Canceled
     - ``TimeInForce.GoodTilCanceled``: Order is valid until filled (default).
   * - Day
     - ``TimeInForce.Day``: Order is valid until filled or the market closes.
   * - Good Until Date
     - ``TimeInForce.GoodTilDate(DateTime expiry)``: Order is valid until filled or the specified expiration time.

By default, orders remain open until they are canceled (``TimeInForce.GoodTilCanceled``). To update the value, set the ``DefaultOrderProperties.TimeInForce`` before placing an order. Doing so will change the default value for all future orders unless reassigned again.

.. tabs::

   .. code-tab:: c#

        // Set Limit Order to be good until market close
        DefaultOrderProperties.TimeInForce = TimeInForce.Day;
        LimitOrder("IBM", 100, lastClose * .999m);

        // Set Market Order to be good until noon
        DefaultOrderProperties.TimeInForce = TimeInForce.GoodTilDate(new DateTime(2019, 6, 19, 12, 0, 0));
        MarketOrder("IBM", 100);


   .. code-tab:: py

        # Set Limit Order to be good until market close
        self.DefaultOrderProperties.TimeInForce = TimeInForce.Day
        self.LimitOrder("IBM", 100, lastClose * decimal.Decimal(.999))

        # Set Market Order to be good until noon
        self.DefaultOrderProperties.TimeInForce = TimeInForce.GoodTilDate(datetime(2019, 6, 19, 12, 0, 0))
        self.MarketOrder("IBM", 100)

|

.. _algorithm-reference-trading-and-orders-market-orders:

Market Orders
=============

Market Orders are sent immediately and filled at the market price for the security. To send a market order, you must provide a symbol and quantity. If you do not have sufficient capital for the purchase, your order will be rejected. By default, market orders are *synchronous* and fill immediately.

.. tabs::

   .. code-tab:: c#

        // Create a Market Order for 100 shares of IBM.
        var marketTicket = MarketOrder("IBM", 100);
        Debug($"Market Order Fill Price: {marketTicket.AverageFillPrice});

   .. code-tab:: py

        # Create a Market Order for 100 shares of IBM.
        marketTicket = self.MarketOrder("IBM", 100)
        self.Debug("Market Order Fill Price: {0}".format(marketTicket.AverageFillPrice))

**Configuring Market Order Timeouts**

Market orders are synchronous by default. This means they wait for the order to fill before moving to the next line of code. If you are trading on highly illiquid stocks, this wait can be too long, so LEAN has a built-in default timeout of 5 seconds, after which the code execution will continue even if the trade is not filled. You can control this timeout with the ``Transactions.MarketOrderFillTimeout`` property.

.. tabs::

   .. code-tab:: c#

        // Adjust the market fill-timeout to 30 seconds.
        Transactions.MarketOrderFillTimeout = TimeSpan.FromSeconds(30);

   .. code-tab:: py

        # Adjust the market fill-timeout to 30 seconds.
        self.Transactions.MarketOrderFillTimeout = timedelta(seconds=30)

**Asynchronously Sending Market Orders**

When trading on a large portfolio of assets, you may wish to send orders in batches and not wait for the response to each one. This is possible by setting the optional argument ``asynchronous`` to true.

.. tabs::

   .. code-tab:: c#

        // Create a Market Order for 100 shares of IBM asynchronously.
        MarketOrder("IBM", 100, asynchronous: true);

   .. code-tab:: py

        # Create a Market Order for 100 shares of IBM asynchronously.
        self.MarketOrder("IBM", 100, True)

|

.. _algorithm-reference-trading-and-orders-limit-orders:

Limit Orders
============

Limit orders fill once the asset price is equal or better than the configured price. When purchasing an asset, this means the price is equal or lower to the price you set. Conversely, when selling shares, this is when the price is equal or higher to the price you set. Limit orders are often used to get a good entry price, or take-profit on an existing holding.

Limit orders can be updated via their ``OrderTicket`` because their orders are not immediately filled. For more information about updating orders, see :ref:`Updating Orders <algorithm-reference-trading-and-orders-updating-orders>`.

.. tabs::

   .. code-tab:: c#

        // Purchase 10 SPY shares when its 1% below the current price
        var close = Securities["SPY"].Close;
        var limitTicket = LimitOrder("SPY", 10, close * .99m);

   .. code-tab:: py

        # Purchase 10 SPY shares when its 1% below the current price
        close = self.Securities["SPY"].Close
        limitTicket = self.LimitOrder("SPY", 10, close * .99)

|

.. _algorithm-reference-trading-and-orders-stop-market-orders:

Stop Market Orders
==================

A Stop Market Order ("stop-loss") fills as a market order when a specific price is reached. A buy stop market order to purchase assets will trigger when the price is equal or higher than the one configured. Conversely, a sell stop market order will trigger when the price is equal or lower than to the one set. Stop market orders are often used to prevent loss.

If the market gaps (jumps in a discontinuous manner) past your stop price, it may be filled at a substantially worse price than the stop price you entered. As such, a stop-loss order is no guarantee your trade will fill at the price you specify.

Stop Market Order ``StopPrice``, ``Tag``, and ``Quantity`` can be updated. For more information on updating orders, see :ref:`Updating Orders <algorithm-reference-trading-and-orders-updating-orders>`.

.. tabs::

   .. code-tab:: c#

        // Create Stop Market Order for 1% below current market price.
        var close = Securities[symbol].Close;
        var stopMarketTicket = StopMarketOrder(symbol, 10, close * 0.99m);

   .. code-tab:: py

        # Create Stop Market Order for 1% below current market price.
        close = self.Securities["SPY"].Close
        stopMarketTicket = self.StopMarketOrder("SPY", 10, close * 0.99)

|

.. _algorithm-reference-trading-and-orders-stop-limit-orders:

Stop Limit Orders
=================

Stop Limit Orders create a limit order when a specified price is reached. The associated limit order is filled when it reaches the limit price or better. As with all limit orders, the order is not filled if the price does not reach the specified price. Stop limit orders are often used to control risk, without the risk of a large gap filling trades unfavorably.

Stop Limit Order ``StopPrice``, ``LimitPrice``, ``Tag``, and ``Quantity`` can all be updated after creation. For more information on updating orders, see :ref:`Updating Orders <algorithm-reference-trading-and-orders-updating-orders>`.

.. tabs::

   .. code-tab:: c#

        var close = Securities[symbol].Close;
        var stopPrice = close * .99; // Trigger stop limit when price falls 1%.
        var limitPrice = close * 1.01; // Sell equal or better than 1% > close.
        var stopLimitTicket = StopLimitOrder(symbol, -10, stopPrice, limitPrice);


   .. code-tab:: py

        close = self.Securities["SPY"].Close
        stopPrice = close * .99 # Trigger stop limit when price falls 1%.
        limitPrice = close * 1.01 # Sell equal or better than 1% > close.
        stopLimitTicket = self.StopLimitOrder("SPY", 10, stopPrice, limitPrice)

|

.. _algorithm-reference-trading-and-orders-market-open-close:

Market On Open-Close Orders
===========================

Market On Open orders are filled at the official *opening* price for the security. They must be submitted two minutes before the market opens to be included in the opening auction. The Market On Open ``Quantity`` and ``Tag`` properties can be updated after creation until the last two minutes before open.

Market On Close orders are filled at the official *closing* price for the security. They must be submitted at least two minutes before the market closes to be included in the official closing auction. The Market On Open ``Quantity`` and ``Tag`` properties can be updated after creation until the last two minutes before close.

For more information on updating orders, see :ref:`Updating Orders <algorithm-reference-trading-and-orders-updating-orders>`.

.. tabs::

   .. code-tab:: c#

        // Create Market Open/Close Orders for 100 shares of IBM
        var marketOpenOrderTicket = MarketOnOpenOrder("SPY", 100);   // Place Before Open
        var marketCloseOrderTicket = MarketOnCloseOrder("SPY", 100); // Place Before Close

   .. code-tab:: py

        # Create Market Open/Close Orders for 100 shares of IBM
        marketOpenOrderTicket = self.MarketOnOpenOrder("SPY", 100)    # Place Before Open
        marketCloseOrderTicket = self.MarketOnCloseOrder("SPY", 100)  # Place Before Close

**Fill Price Considerations**

When you place a market on open or close order, you do not know its fill price until after the order is completed. If your order quantity is too close to your total portfolio buying power, you have a high chance of it being rejected as there may be large changes in price overnight. We recommend you consider this when sizing your portfolio to increase your probability of successful trades.

|

Other Order Types
=================

Often we are asked to support other order types such as Multi-Leg, One Cancels All, and Trailing Stop. Currently these order types are not supported, but will be added over time. Part of the difficulty of implementing them is the incomplete brokerage support.

|

Tagging Orders and Debugging
============================

Orders can be set with tags to aid your strategy development. Tags can be any string of up to 100 characters. Order tags can also be set with the order update system, as shown below:

.. tabs::

   .. code-tab:: c#

        // Tag an order on creation
        var ticket = LimitOrder("SPY", 100, 221.05, tag: "New SPY trade");

        //Tag order later
        ticket.Update( UpdateOrderFields() {
          Tag = "Our New Tag for SPY Trade" }
        );

   .. code-tab:: py

        # Tag an order on creation
        ticket = self.LimitOrder("SPY", 100, 221.05, "New SPY trade")

        # Tag order later
        updateSettings = UpdateOrderFields()
        updateSettings.Tag = "Our New Tag for SPY Trade"
        ticket.Update(updateSettings)

For more information on updating order properties, see :ref:`Updating Orders <algorithm-reference-trading-and-orders-updating-orders>`.

|

Common Order Errors
===================

**Why is my order being converted to a market on open order?**

Market orders are automatically converted into Market On Open orders when the market is closed at the time of the request. This most commonly happens when using Daily or Hourly data, which is emitted when the market closes. Daily data is emitted at the end of the day (midnight), and hourly data for equities' final bar is at 4 pm ET. If you are using one of the automatic portfolio helper methods (``SetHoldings``), then the orders will also be converted if the data resolution is insufficient.

To fix this, we recommend using minute resolution data or updating your order creation logic to submit Market On Open orders.

**Why am I seeing the "stale price" warning?**

If the last price data point was more than 10 minutes old, LEAN will flag the orders with a warning tag indicating the price may not be representative. This can happen on illiquid assets or if you are scheduling intraday events using daily data.

To fix this, we recommend using the highest resolution data possible for a high fidelity backtest.

|

.. _algorithm-reference-trading-and-orders-order-error-code-reference:

Order Error Code Reference
==========================

When an order fails to process it returns with a negative order-id. These error codes mean different things as described in the table below.

.. list-table::
   :header-rows: 1

   * - Id
     - Interpretation
   * - -1
     - **ProcessingError** - Unknown error.
   * - -2
     - **OrderAlreadyExists** - Cannot submit because order already exists.
   * - -3
     - **InsufficientBuyingPower** - Not enough money to to submit order.
   * - -4
     - **BrokerageModelRefusedToSubmitOrder** - Internal logic invalidated submit order.
   * - -5
     - **BrokerageFailedToSubmitOrder** - Brokerage rejected order.
   * - -6
     - **BrokerageFailedToUpdateOrder** - Failed to update order.
   * - -7
     - **BrokerageHandlerRefusedToUpdateOrder** - Brokerage rejected update request.
   * - -8
     - **BrokerageFailedToCancelOrder** - Brokerage refused to cancel order.
   * - -9
     - **InvalidOrderStatus** - Only pending orders can be cancelled
   * - -10
     - **UnableToFindOrder** - Cannot find order with that id.
   * - -11
     - **OrderQuantityZero** - Cannot submit or update orders with zero quantity.
   * - -12
     - **UnsupportedRequestType** - This type of request is unsupported.
   * - -13
     - **PreOrderChecksError** - Pre-placement order checks failed.
   * - -14
     - **MissingSecurity** - Security is missing. Probably did not subscribe.
   * - -15
     - **ExchangeNotOpen** - Some order types require open exchange.
   * - -16
     - **SecurityPriceZero** - There isn't any market data yet for the security.
   * - -17
     - **ForexBaseAndQuoteCurrenciesRequired** - Need both currencies in cashbook to trade a pair.
   * - -18
     - **ForexConversionRateZero** - Need conversion rate to account currency.
   * - -19
     - **SecurityHasNoData** - Should not attempt trading without at least one data point.
   * - -20
     - **ExceededMaximumOrders** - Transaction manager's cache is full.
   * - -21
     - **MarketOnCloseOrderTooLate** - Need to submit market on close orders at least 11 minutes before exchange close.
   * - -22
     - **InvalidRequest** - Request is invalid or null.
   * - -23
     - **RequestCanceled** - Request was canceled by user.
   * - -24
     - **AlgorithmWarmingUp** - All orders are invalidated while algorithm is warming up.
   * - -25
     - **BrokerageModelRefusedToUpdateOrder** - Internal logic invalidated update order.
   * - -26
     - **QuoteCurrencyRequired** - Need quote currency in cashbook to trade.
   * - -27
     - **ConversionRateZero** - Need conversion rate to account currency.
   * - -28
     - **NonTradableSecurity** - The order's symbol references a non-tradable security.
   * - -29
     - **NonExercisableSecurity** - The order's symbol references a non-exercisable security.