.. _algorithm-reference-overview:

==============================
Algorithm Reference - Overview
==============================

|

Introduction
============

QuantConnect's LEAN engine manages your portfolio and data feeds, letting you focus on your algorithm strategy and execution. Data is piped into your strategy via event handlers, upon which you can place trades. We automatically provide basic portfolio management and fill modeling underneath the hood. This is provided by the ``QCAlgorithm`` base class.

All algorithms extend ``QCAlgorithm``, which provides some key helper properties for you to use: Security Manager, Portfolio Manager, Transactions Manager, Notification Manager, and Scheduling Manager. Along with hundreds of helper methods to make the API easy to use. We'll go into more detail in the next few sections.

The ``Securities`` property is a dictionary of Security objects. Each asset (equity, forex pair, etc) in your algorithm has a security object. All the models for a security live on these objects: e.g. ``self.Securities["IBM"].FeeModel`` or ``self.Securities["IBM"].Price``.

``Portfolio`` is a dictionary of SecurityHolding classes. These classes track the individual portfolio items profit and losses, fees, and quantity held. e.g. ``self.Portfolio["IBM"].LastTradeProfit``.

Other helpers like ``Transactions``, ``Schedule``, ``Notify``, and ``Universe`` have their own helper methods, which we'll explain in the following sections.

.. tabs::

   .. code-tab:: c#

        public class QCAlgorithm
        {
                SecurityManager Securities;   //Array of Security objects.
                SecurityPortfolioManager Portfolio;  // Array of SecurityHolding objects
                SecurityTransactionManager Transactions;  // Transactions helper
                ScheduleManager Schedule;  // Scheduling helper
                NotificationManager Notify; //Email, SMS helper
                UniverseManager Universe; // Universe helper

                //Set up Requested Data, Cash, Time Period.
                public virtual void Initialize() { ... };

                //Event Handlers:
                public virtual OnData(Slice data) { ... };
                public virtual OnDividend() { ... };
                public virtual OnEndOfDay() { ... };
                public virtual OnEndOfAlgorithm() { ... };

                //Helpers...
                public SimpleMovingAverage SMA();
        }

   .. code-tab:: py

        def QCAlgorithm
        {
                self.Securities;   # Array of Security objects.
                self.Portfolio;    # Array of SecurityHolding objects
                self.Transactions; # Transactions helper
                self.Schedule;    # Scheduling helper
                self.Notify;      # Email, SMS helper
                self.Universe;    # Universe helper

                # Set up Requested Data, Cash, Time Period.
                def Initialize:

                # Other Event Handlers:
                def OnData(self, slice):
                def OnEndOfDay(self, symbol):
                def OnEndOfAlgorithm():

                # Indicator Helpers
                def SMA():
        }

|