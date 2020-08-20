.. _algorithm-reference-securities-and-portfolio:

==============================================
Algorithm Reference - Securities and Portfolio
==============================================

|

Demonstration Algorithms
========================

.. list-table::
   :header-rows: 1

   * - C#
     - Python
   * - `AddRemoveSecurityRegressionAlgorithm.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/AddRemoveSecurityRegressionAlgorithm.cs>`_
     - `AddRemoveSecurityRegressionAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/AddRemoveSecurityRegressionAlgorithm.py>`_

|

Introduction
============

Algorithms have a ``Securities`` property that stores a Security object for each asset in your algorithm. ``Security`` objects hold the models (backtesting behaviors) and properties of an asset. Each security can be completely customized to behave as you'd like. Securities is a ``Dictionary<Symbol, Security>`` so you can access your Security objects with their ticker ``Securities["IBM"].Price``.

.. tabs::

   .. code-tab:: c#

        // Popular Securities Property Values:
        Securities["IBM"].HasData           // Security has data
                         .Invested          // Have holdings
                         .LocalTime         // Time on the asset exchange
                         .Holdings          // Portfolio object
                         .Exchange          // Exchange information
                         .FeeModel;         // Fee model setter

   .. code-tab:: py

        // Popular Securities Property Values:
        self.Securities["IBM"].HasData           # Security has data
                         .Invested          # Have holdings
                         .LocalTime         # Time on the asset exchange
                         .Holdings          # Portfolio object
                         .Exchange          # Exchange information
                         .FeeModel;         # Fee model setter

Security objects also carry all the models for creating realistic backtests. These models are set via the public security properties and then used in LEAN to improve your backtest realism.

The Portfolio property is a collection of SecurityHolding objects to provide easy access to the holding properties. This property also provides information about the whole portfolio state:

.. tabs::

   .. code-tab:: c#

        // Popular Portfolio Property Values:
        Portfolio.Invested                // Hold at least one stock
                 .Cash                    // Sum of all currencies in account (only unsettled cash)
                 .UnsettledCash           // Sum of all currencies in account (only settled cash)
                 .TotalFees               // Fees incurred since backtest start
                 .TotalHoldingsValue      // Absolute sum portfolio items
                 .MarginRemaining         // Remaining margin on the account
                 .TotalMarginUsed         // Sum of margin used across all securities
                 .TotalPortfolioValue     // Portfolio equity
                 .TotalProfit             // Sum of all gross profit
                 .TotalUnrealizedProfit   // Holdings profit/loss

   .. code-tab:: py

        // Popular Portfolio Property Values:
        self.Portfolio.Invested                # Hold at least one stock
                      .Cash                    # Sum of all currencies in account (only settled cash)
                      .UnsettledCash           # Sum of all currencies in account (only unsettled cash)
                      .TotalFees               # Fees incurred since backtest start
                      .TotalHoldingsValue      # Absolute sum portfolio items
                      .MarginRemaining         # Remaining margin on the account
                      .TotalMarginUsed         # Sum of margin used across all securities
                      .TotalPortfolioValue     # Portfolio equity
                      .TotalProfit             # Sum of all gross profit
                      .TotalUnrealizedProfit   # Holdings profit/loss

The Portfolio class is a ``Dictionary<Symbol, SecurityHolding>``, so it can be accessed via ticker index: ``Portfolio["IBM"].IsLong``

.. tabs::

   .. code-tab:: c#

        // Popular Portfolio Property Values:
        Portfolio["IBM"].Invested
                        .IsLong            // IsLong, IsShort Holdings.
                        .Quantity          // Shares held.
                        .UnrealizedProfit; // Holdings profit/loss
                        .TotalFees         // Fees incurred since backtest start
                        .Price;            // Asset price

   .. code-tab:: py

        // Popular Portfolio Property Values:
        self.Portfolio.Invested                # Hold at least one stock
                      .Cash                    # Sum of all currencies in account (only settled cash)
                      .UnsettledCash           # Sum of all currencies in account (only unsettled cash)
                      .TotalFees               # Fees incurred since backtest start
                      .TotalHoldingsValue      # Absolute sum portfolio items
                      .MarginRemaining         # Remaining margin on the account
                      .TotalMarginUsed         # Sum of margin used across all securities
                      .TotalPortfolioValue     # Portfolio equity
                      .TotalProfit             # Sum of all gross profit
                      .TotalUnrealizedProfit   # Holdings profit/loss

Detailed information on these classes can be found in the LEAN documentation. Check out the ``Security`` (Securities objects), ``SecurityPortfolioManager`` class, and ``SecurityHolding`` (Portfolio objects) classes.

.. tabs::

   .. code-tab:: c#

        //Access to Security Objects with Securities:
        Securities["IBM"].Price

        //Security object properties:
        class Security {
            Resolution Resolution;
            bool HasData;
            bool Invested;
            DateTime LocalTime;
            SecurityHolding Holdings;
            SecurityExchange Exchange;
            IFeeModel FeeModel;
            IFillModel FillModel;
            ISlippageModel SlippageModel;
            ISecurityPortfolioModel PortfolioModel;
            ISecurityMarginModel MarginModel;
            ISettlementModel SettlementModel;
            IVolatilityModel VolatilityModel;
            ISecurityDataFilter DataFilter;
        }

   .. code-tab:: py

        #Access to Security Objects with Securities:
        self.Securities["IBM"].Price

        #Security object properties:
        class Security {
            Resolution Resolution;
            bool HasData;
            bool Invested;
            DateTime LocalTime;
            SecurityHolding Holdings;
            SecurityExchange Exchange;
            IFeeModel FeeModel;
            IFillModel FillModel;
            ISlippageModel SlippageModel;
            ISecurityPortfolioModel PortfolioModel;
            ISecurityMarginModel MarginModel;
            ISettlementModel SettlementModel;
            IVolatilityModel VolatilityModel;
            ISecurityDataFilter DataFilter;
        }

|

Active Securities
=================

The ActiveSecurities lets you select the assets currently in your universe. This is useful for iterating over those securities from your universe selection. It has all the same properties as the Securities collection.

.. tabs::

   .. code-tab:: c#

        // Securities currently in the universe:
        ActiveSecurities["IBM"].HasData           // Security has data
                         .Invested          // Have holdings
                         .LocalTime         // Time on the asset exchange
                         .Holdings          // Portfolio object
                         .Exchange          // Exchange information
                         .FeeModel;         // Fee model setter

   .. code-tab:: py

        # Securities currently in the universe:
        self.ActiveSecurities["IBM"].HasData           # Security has data
                         .Invested          # Have holdings
                         .LocalTime         # Time on the asset exchange
                         .Holdings          # Portfolio object
                         .Exchange          # Exchange information
                         .FeeModel;         # Fee model setter