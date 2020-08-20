.. _live-trading-institutional-accounts:

======================
Institutional Accounts
======================

|

.. list-table:: Demonstration Algorithms
   :header-rows: 1

   * - C#
     - Py

   * - `FinancialAdvisorDemoAlgorithm.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/FinancialAdvisorDemoAlgorithm.cs>`_
     - `FinancialAdvisorDemoAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/FinancialAdvisorDemoAlgorithm.py>`_


QuantConnect supports live trading with financial advisor (FA) accounts. These certified professionals can use a single trading algorithm to manage several clients accounts. This feature is only available with a `professional subscription <https://www.quantconnect.com/upgrade>`_ on QuantConnect.

The only supported brokerage with FA accounts is Interactive Brokers. In the Initialize method, we need to set the ``DefaultOrderProperties`` property to ``InteractiveBrokersOrderProperties``:

.. tabs::

   .. code-tab:: c#

        // The default order properties can be set here to choose the FA settings
        // to be automatically used in any order submission method (such as SetHoldings, Buy, Sell and Order)

        // Use a default FA Account Group with an Allocation Method
        DefaultOrderProperties = new InteractiveBrokersOrderProperties
        {
            // account group created manually in IB/TWS
            FaGroup = "TestGroupEQ",
            // supported allocation methods are: EqualQuantity, NetLiq, AvailableEquity, PctChange
            FaMethod = "EqualQuantity"
        };

        // set a default FA Allocation Profile
        DefaultOrderProperties = new InteractiveBrokersOrderProperties
        {
            // allocation profile created manually in IB/TWS
            FaProfile = "TestProfileP"
        };

        // send all orders to a single managed account
        DefaultOrderProperties = new InteractiveBrokersOrderProperties
        {
            // a sub-account linked to the Financial Advisor master account
            Account = "DU123456"
        };

   .. code-tab:: py

        # Use a default FA Account Group with an Allocation Method
        self.DefaultOrderProperties = InteractiveBrokersOrderProperties()
        # account group created manually in IB/TWS
        self.DefaultOrderProperties.FaGroup = "TestGroupEQ"
        # supported allocation methods are: EqualQuantity, NetLiq, AvailableEquity, PctChange
        self.DefaultOrderProperties.FaMethod = "EqualQuantity"

        # set a default FA Allocation Profile
        self.DefaultOrderProperties = InteractiveBrokersOrderProperties()
        # allocation profile created manually in IB/TWS
        self.DefaultOrderProperties.FaProfile = "TestProfileP"

        # send all orders to a single managed account
        DefaultOrderProperties = InteractiveBrokersOrderProperties()
        # a sub-account linked to the Financial Advisor master account
        self.DefaultOrderProperties.Account = "DU123456"

.. code-block::

    //InteractiveBrokersOrderProperties object properties:
    class InteractiveBrokersOrderProperties {
        string Account     // Linked account for which to submit the order
        string FaGroup     // Account group for the order
        string FaMethod    // Allocation method for the account group order: EqualQuantity, NetLiq, AvailableEquity, PctChange
        int FaPercentage   // Percentage for the percent change method
        string FaProfile   // Allocation profile to be used for the order
    }

``DefaultOrderProperties`` properties can be overridden throughout the code to accommodate different scenarios. For instance, the group may have a different allocation method, and these properties need to be overwritten before calling an order method (SetHoldings, Buy, Sell, LImitOrder, etc).

.. tabs::

   .. code-tab:: c#

        // Change FA Account Group and Allocation Method
        ((InteractiveBrokersOrderProperties)DefaultOrderProperties).FaGroup = "TestGroupAE";
        ((InteractiveBrokersOrderProperties)DefaultOrderProperties).FaMethod = "AvailableEquity";
        SetHoldings("AAPL", 1);

   .. code-tab:: py

        # Change FA Account Group and Allocation Method
        self.DefaultOrderProperties.FaGroup = "TestGroupAE"
        self.DefaultOrderProperties.FaMethod = "AvailableEquity"
        self.SetHoldings("AAPL", 1)
