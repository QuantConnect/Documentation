.. _algorithm-framework-risk-management:

=====================================
Algorithm Framework - Risk Management
=====================================

|

.. list-table:: Demonstration Algorithms
   :header-rows: 1

   * - C#
     - Py
   * - `Do Nothing - NullRiskManagementModel.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm/Risk/NullRiskManagementModel.cs>`_
     - `Do Nothing - NullRiskManagementModel.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm/Risk/NullRiskManagementModel.py>`_
   * - `Limit Allowed Drawdown - MaximumDrawdownPercentPerSecurity.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Risk/MaximumDrawdownPercentPerSecurity.cs>`_
     - `Limit Allowed Drawdown - MaximumDrawdownPercentPerSecurity.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Risk/MaximumDrawdownPercentPerSecurity.py>`_
   * - `Limit Sector Exposure - MaximumSectorExposureRiskManagementModel.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Risk/MaximumSectorExposureRiskManagementModel.cs>`_
     - `Limit Sector Exposure - MaximumSectorExposureRiskManagementModel.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Risk/MaximumSectorExposureRiskManagementModel.py>`_.

|

Introduction
============

.. figure:: https://cdn.quantconnect.com/web/i/docs/algorithm-framework/risk-management.png

The Risk Management Model seeks to manage risk on the ``PortfolioTarget`` collection created by the Portfolio Construction Model. It is applied to the targets *before* they reach the Execution Model. There are many creative ways to manage risk. Some examples of this might be:

* *"Trailing Stop Risk Management Model"* Seek to automatically create and manage trailing stop-loss orders for open positions.
* *"Option Hedging Risk Management Model"* Automatically purchase options to hedge large equity exposures.
* *"Sector Exposure Risk Management Model"* Seek to reduce position sizes when overexposed to sectors or individual assets, keeping the portfolio within diversification requirements.
* *"Flash Crash Detection Risk Management Model"* Scan for strange market situations which might be precursors to a flash crash and attempt to protect the portfolio when detected.

To set a Risk Management Model use the ``AddRiskManagement()`` method:

.. tabs::

   .. code-tab:: c#

        // Setting a risk management model
        AddRiskManagement( new NullRiskManagementModel() );

   .. code-tab:: py

         # Setting a risk management model
        self.AddRiskManagement( NullRiskManagementModel() )

|

Risk Management Model Structure
===============================


The Risk Management Model should extend the RiskManagementModel class and has one required method: ``ManageRisk()``, which receives an array of ``PortfolioTarget`` objects. When an adjustment of the targets is required, you should return the *changed* targets only. Optionally you can also use the ``OnSecuritiesChanged()`` event.

.. tabs::

   .. code-tab:: c#

        class MaximumDrawdownPerSecurity : RiskManagementModel
        {
            // Adjust the portfolio targets and return them. If no changes emit nothing.
            List<PortfolioTarget> ManageRisk(QCAlgorithmFramework algorithm, PortfolioTarget[] targets)      {
            }

            // Optional: Be notified when securities change
            void OnSecuritiesChanged(QCAlgorithmFramework algorithm, SecurityChanges changes)
            {
            }
        }

   .. code-tab:: py

        class MaximumDrawdownPerSecurity(RiskManagementModel):
            # Adjust the portfolio targets and return them. If no changes emit nothing.
            def ManageRisk(self, algorithm, targets):
                return []

            # Optional: Be notified when securities change
            def OnSecuritiesChanged(self, algorithm, changes):
                pass

|

Maximum Drawdown Risk Management Module
=======================================

The Maximum Drawdown Risk Management Module monitors portfolio holdings, and when extended beyond a predefined drawdown limit, it liquidates the portfolio.

You can view the C# *implementation* of this model in `GitHub <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Risk/MaximumDrawdownPercentPerSecurity.cs>`_ or the Python *implementation* of this model in `GitHub <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Risk/MaximumDrawdownPercentPerSecurity.py>`_.

|

Sector Exposure Risk Management Module
======================================

The Sector Exposure Risk Management Module limits the exposure to a specific industry *sector* to a predefined maximum percentage. This requires assets that are selected by Morningstar fine fundamental data.

You can view the C# *implementation* of this model in `GitHub <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Risk/MaximumSectorExposureRiskManagementModel.cs>`_ or the Python *implementation* of this model in `GitHub <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Risk/MaximumSectorExposureRiskManagementModel.py>`_.
