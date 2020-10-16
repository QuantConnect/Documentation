.. _platform-api-backtest:

===================
Backtest Management
===================

The QuantConnect.com API exposes methods for creating, reading, listing, updating, and deleting backtests in the user's account.

|

----------------------------------------------------------------

Return Data Types
-----------------

|

Backtest management API calls return either a Backtest object, a Backtest List object, or a Backtest Report object.

|

Backtest Object
===============

A Backtest objects describes a Backtest response packet from the QuantConnect.com API.

Attributes
^^^^^^^^^^

.. list-table::
   :header-rows: 1

   * - Attribute
     - Type
     - Description
   * - name
     - string
     - Name of the backtest.
   * - note
     - string
     - Note on the backtest, attached by the user.
   * - backtestId
     - string
     - Assigned backtest Id.
   * - completed
     - boolean
     - True when the backtest is completed.
   * - progress
     - decimal
     - Progress of the backtest in percent 0-1.
   * - result
     - A Backtest Result object (see full API reference for its attributes)
     - Result-specific items from the backtest packet.
   * - error
     - string
     - Backtest error message.
   * - stacktrace
     - string
     - Backtest error stacktrace.
   * - created
     - string
     - Date and time the project was created.
   * - success
     - Boolean
     - Indicate if the API request was successful.
   * - errors
     - Array of strings
     - List of errors with the API call, if any.

|

----------------------------------------------------------------

Backtest List Object
====================

A Backtest List object contains a list of Backtest objects for a project.

Attributes
^^^^^^^^^^

.. list-table::
   :header-rows: 1

   * - Attribute
     - Type
     - Description
   * - backtests
     - Array of Backtest objects
     - List of backtest for a project.
   * - success
     - Boolean
     - Indicate if the API request was successful.
   * - errors
     - Array of strings
     - List of errors with the API call, if any.

|

----------------------------------------------------------------

Backtest Report Object
======================

A Backtest Report object contains the report of a backtest.

Attributes
^^^^^^^^^^

.. list-table::
   :header-rows: 1

   * - Attribute
     - Type
     - Description
   * - report
     - string
     - HTML data of the report, with embedded base64 images.
   * - success
     - Boolean
     - Indicate if the API request was successful.
   * - errors
     - Array of strings
     - List of errors with the API call, if any.

|

----------------------------------------------------------------

Common Data Types
-----------------

|

Common data types passed into and returned from the API.

|

Backtest Result Object
======================

Result-specific items from the packet.

Attributes
^^^^^^^^^^

.. list-table::
   :header-rows: 1

   * - Attribute
     - Type
     - Description
   * - rollingWindow
     - Dictionary of [string:Algorithm Performance objects]
     - Rolling window detailed statistics.
   * - totalPerformance
     - An Algorithm Performance object.
     - Rolling window detailed statistics.
   * - alphaRuntimeStatistics
     - An Alpha Runtime Statistics object.
     - Contains population averages scores over the life of the algorithm.
   * - charts
     - Dictionary of [string:Chart]
     - Charts updates for the live algorithm since the last result packet.
   * - orders
     - Dictionary of [string:Order]
     - Order updates since the last result packet.
   * - orderEvents
     - List of Order Event objects
     - Order Event updates since the last result packet.
   * - profitLoss
     - Dictionary of [string:decimal]
     - Trade profit and loss information since the last algorithm result packet.
   * - statistics
     - Dictionary [string:string]
     - Statistics information sent during the algorithm operations.
   * - runtimeStatistics
     - Dictionary [string:string]
     - Runtime banner/updating statistics in the title banner of the live algorithm GUI.
   * - serverStatistics
     - Dictionary [string:string]
     - Server status information, including CPU/RAM usage, ect.

|

----------------------------------------------------------------

Algorithm Performance Object
============================

A wrapper for TradeStatistics and PortfolioStatistics.

Attributes
^^^^^^^^^^

.. list-table::
   :header-rows: 1

   * - Attribute
     - Type
     - Description
   * - tradeStatistics
     - A Trade Statistics object
     - A set of statistics calculated from a list of closed trades.
   * - portfolioStatistics
     - A Portfolio Statistics object
     - Represents a set of statistics calculated from equity and benchmark samples.
   * - closedTrades
     - Array of Trade objects
     - The algorithm statistics on portfolio.

|

----------------------------------------------------------------

TradeStatistics Object
======================

A set of statistics calculated from a list of closed trades.

Attributes
^^^^^^^^^^

.. list-table::
   :header-rows: 1

   * - Attribute
     - Type
     - Description
   * - startDateTime
     - string
     - The entry date/time of the first trade.
   * - endDateTime
     - string
     - The exit date/time of the first trade.
   * - totalNumberOfTrades
     - int
     - The total number of trades.
   * - numberOfWinningTrades
     - int
     - The total number of winning trades.
   * - numberOfLosingTrades
     - int
     - The total number of losing trades.
   * - totalProfitLoss
     - decimal
     - The total profit/loss for all trades (as symbol currency).
   * - totalProfit
     - decimal
     - The total profit for all winning trades (as symbol currency).
   * - totalLoss
     - decimal
     - The total loss for all losing trades (as symbol currency).
   * - largestProfit
     - decimal
     - The largest profit in a single trade (as symbol currency).
   * - largestLoss
     - decimal
     - The largest loss in a single trade (as symbol currency).
   * - averageProfitLoss
     - decimal
     - The average profit/loss (a.k.a. Expectancy or Average Trade) for all trades (as symbol currency).
   * - averageProfit
     - decimal
     - The average profit for all winning trades (as symbol currency).
   * - averageLoss
     - decimal
     - The average loss for all winning trades (as symbol currency).
   * - averageTradeDuration
     - string
     - The average duration for all trades.
   * - averageWinningTradeDuration
     - string
     - The average duration for all winning trades.
   * - averageLosingTradeDuration
     - string
     - The average duration for all losing trades.
   * - medianTradeDuration
     - string
     - The median duration for all trades.
   * - medianWinningTradeDuration
     - string
     - The median duration for all winning trades.
   * - medianLosingTradeDuration
     - string
     - The median duration for all losing trades.
   * - maxConsecutiveWinningTrades
     - int
     - The maximum number of consecutive winning trades.
   * - maxConsecutiveLosingTrades
     - int
     - The maximum number of consecutive losing trades.
   * - profitLossRatio
     - decimal
     - The ratio of the average profit per trade to the average loss per trade.
   * - winLossRatio
     - decimal
     - The ratio of the number of winning trades to the number of losing trades.
   * - winRate
     - decimal
     - The ratio of the number of winning trades to the total number of trades.
   * - lossRate
     - decimal
     - The ratio of the number of losing trades to the total number of trades.
   * - averageMAE
     - decimal
     - The average Maximum Adverse Excursion for all trades.
   * - averageMFE
     - decimal
     - The average Maximum Favorable Excursion for all trades.
   * - largestMAE
     - decimal
     - The largest Maximum Adverse Excursion in a single trade (as symbol currency).
   * - largestMFE
     - decimal
     - The largest Maximum Favorable Excursion in a single trade (as symbol currency).
   * - maximumClosedTradeDrawdown
     - decimal
     - The maximum closed-trade drawdown for all trades (as symbol currency).
   * - maximumIntraTradeDrawdown
     - decimal
     - The maximum intra-trade drawdown for all trades (as symbol currency)
   * - profitLossStandardDeviation
     - decimal
     - The standard deviation of the profits/losses for all trades (as symbol currency).
   * - profitLossDownsideDeviation
     - decimal
     - The downside deviation of the profits/losses for all trades (as symbol currency).
   * - profitFactor
     - decimal
     - The ratio of the total profit to the total loss.
   * - sharpeRatio
     - decimal
     - The ratio of the average profit/loss to the standard deviation.
   * - sortinoRatio
     - decimal
     - The ratio of the average profit/loss to the downside deviation.
   * - profitToMaxDrawdownRatio
     - decimal
     - The ratio of the total profit/loss to the maximum closed trade drawdown.
   * - maximumEndTradeDrawdown
     - decimal
     - The maximum amount of profit given back by a single trade before exit (as symbol currency).
   * - averageEndTradeDrawdown
     - decimal
     - The average amount of profit given back by all trades before exit (as symbol currency).
   * - maximumDrawdownDuration
     - string
     - The maximum amount of time to recover from a drawdown (longest time between new equity highs or peaks).
   * - totalFees
     - decimal
     - The sum of fees for all trades.

|

----------------------------------------------------------------

Portfolio Statistics Object
============================

Represents a set of statistics calculated from equity and benchmark samples.

Attributes
^^^^^^^^^^

.. list-table::
   :header-rows: 1

   * - Attribute
     - Type
     - Description
   * - riskFreeRate
     - decimal
     - The current defined risk free annual return rate.
   * - averageWinRate
     - decimal
     - The average rate of return for winning trades.
   * - averageLossRate
     - decimal
     - The average rate of return for losing trades.
   * - profitLossRatio
     - decimal
     - The ratio of the average win rate to the average loss rate.
   * - winRate
     - decimal
     - The ratio of the number of winning trades to the total number of trades.
   * - lossRate
     - decimal
     - The ratio of the number of losing trades to the total number of trades.
   * - expectancy
     - decimal
     - The expected value of the rate of return.
   * - compoundingAnnualReturn
     - decimal
     - Annual compounded returns statistic based on the final-starting capital and years.
   * - drawdown
     - decimal
     - Drawdown maximum percentage.
   * - totalNetProfit
     - decimal
     - The total net profit percentage.
   * - sharpeRatio
     - decimal
     - Sharpe ratio with respect to risk free rate: measures excess of return per unit of risk.
   * - probabilisticSharpeRatio
     - decimal
     - A probability measure associated with the Sharpe ratio. It informs us of the probability that the estimated Sharpe ratio is greater than a chosen benchmark.
   * - alpha
     - decimal
     - Abnormal returns over the risk free rate and the relationship (beta) with the benchmark returns.
   * - beta
     - decimal
     - The covariance between the algorithm and benchmark performance, divided by benchmark's variance
   * - annualStandardDeviation
     - decimal
     - Annualized standard deviation.
   * - annualVariance
     - decimal
     - Calculation using the daily performance variance and trading days per year.
   * - informationRatio
     - decimal
     - Risk adjusted return.
   * - trackingError
     - decimal
     - A measure of how closely a portfolio follows the index to which it is benchmarked.
   * - treynorRatio
     - decimal
     - A measurement of the returns earned in excess of that which could have been earned on an investment that has no diversifiable risk.

|

----------------------------------------------------------------

Trade Object
============

Represents a closed trade.

Attributes
^^^^^^^^^^

.. list-table::
   :header-rows: 1

   * - Attribute
     - Type
     - Description
   * - symbol
     - A Symbol object
     - .
   * - entryTime
     - string
     - The date and time the trade was opened.
   * - entryPrice
     - decimal
     - The price at which the trade was opened (or the average price if multiple entries).
   * - direction
     - string
     - The direction of the trade. Either "Long" or "Short".
   * - quantity
     - decimal
     - The total unsigned quantity of the trade.
   * - exitTime
     - string
     - The date and time the trade was closed.
   * - exitPrice
     - decimal
     - The price at which the trade was closed (or the average price if multiple exits).
   * - profitLoss
     - decimal
     - The gross profit/loss of the trade (as account currency).
   * - totalFees
     - decimal
     - The total fees associated with the trade (always positive value) (as account currency).
   * - mAE
     - decimal
     - The Maximum Adverse Excursion (as account currency).
   * - mFE
     - decimal
     - The Maximum Favorable Excursion (as account currency).
   * - duration
     - string
     - The duration of the trade.
   * - endTradeDrawdown
     - decimal
     - The amount of profit given back before the trade was closed.

|

----------------------------------------------------------------

Alpha Runtime Statistics Object
===============================

Contains insight population run time statistics.

Attributes
^^^^^^^^^^

.. list-table::
   :header-rows: 1

   * - Attribute
     - Type
     - Description
   * - MeanPopulationScore
     - string
     - The base currency used.
   * - RollingAveragedPopulationScore
     - An Insight Score object
     - Defines the scores given to a particular insight.
   * - LongCount
     - string
     - Gets the total number of insights with an up direction.
   * - ShortCount
     - string
     - Gets the total number of insights with a down direction.
   * - LongShortRatio
     - decimal
     - The ratio of InsightDirection.Up over InsightDirection.Down.
   * - TotalAccumulatedEstimatedAlphaValue
     - decimal
     - The total accumulated estimated value of trading all insights.
   * - KellyCriterionEstimate
     - decimal
     - Score of the strategy's insights predictive power.
   * - KellyCriterionProbabilityValue
     - decimal
     - The p-value or probability value of the KellyCriterionEstimate.
   * - FitnessScore
     - decimal
     - Score of the strategy's performance, and suitability for the Alpha Stream Market.
   * - PortfolioTurnover
     - decimal
     - Measurement of the strategies trading activity with respect to the portfolio value. Calculated as the sales volume with respect to the average total portfolio value.
   * - ReturnOverMaxDrawdown
     - decimal
     - Provides a risk adjusted way to factor in the returns and drawdown of the strategy. It is calculated by dividing the Portfolio Annualized Return by the Maximum Drawdown seen during the backtest.
   * - SortinoRatio
     - decimal
     - Gives a relative picture of the strategy volatility. It is calculated by taking a portfolio's annualized rate of return and subtracting the risk free rate of return.
   * - EstimatedMonthlyAlphaValue
     - decimal
     - Suggested Value of the Alpha On A Monthly Basis For Licensing.
   * - TotalInsightsGenerated
     - string
     - The total number of insight signals generated by the algorithm.
   * - TotalInsightsClosed
     - string
     - The total number of insight signals generated by the algorithm.
   * - TotalInsightsAnalysisCompleted
     - string
     - The total number of insight signals generated by the algorithm.
   * - MeanPopulationEstimatedInsightValue
     - decimal
     - Gets the mean estimated insight value.

|

----------------------------------------------------------------

Chart Object
============

Single Parent Chart Object for Custom Charting.

Attributes
^^^^^^^^^^

.. list-table::
   :header-rows: 1

   * - Attribute
     - Type
     - Description
   * - name
     - string
     - Name of the Chart.
   * - chartType
     - string
     - Type of the Chart. Either 'Overlayed' or 'Stacked'.
   * - series
     - A Series object
     - List of Series Objects for this Chart.

|

----------------------------------------------------------------

Order Object
============

Order struct for placing new trade.

Attributes
^^^^^^^^^^

.. list-table::
   :header-rows: 1

   * - Attribute
     - Type
     - Description
   * - id
     - int
     - Order Id.
   * - contingentId
     - int
     - Order Id to process before processing this order.
   * - brokerId
     - int
     - Brokerage Id for this order for when the brokerage splits orders into multiple pieces.
   * - symbol
     - Array of strings
     -
   * - price
     - decimal
     - Price of the order.
   * - priceCurrency
     - string
     - Currency for the order price.
   * - time
     - string
     - Gets the utc time the order was created.
   * - createdTime
     - string
     - Gets the utc time this order was created. Alias for Time.
   * - lastFillTime
     - string
     - Gets the utc time the last fill was received, or null if no fills have been received.
   * - lastUpdateTime
     - string
     - Gets the utc time this order was last updated, or null if the order has not been updated.
   * - canceledTime
     - string
     - Gets the utc time this order was canceled, or null if the order was not canceled.
   * - quantity
     - decimal
     - Number of shares to execute.
   * - type
     - string
     - Order type. Either 'Market', 'Limit', 'StopMarket', 'StopLimit', 'MarketOnOpen', 'MarketOnClose', or 'OptionExercise'.
   * - status
     - string
     - Status of the Order. Either 'New', 'Submitted', 'PartiallyFilled', 'Filled', 'Canceled', 'None', 'Invalid', 'CancelPending', or 'UpdateSubmitted'.
   * - tag
     - string
     - Tag the order with some custom data.
   * - securityType
     - string
     - Type of tradable security / underlying asset. Either 'Base', 'Equity', 'Option', 'Commodity', 'Forex', 'Future', 'Cfd' or 'Crypto'.
   * - direction
     - string
     - Direction of the order. Either 'Buy', 'Sell', or 'Hold'.
   * - value
     - decimal
     - Gets the executed value of this order. If the order has not yet filled, then this will return zero.
   * - orderSubmissionData
     - An Order Submission Data object
     - Stores time and price information available at the time an order was submitted.
   * - isMarketable
     - Boolean
     - Returns true if the order is a marketable order.

|

----------------------------------------------------------------

Order Event Object
==================

Messaging class signifying a change in an order state and record the change in the user's algorithm portfolio.

Attributes
^^^^^^^^^^

.. list-table::
   :header-rows: 1

   * - Attribute
     - Type
     - Description
   * - orderId
     - int
     - Id of the order this event comes from.
   * - id
     - int
     - The unique order event id for each order.
   * - symbol
     - A Symbol object.
     - A unique security identifier.
   * - utcTime
     - string
     - The date and time of this event (UTC).
   * - status
     - string
     - Fill status of the order class. Either 'New', 'Submitted', 'PartiallyFilled', 'Filled', 'Canceled', 'None', 'Invalid', 'CancelPending' or 'UpdateSubmitted'.
   * - orderFee
     - An Order Fee object.
     - The fee associated with the order.
   * - fillPrice
     - decimal
     - Fill price information about the order.
   * - fillPriceCurrency
     - string
     - Currency for the fill price.
   * - fillQuantity
     - decimal
     - Number of shares of the order that was filled in this event.
   * - direction
     - string.
     - Direction of the order. Either 'Buy', 'Sell', or 'Hold'.
   * - message
     - string
     - Any message from the exchange.
   * - isAssignment
     - Boolean
     - True if the order event is an assignment.
   * - stopPrice
     - decimal
     - The current stop price.
   * - limitPrice
     - decimal
     - The current limit price.
   * - quantity
     - decimal
     - The current order quantity.

|

----------------------------------------------------------------

Symbol Object
=============

Represents a unique security identifier. This is made of two components, the unique SID and the Value. The value is the current ticker symbol while the SID is constant over the life of a security.

Attributes
^^^^^^^^^^

.. list-table::
   :header-rows: 1

   * - Attribute
     - Type
     - Description
   * - value
     - string
     - The current symbol for this ticker.
   * - id
     - string
     - The security identifier for this symbol.
   * - permtick
     - string
     - The current symbol for this ticker.

|

----------------------------------------------------------------

Insight Score Object
====================

Defines the scores given to a particular insight.

Attributes
^^^^^^^^^^

.. list-table::
   :header-rows: 1

   * - Attribute
     - Type
     - Description
   * - updatedTimeUtc
     - string
     - Gets the time these scores were last updated.
   * - direction
     - double
     - Gets the direction score.
   * - magnitude
     - double
     - Gets the magnitude score.
   * - isFinalScore
     - Boolean
     - Gets whether or not this is the insight's final score.

|

----------------------------------------------------------------

Series Object
============

Series data and properties for a chart.

Attributes
^^^^^^^^^^

.. list-table::
   :header-rows: 1

   * - Attribute
     - Type
     - Description
   * - name
     - string
     - Name of the series.
   * - unit
     - string
     - Axis for the chart series.
   * - index
     - int
     - Index/position of the series on the chart.
   * - values
     - Array of Chart Point objects
     - Values for the series plot. These values are assumed to be in ascending time order (first points earliest, last points latest).
   * - seriesType
     - string
     - Chart type for the series. Either 'Line', 'Scatter', 'Candle', 'Bar', 'Flag', 'StackedArea', 'Pie' or 'Treemap'.
   * - color
     - string
     - Color the series.
   * - scatterMarkerSymbol
     - string
     - Shape or symbol for the marker in a scatter plot. Either 'none', 'circle', 'square', 'diamond', 'triangle' or 'triangle-down'.
   * - _updatePosition
     - int
     - Index of the last fetch update request to only retrieve the "delta" of the previous request.

|

----------------------------------------------------------------

Order Submission Data Object
============================

Stores time and price information available at the time an order was submitted.

Attributes
^^^^^^^^^^

.. list-table::
   :header-rows: 1

   * - Attribute
     - Type
     - Description
   * - bidPrice
     - decimal
     - The bid price at an order submission time.
   * - askPrice
     - decimal
     - The ask price at an order submission time.
   * - lastPrice
     - decimal
     - The last price at an order submission time.

|

----------------------------------------------------------------

Order Fee Object
================

The order fee associated with the specified order.

Attributes
^^^^^^^^^^

.. list-table::
   :header-rows: 1

   * - Attribute
     - Type
     - Description
   * - value
     - A Cash Amount object
     - A cash amount which can be converted to account currency using a currency converter.

|

----------------------------------------------------------------

Chart Point Object
==================

Chart Point Value Type for QCAlgorithm.Plot().

Attributes
^^^^^^^^^^

.. list-table::
   :header-rows: 1

   * - Attribute
     - Type
     - Description
   * - x
     - decimal
     - Time of this chart point: lower case for javascript encoding simplicty.
   * - y
     - decimal
     - Value of this chart point:  lower case for javascript encoding simplicty.

|

----------------------------------------------------------------

Cash Amount Object
==================

Chart Point Value Type for QCAlgorithm.Plot().

Attributes
^^^^^^^^^^

.. list-table::
   :header-rows: 1

   * - Attribute
     - Type
     - Description
   * - x
     - decimal
     - Time of this chart point: lower case for javascript encoding simplicty.
   * - y
     - decimal
     - Value of this chart point:  lower case for javascript encoding simplicty.

|

----------------------------------------------------------------

Create a Backtest
-----------------

Create a new backtest.

Path
====

``POST`` /backtests/create

Request
=======

.. code-block::

    {
      "projectId": 000000001,
      "compileId": 12345,
      "backtestName": "My first backtest"
    }

.. list-table::
   :header-rows: 1

   * - Parameter
     - Type
     - Description
   * - projectId ``(Required)``
     - int
     - Project Id of the project which was compiled.
   * - compileId ``(Required)``
     - int
     - Compile Id returned from the compile creation request.
   * - backtestName ``(Required)``
     - string
     - Name for the new backtest

Response
========

Returns the newly created Backtest object.

.. code-block::

    {
      "name": "My first backtest",
      "backtestId": "abc123",
      "completed": false,
      "progress": 0.5
    }

|

----------------------------------------------------------------

Read a Backtest
---------------

Read out a single backtest from one of the user's projects.

Path
====

``POST`` /backtests/read

Request
=======

.. code-block::

    {
      "projectId": 000000001,
      "backtestId": "abc123"
    }

.. list-table::
   :header-rows: 1

   * - Parameter
     - Type
     - Description
   * - projectId ``(Required)``
     - int
     - Project Id of the project from which to read a backtest.
   * - backtestId ``(Required)``
     - string
     - Compile-specific backtest Id of the backtest to read.

Response
========

Returns the requested Backtest object.

.. code-block::

    {
      "name": "My first backtest",
      "backtestId": "abc123",
      "completed": false,
      "progress": 0.5
    }

|

----------------------------------------------------------------

Update a Backtest
-----------------

Update a backtest's name and/or note.

Path
====

``POST`` /backtests/update

Request
=======

.. code-block::

    {
      "projectId": 000000001,
      "backtestId": "abc123",
      "name": "My backtest's new name",
      "note": "My personal note"
    }

.. list-table::
   :header-rows: 1

   * - Parameter
     - Type
     - Description
   * - projectId ``(Required)``
     - int
     - Project Id for the backtest we want to update.
   * - backtestId ``(Required)``
     - string
     - Backtest Id of the backtest we want to update.
   * - name
     - string
     - Name we'd like to assign to the backtest.
   * - note
     - string
     - Note attached to the backtest.

Response
========

Returns a RestResponse object which indicates whether the request executed successfully.

.. code-block::

    {
      "success": true,
    }

|

----------------------------------------------------------------

Delete a Backtest
-----------------

Delete the specified backtest from the specified project.

Path
====

``POST`` /backtests/delete

Request
=======

.. code-block::

    {
      "projectId": 000000001,
      "backtestId": "abc123"
    }

.. list-table::
   :header-rows: 1

   * - Parameter
     - Type
     - Description
   * - projectId ``(Required)``
     - int
     - Project Id for the backtest we want to delete.
   * - backtestId ``(Required)``
     - string
     - Backtest Id of the backtest we want to delete.

Response
========

Returns a RestResponse object which indicates whether the request executed successfully.

.. code-block::

    {
      "success": true,
    }

|

----------------------------------------------------------------

List Backtests
--------------

Get details from all of the backtests for the specified project.

Path
====

``POST`` /backtests/read

Request
=======

.. code-block::

    {
      "projectId": 000000001
    }

.. list-table::
   :header-rows: 1

   * - Parameter
     - Type
     - Description
   * - projectId ``(Required)``
     - int
     - Project Id of the project from which to read a backtest.

Response
========

Returns a Backtest List object containing all of the requested Backtest objects.

.. code-block::

    {
      "backtests": [
        {
          "name": "My first backtest",
          "backtestId": "abc123",
          "completed": false,
          "progress": 0.5
        },
        {
          "name": "My second backtest",
          "backtestId": "def456",
          "completed": false,
          "progress": 0.5
        }
      ],
      "success": true
    }

|

----------------------------------------------------------------

Read a Backtest Report
----------------------

Read out the report from the specified backtest.

Path
====

``POST`` /backtests/read/report

Request
=======

.. code-block::

    {
      "projectId": 000000001,
      "backtestId": "abc123"
    }

.. list-table::
   :header-rows: 1

   * - Parameter
     - Type
     - Description
   * - projectId ``(Required)``
     - int
     - Project Id of the project from which to read a backtest report.
   * - backtestId ``(Required)``
     - string
     - Compile-specific backtest Id of the backtest report to read.

Response
========

Returns a Backtest Report object.

.. code-block::

    {
      "report": "Backtest report data",
      "success": true
    }

|