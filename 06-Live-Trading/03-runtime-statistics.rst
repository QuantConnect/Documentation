.. _live-trading-runtime-statistics:

=================================
Live Trading - Runtime Statistics
=================================

|

.. list-table:: Demonstration Algorithm
   :header-rows: 1

   * - C#
     - Py

   * - `LiveFeaturesAlgorithm.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/LiveFeaturesAlgorithm.cs>`_
     - `LiveFeaturesAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/LiveFeaturesAlgorithm.py>`_

Runtime statistics are custom defined banner statistics displayed across the top of your live algorithm.

.. figure:: https://cdn.quantconnect.com/web/i/docs/docs-live-runtime.png

You can add your own runtime statistics using the ``SetRuntimeStatistic(string name, string value);`` method. In the image above, the user displays the current value of BTC: ``SetRuntimeStatistic("BTC", data["BTC"].Close);``

.. tabs::

   .. code-tab:: c#

        // User displays the current value of BTC
        SetRuntimeStatistic("BTC", data["BTC"].Close);

   .. code-tab:: py

        # User displays the current value of BTC
        btc = self.Securities["BTC"].Symbol
        self.SetRuntimeStatistic("BTC", data[btc].Close)