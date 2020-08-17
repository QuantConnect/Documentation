======================================
Algorithm Reference - Scheduled Events
======================================

|

Demonstration Algorithms
========================

.. list-table::
   :header-rows: 1

   * - C#
     - Python
   * - `ScheduledEventsAlgorithm.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/ScheduledEventsAlgorithm.cs>`_
     - `ScheduledEventsAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/ScheduledEventsAlgorithm.py>`_

|

Introduction
=============

Scheduled events allow you to trigger code to run at specific times of day. This happens regardless of your data events. The schedule API requires a date and time rule to specify when the event is fired.

Scheduled events need a ``DateRules`` and ``TimeRules`` pair to set a specific time. When the event is triggered, the action block is executed.

|

DateTime Rules
==============

.. list-table::
   :header-rows: 1

   * - DateRules Class
     - Example Usage
   * - On(year,month,day)
     - ``DateRules.On(2013, 10, 7)``/``self.DateRules.On(2013, 10, 7)`` Trigger an event on a specific date.
   * - EveryDay(symbol)
     - ``DateRules.EveryDay("SPY")``/``self.DateRules.EveryDay("SPY")`` Trigger an event every day a specific symbol is trading.
   * - EveryDay()
     - ``DateRules.EveryDay()``/``self.DateRules.EveryDay()`` Trigger an event every day.
   * - Every(days)
     - ``DateRules.Every(DayOfWeek.Monday, DayOfWeek.Friday)``/``self.DateRules.Every(DayOfWeek.Monday, DayOfWeek.Friday)`` Trigger an event on specific days during week.

.. list-table::
   :header-rows: 1

   * - TimeRules Class
     - Example Usage
   * - At(hour, min)
     - ``TimeRules.At(13, 10)``/``self.TimeRules.At(13, 10)`` Trigger an event at a specific time of day (e.g. 13:10).
   * - AfterMarketOpen(symbol, min)
     - ``TimeRules.AfterMarketOpen("SPY", 10)``/``self.TimeRules.AfterMarketOpen("SPY", 10)`` Trigger an event a few minutes after market open for a specific symbol.
   * - BeforeMarketClose(symbol, min)
     - ``TimeRules.BeforeMarketClose("SPY", 10)``/``self.TimeRules.BeforeMarketClose("SPY", 10)`` Trigger an event a few minutes before close for a specific symbol.
   * - Every(period)
     - ``TimeRules.Every(TimeSpan.FromMinutes(10))``/``self.TimeRules.Every(TimeSpan.FromMinutes(10))`` Trigger an event every period interval.

.. tabs::

   .. code-tab:: c#

        // Schedule an event to fire at a specific date/time
        Schedule.On(DateRules.On(2013, 10, 7), TimeRules.At(13, 0), () =>
        {
            Log("SpecificTime: Fired at : " + Time);
        });

        // Schedule an event to fire every trading day for a security
        // The time rule here tells it to fire 10 minutes after SPY's market open
        Schedule.On(DateRules.EveryDay("SPY"), TimeRules.AfterMarketOpen("SPY", 10), () =>
        {
            Log("EveryDay.SPY 10 min after open: Fired at: " + Time);
        });

        // Schedule an event to fire every trading day for a security
        // The time rule here tells it to fire 10 minutes before SPY's market close
        Schedule.On(DateRules.EveryDay("SPY"), TimeRules.BeforeMarketClose("SPY", 10), () =>
        {
            Log("EveryDay.SPY 10 min before close: Fired at: " + Time);
        });

        // Schedule an event to fire on certain days of the week
        Schedule.On(DateRules.Every(DayOfWeek.Monday, DayOfWeek.Friday), TimeRules.At(12, 0), () =>
        {
            Log("Mon/Fri at 12pm: Fired at: " + Time);
        });

   .. code-tab:: py

        # schedule an event to fire at a specific date/time
        self.Schedule.On(self.DateRules.On(2013, 10, 7), \
                         self.TimeRules.At(13, 0), \
                         self.SpecificTime)

        # schedule an event to fire every trading day for a security the
        # time rule here tells it to fire 10 minutes after SPY's market open
        self.Schedule.On(self.DateRules.EveryDay("SPY"), \
                         self.TimeRules.AfterMarketOpen(self.spy, 10), \
                         self.EveryDayAfterMarketOpen)

        # schedule an event to fire every trading day for a security the
        # time rule here tells it to fire 10 minutes before SPY's market close
        self.Schedule.On(self.DateRules.EveryDay("SPY"), \
                         self.TimeRules.BeforeMarketClose("SPY", 10), \
                         self.EveryDayBeforeMarketClose)

        # schedule an event to fire on certain days of the week
        self.Schedule.On(self.DateRules.Every(DayOfWeek.Monday, DayOfWeek.Friday), \
                         self.TimeRules.At(12, 0), \
                         self.EveryMonFriAtNoon)

        # the scheduling methods return the ScheduledEvent object which can be used
        # for other things here I set the event up to check the portfolio value every
        # 10 minutes, and liquidate if we have too many losses
        self.Schedule.On(self.DateRules.EveryDay(), \
                         self.TimeRules.Every(timedelta(minutes=10)), \
                         self.LiquidateUnrealizedLosses)

        # schedule an event to fire at the beginning of the month, the symbol is
        # optional.
        # if specified, it will fire the first trading day for that symbol of the month,
        # if not specified it will fire on the first day of the month
        self.Schedule.On(self.DateRules.MonthStart("SPY"), \
                         self.TimeRules.AfterMarketOpen("SPY"), \
                         self.RebalancingCode)

        def SpecificTime(self):
            self.Log("SpecificTime: Fired at : {0}".format(self.Time))

        def EveryDayAfterMarketOpen(self):
            self.Log("EveryDay.SPY 10 min after open: Fired at: {0}".format(self.Time))

        def EveryDayBeforeMarketClose(self):
            self.Log("EveryDay.SPY 10 min before close: Fired at: {0}".format(self.Time))

        def EveryMonFriAtNoon(self):
            self.Log("Mon/Fri at 12pm: Fired at: {0}".format(self.Time))

        def LiquidateUnrealizedLosses(self):
            ''' if we have over 1000 dollars in unrealized losses, liquidate'''
            if self.Portfolio.TotalUnrealizedProfit < -1000:
                self.Log("Liquidated due to unrealized losses at: {0}".format(self.Time))
                self.Liquidate()

        def RebalancingCode(self):
            ''' Good spot for rebalancing code?'''
            pass
