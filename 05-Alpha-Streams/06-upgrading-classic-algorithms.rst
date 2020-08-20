.. _alpha-streams-upgrading-classic-algorithms:

============================================
Alpha Streams - Upgrading Classic Algorithms
============================================

|

.. list-table:: Demonstration Algorithms
   :header-rows: 1

   * - C#
     - Python

   * - `ConvertToFrameworkAlgorithm.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/ConvertToFrameworkAlgorithm.cs>`_
     - `ConvertToFrameworkAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/ConvertToFrameworkAlgorithm.py>`_

|

Introduction
============
With a few code adjustments, you can quickly turn a classic algorithm into an Alpha Streams capable strategy. The algorithm must emit ``Insight`` objects to be classified as an Alpha.

|

Converting Classic Algorithms
=============================
The only step required to convert your classic algorithm to be compatible with Alpha Streams is to modify your algorithm to emit Insight objects at an appropriate time. The default QCAlgorithm behavior is to emit Insights whenever a trade is placed, but manually emitting Insights allows you to inject information about the magnitude and confidence of the Insight, which adds value to the Alpha and can boost your overall score.

**Call EmitInsights**

Insight direction generally lines up with what type of order (Up/Buy, Down/Sell) you want to place, but *Flat* Insights are required for Liquidate statements. To emit Insights, you need to call the function before placing a trade. Take care in setting the insight properties, and don't emit insights superfluously or they could negatively affect your overall score.

.. tabs::

   .. code-tab:: c#

        // Call EmitInsights with insights created in correct direction,
        // here we're going short. The EmitInsights method can accept multiple insights
        // separated by commas.
        EmitInsights(
            // Creates an insight for our symbol, predicting that it will move down within
            // the fast ema period number of days
            Insight.Price(_symbol, TimeSpan.FromDays(FastEmaPeriod), InsightDirection.Down));

   .. code-tab:: py

        # Call EmitInsights with insights created in correct direction,
        # here we're going short. The EmitInsights method can accept multiple insights
        # separated by commas.
        self.EmitInsights(
            # Creates an insight for our symbol, predicting that it will move down within
            #  the fast ema period number of days
            Insight.Price(self.symbol, timedelta(self.FastEmaPeriod), InsightDirection.Down))

In creating these insights you should think about:

* What is the time period/expected holding period of my insight?
* Does my trade signal give me a confidence of investment?
* What is the expected move magnitude?

If you cannot determine these values with some intrinsic data-driven hypothesis, we recommend leaving them unset. This information can provide value to your algorithm that goes beyond pure performance and is one of the benefits of manual Insight creation.

|

Determining Insight Confidence and Magnitude
============================================
Your Insight period, magnitude, and confidence are important properties that can be hard to generate when porting a classic algorithm. See the `Creating Alpha <https://www.quantconnect.com/docs/alpha-streams/creating-an-alpha#Creating-an-Alpha-Determining-Insight-Confidence-and-Magnitude>`_ section for more information.