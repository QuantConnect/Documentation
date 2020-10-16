.. _platform-api-live:

==================
Project Management
==================

The QuantConnect.com API exposes methods for creating, reading, listing, and deleting projects in the user's account.

|

----------------------------------------------------------------

Live Algorithm Data Types
-------------------------

This is a list of data types unique to the Live API. For other common data types, see the see :ref:`backtest management <platform-api-backtest`> page.

Live Algorithm Object
=====================

Live algorithm instance result from the QuantConnect Rest API.

Attributes
^^^^^^^^^^

.. list-table::
   :header-rows: 1

   * - Attribute
     - Type
     - Description
   * - projectId
     - int
     - Project id for the live instance.
   * - deployId
     - string
     - Unique live algorithm deployment identifier (similar to a backtest id).
   * - status
     - string
     - The state of the live deployment. Either 'DeployError', 'InQueue', 'Running', 'Stopped', 'Liquidated', 'Deleted', 'Completed', 'RuntimeError', 'Invalid', 'LoggingIn', 'Initializing', or 'History'.
   * - launched
     - string
     - Date and time the algorithm was launched in UTC.
   * - stopped
     - string
     - Date and time the algorithm was stopped in UTC. Null if it's still running.
   * - brokerage
     - string
     - The brokerage with which this live algorithm trades. Either "Interactive", "FXCM", "Oanda", "Tradier", or "PaperTrading".
   * - subscription
     - string
     - Chart we're subscribed to.
   * - error
     - string
     - Live algorithm error message from a crash or algorithm runtime error.
   * - success
     - Boolean
     - Indicate if the API request was successful.
   * - errors
     - Array of strings
     - List of errors with the API call, if any.

|

----------------------------------------------------------------

Live Algorithm Log Object
=========================

A Live Algorithm Log object contains logs from a live algorithm.

Attributes
^^^^^^^^^^

.. list-table::
   :header-rows: 1

   * - Attribute
     - Type
     - Description
   * - liveAlgorithmLog
     - Array of strings
     - List of logs from the live algorithm.
   * - success
     - Boolean
     - Indicate if the API request was successful.
   * - errors
     - Array of strings
     - List of errors with the API call, if any.

|

----------------------------------------------------------------

Live Algorithm Results Object
=============================

Details a live algorithm from the "live/read" Api endpoint.

Attributes
^^^^^^^^^^

.. list-table::
   :header-rows: 1

   * - Attribute
     - Type
     - Description
   * - liveResults
     - A Live Results Data object
     - Holds information about the state and operation of the live running algorithm
   * - success
     - Boolean
     - Indicate if the API request was successful.
   * - errors
     - Array of strings
     - List of errors with the API call, if any.

|

----------------------------------------------------------------

Base Live Algorithm Settings Object
===================================

Base class for settings that must be configured per Brokerage to create new algorithms via the API.

Attributes
^^^^^^^^^^

.. list-table::
   :header-rows: 1

   * - Attribute
     - Type
     - Description
   * - id
     - string
     - Either 'Interactive', 'FXCM', 'Oanda', 'Tradier', 'PaperTrading'
   * - user
     - string
     - Username associated with brokerage
   * - password
     - string
     - Password associated with brokerage
   * - environment
     - string
     - Represents the types of environments supported by brokerages for trading. Either 'live' or 'paper'
   * - account
     - string
     - Account of the associated brokerage

|

----------------------------------------------------------------

Live Results Data Object
========================

Holds information about the state and operation of the live running algorithm.

Attributes
^^^^^^^^^^

.. list-table::
   :header-rows: 1

   * - Attribute
     - Type
     - Description
   * - version
     - int
     - Results version
   * - resolution
     - string
     - Resolution of data requested. Either 'Tick', 'Second', 'Minute', 'Hour', or 'Daily'.
   * - results
     - A Live Result object
     - A class for packaging live result data.

|

----------------------------------------------------------------

Live Result Object
==================

A class for packaging live result data.

Attributes
^^^^^^^^^^

.. list-table::
   :header-rows: 1

   * - Attribute
     - Type
     - Description
   * - holdings
     - Dictionary [string:Holding object]
     - Holdings dictionary of algorithm holdings information.
   * - cash
     - A CashBook object
     - Keeps track of the different cash holdings of an algorithm. Is also a Dictionary of Cash objects.
   * - alphaRuntimeStatistics
     - An Alpha Runtime Statistics object
     - Contains insight population run time statistics.
   * - charts
     - A Chart object
     - Charts updates for the live algorithm since the last result packet.
   * - orders
     - An Order object
     - Order updates since the last result packet.
   * - orderEvents
     - An array of Order Event objects
     - Order Event updates since the last result packet.
   * - profitLoss
     - decimal
     - Trade profit and loss information since the last algorithm result packet.
   * - statistics
     - Dictionary [string:string]
     - Statistics information sent during the algorithm operations.
   * - runtimeStatistics
     - Dictionary [string:string]
     - Runtime banner/updating statistics in the title banner of the live algorithm GUI.
   * - serverStatistics
     - Dictionary [string:string]
     - Server status information, including CPU/RAM usage, ect...

|

-----------------------------------------------------------------

Holding Object
==================

A class for packaging live result data.

Attributes
^^^^^^^^^^

.. list-table::
   :header-rows: 1

   * - Attribute
     - Type
     - Description
   * - symbol
     - A Symbol object
     - Symbol of the Holding.
   * - type
     - string
     - Type of tradable security / underlying asset. Either 'Base', 'Equity', 'Option', 'Commodity', 'Forex', 'Future', 'Cfd' or 'Crypto'.
   * - currencySymbol
     - string
     - The currency symbol of the holding, such as $.
   * - averagePrice
     - decimal
     - Average Price of our Holding in the currency the symbol is traded in.
   * - quantity
     - decimal
     - Quantity of the Symbol we hold.
   * - marketPrice
     - decimal
     - Current market price of the Asset in the currency the symbol is traded in.
   * - conversionRate
     - decimal
     - Current market conversion rate into the account currency.
   * - marketValue
     - decimal
     - Current market value of the holding.
   * - unrealizedPnl
     - decimal
     - Current unrealized P/L of the holding.

|

----------------------------------------------------------------

CashBook Object
===============

Keeps track of the different cash holdings of an algorithm. Is also a Dictionary of Cash objects.

Attributes
^^^^^^^^^^

.. list-table::
   :header-rows: 1

   * - Attribute
     - Type
     - Description
   * - _accountCurrency
     - string
     - The base currency used.
   * - _currencies
     - Dictionary [String:Cash]
     - Tracks cash holdings.
   * - TotalValueInAccountCurrency
     - decimal
     - The total value of the cash book in units of the base currency.
   * - AccountCurrency
     - string
     - Gets account currency

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

Create a Live Algorithm
-----------------------

Create a live algorithm.

Path
====

``POST`` /live/create

Request
=======

.. code-block::

    {
      "projectId": 12345,
      "compileId": "ABC123",
      "serverType": "",
      "baseLiveAlgorithmSettings": {},
      "versionId": ""
    }

.. list-table::
   :header-rows: 1

   * - Parameter
     - Type
     - Description
   * - projectId ``(Required)``
     - int
     - Id of the project on QuantConnect.
   * - compileId ``(Required)``
     - string
     - Id of the compilation on QuantConnect.
   * - serverType ``(Required)``
     - string
     - Type of server instance that will run the algorithm.
   * - baseLiveAlgorithmSettings ``(Required)``
     - A Base Live Algorithm Settings object
     - Settings that must be configured per Brokerage to create new algorithms via the API.
   * - versionId
     - string
     - The version of the Lean used to run the algorithm. -1 is master, however, sometimes this can create problems with live deployments. If you experience problems using, try specifying the version of Lean you would like to use.

Response
========

Returns a Live Algorithm object.

.. code-block::

    {
      "projectId": 12345,
      "deployId": "ABC123",
      "status": "Stopped",
      "launched": "2020-09-30 10:00:00",
      "stopped": "2020-09-30 10:30:00",
      "brokerage": "Oanda",
      "subscription": "Chart A",
      "success": true,
    }

|

----------------------------------------------------------------

Read a Live Algorithm
---------------------

Read a live algorithm.

Path
====

``POST`` /live/read

Request
=======

.. code-block::

    {
      "projectId": 12345,
      "deployId": "ABC123"
    }

.. list-table::
   :header-rows: 1

   * - Parameter
     - Type
     - Description
   * - projectId ``(Required)``
     - int
     - Id of the project to read.
   * - deployId ``(Required)``
     - string
     - Specific instance id to read.

Response
========

Returns a Live Algorithm Results object.

.. code-block::

    {
      "liveResults": {
        "version": 1
        "resolution": "Minute",
        "results": {
          "holdings": {
            "Holding A": {
              "symbol":
              "type":
              "currencySymbol":
              "averagePrice":
              "quantity":
              "marketPrice":
              "conversionRate":
              "marketValue":
              "unrealizedPnl":
            }
          },
          "cash": {
            "_accountCurrency": CashBook
            "_currencies":
            "TotalValueInAccountCurrency":
            "AccountCurrency":
          }
          "alphaRuntimeStatistics":
          "charts":
          "orders":
          "orderEvents":
          "profitLoss":
          "statistics":
          "runtimeStatistics":
          "serverStatistics":
        }
      },
      "success": true,
    }

|

----------------------------------------------------------------