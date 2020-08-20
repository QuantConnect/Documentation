.. _research-research-to-production:

======================
Research to Production
======================

|

Introduction
============

The ultimate goal of research is to produce a strategy which we can backtest and eventually live trade. Once we've developed a hypothesis that we are confident in, we can start working towards exporting our research into backtesting. We will need to translate the code in research, which relies on QuantBook into a format compatible with QCAlgorithm.

|

API Context
===========

The most notable difference is that research uses QuantBook while backtesting uses QCAlgorithm. QuantBook is a wrapper on QCAlgorithm, which means QuantBook allows you to access all the methods available to QCAlgorithm; However, QuantBook contains some additional methods not found in QCAlgorithm, like ``GetFutureHistory`` and ``GetOptionHistory``. This means that if we utilize one of these methods in research to request historical data, we will need to replace them with their QCAlgorithm counterpart GetHistory. ``GetFundamental`` in research does not have any counterpart in backtesting. Fundamental data is accessed through universe selection in backtesting. Visit the Universe Selection documentation to learn more.

We can, for most intents and purposes, replace `qb` with ``self`` when exporting code to backtesting. For example, consider the following code in the research environment.

.. code-block::

    # Initialize QuantBook
    qb = QuantBook()

    # Subscribe to SPY data with QuantBook
    spy = qb.AddEquity("SPY")

    # Make history call with QuantBook
    history = qb.History(spy.Symbol, timedelta(days=10), Resolution.Daily)

We can export this into backtesting by removing QuantBook and replacing ``qb`` with ``self`` by setting ``qb = self``.

.. code-block::

    def Initialize(self):

        # Set qb to instance of QCAlgorithm
        qb = self

        # Subscribe to SPY data with QCAlgorithm
        spy = qb.AddEquity("SPY")

        # Make history call with QCAlgorithm
        history = qb.History(spy.Symbol, timedelta(days=10), Resolution.Daily)

Keep in mind that in the research environment, all the data is directly available. While in backtesting, the available data is purposefully limited to the data at or before the agorithm time. This means that if we make a history request for the previous 10 days of data, in research we will receive the previous 10 days of data from today's date, while in backtesting we will receive the previous 10 days of data from the algorithm time.

|

Research to Production Example
==============================

**Mean Reversion**

Imagine that we've developed the following hypothesis: stocks that are below 1 standard deviation of their 30 day mean are due to revert and increase in value. We've developed the following code in research to pick out such stocks from a preselected basket of stocks.

.. code-block::

    import numpy as np
    qb = QuantBook()

    symbols = {}
    assets = ["SHY", "TLT", "SHV", "TLH", "EDV", "BIL",
    "SPTL", "TBT", "TMF", "TMV", "TBF", "VGSH", "VGIT",
    "VGLT", "SCHO", "SCHR", "SPTS", "GOVT"]

    for i in range(len(assets)):
        symbols[assets[i]] = qb.AddEquity(assets[i],Resolution.Minute).Symbol

    # Fetch history on our universe
    df = qb.History(qb.Securities.Keys, 30, Resolution.Daily)

    # Make all of them into a single time index.
    df = df.close.unstack(level=0)

    # Calculate the truth value of the most recent price being less than 1 std away from the mean
    classifier = df.le(df.mean().subtract(df.std())).tail(1)

    # Get indexes of the True values
    classifier_indexes = np.where(classifier)[1]

    # Get the Symbols for the True values
    classifier = classifier.transpose().iloc[classifier_indexes].index.values

    # Get the std values for the True values (used for magnitude)
    magnitude = df.std().transpose()[classifier_indexes].values

    # Zip together to iterate over later
    selected = zip(classifier, magnitude)

Once we are confident in our hypothesis, we can export this code into backtesting. We want to ultimately go long on the stocks that have been selected. One way to accomodate this model into research is to create a scheduled event which uses our model to pick stocks and goes long.

.. code-block::

    def Initialize(self):

            #1. Required: Five years of backtest history
            self.SetStartDate(2014, 1, 1)

            #2. Required: Alpha Streams Models:
            self.SetBrokerageModel(BrokerageName.AlphaStreams)

            #3. Required: Significant AUM Capacity
            self.SetCash(1000000)

            #4. Required: Benchmark to SPY
            self.SetBenchmark("SPY")

            self.SetPortfolioConstruction(EqualWeightingPortfolioConstructionModel())
            self.SetExecution(ImmediateExecutionModel())

            self.assets = ["IEF", "SHY", "TLT", "IEI", "SHV", "TLH", "EDV", "BIL",
                          "SPTL", "TBT", "TMF", "TMV", "TBF", "VGSH", "VGIT",
                          "VGLT", "SCHO", "SCHR", "SPTS", "GOVT"]

            self.symbols = {}

            # Add Equity ------------------------------------------------
            for i in range(len(self.assets)):
                self.symbols[self.assets[i]] = self.AddEquity(self.assets[i],Resolution.Minute).Symbol

            # Set Scheduled Event Method For Our Model
            self.Schedule.On(self.DateRules.Every(DayOfWeek.Monday), self.TimeRules.AfterMarketOpen("IEF", 1), self.EveryDayAfterMarketOpen)

Now we export our model into the scheduled event method. We will switch ``qb`` with ``self`` and replace methods with their QCAlgorithm counterparts as needed. In this example, this is not an issue because all the methods we used in research also exist in QCAlgorithm.

.. code-block::

    def EveryDayAfterMarketOpen(self):
            qb = self
            # Fetch history on our universe
            df = qb.History(qb.Securities.Keys, 5, Resolution.Daily)

            # Make all of them into a single time index.
            df = df.close.unstack(level=0)

            # Calculate the truth value of the most recent price being less than 1 std away from the mean
            classifier = df.le(df.mean().subtract(df.std())).tail(1)

            # Get indexes of the True values
            classifier_indexes = np.where(classifier)[1]

            # Get the Symbols for the True values
            classifier = classifier.transpose().iloc[classifier_indexes].index.values

            # Get the std values for the True values (used for magnitude)
            magnitude = df.std().transpose()[classifier_indexes].values

            # Zip together to iterate over later
            selected = zip(classifier, magnitude)

            # ==============================

            insights = []

            for symbol, magnitude in selected:
                insights.append( Insight.Price(symbol, timedelta(days=5), InsightDirection.Up, magnitude) )

            self.EmitInsights(insights)

Now that our model in research has been exported to backtesting, we can further analyze its performance with its backtesting metrics. And if we are still confident in our model, we can eventually live trade this strategy.