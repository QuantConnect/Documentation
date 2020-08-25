.. _alpha-streams-creating-an-alpha:

=================
Creating an Alpha
=================

|

Introduction
============
To create an Alpha for the marketplace, you need just two principal components from the Framework:

#. A Universe Selection Model, to determine the assets your Alpha will be trading; and
#. An Alpha Model, to generate Insight objects on selected assets.

The rest of the Algorithm Framework (Portfolio Construction, Execution, and Risk Management) are related to position sizing and trade management and not relevant to an *external fund.* In this section, we will only cover creating an Alpha using the Algorithm Framework. If you have a Classic Algorithm, please see the documentation for :ref:`Upgrading Classic Algorithms <alpha-streams-upgrading-classic-algorithms>`.

All algorithms utilizing the QuantConnect Algorithm Framework are eligible for licensing in the Alpha Streams marketplace.

|

Choosing An Investment Thesis
=============================
The investment thesis is arguably the most important and artistic part of the alpha creation process. Here you should imagine a hypothesis which will guide your implementation. The thesis is typically a quantifiable effect (something we can measure) that we believe has an impact on our selected assets.

Each Alpha should implement one investment thesis. Combining multiple theories in a single strategy prevents external funds from being able to discern the components. If your algorithm has other signals you believe should be used together, list them as a separate Alpha and link to them in the Alpha description.

Some examples of a focused alpha model are:

* "Profitability Alpha" - Company profitability as a signal of its upward motion.
* "Public Sentiment Alpha" - Public opinion as an indicator of company trajectory.
* "Product Demand Alpha" - Tracking sales to project the trajectory of a company.

Your alpha *does not need to perform well in all markets* to be a good alpha. The fund consuming the alpha will need to decide when to apply your signal. In fact, the more focused your alpha is, the more widely applicable it will be to potential clients.

|

Selecting Your Universe
=======================
Universe Selection is the first model of the Algorithm Framework. QuantConnect provides four techniques for selecting an asset universe: Coarse, Fine, Scheduled, and Manual Universes. For more information on defining your universe, see the documentation on :ref:`Algorithm Framework/Universe Selection <algorithm-framework-universe-selection>`. Depending on your algorithm, you can likely use one of these helpers to select the stocks required.

**Manual Universe Selection**

Manual Universes are primarily used for a fixed set of assets representing a broader index. You may be selecting a basket of ETFs or a set of Forex Symbols representing the entire universe.

**Coarse and Fine Universe Selection**

Coarse and Fine Universes allow you to select US Equities based on price-volume action or corporate fundamental data. They are useful for filtering large volumes of information down to a smaller actionable set of assets.

**Scheduled Universe Selection**

Scheduled Universes trigger at defined points in time and run a generic function. This is useful for using an external API or file to define your universe source. As we must ensure Alpha algorithms are robust, only some external data sources are approved for Alpha Streams. Make sure to get your data approved before submission to Alpha Streams. DropBox/FTP driven universes require your continual effort, so are not an approved universe source.

|

Generating Insights
===================
The next component of your algorithm is the Alpha Model. It is responsible for generating the Insight objects Funds consume as factors for their portfolio. Your Alpha Model should return a list or enumerable of Insight objects from its ``Update()`` method. For more information on creating Insights, see :ref:`AlgorithmFramework / Alpha Creation <algorithm-framework-alpha-creation>`.

An ``Insight`` constructor takes the following arguments:

.. tabs::

   .. code-tab:: c#

        // Insight Constructor Arguments
        // new Insight(symbol, period, type, confidence=null, magnitude=null, source=null, weight=null);
        var insight = new Insight("IBM", TimeSpan.FromMinutes(20), InsightType.Price, InsightDirection.Up, 0.0025m, 1.0m, "MyAlphaModel", 0.25m);

        var insight = Insight.Price("IBM", TimeSpan.FromMinutes(20), InsightDirection.Up, 0.0025m, 1.0m, "MyAlphaModel", 0.25m);

   .. code-tab:: py

        # Insight Constructor Arguments:
        # Insight(symbol, timedelta, type, direction, magnitude=None, confidence=None, sourceModel=None, weight=None)
        Insight("IBM", timedelta(minutes=20), InsightType.Price, InsightDirection.Up, 0.0025, 1.00, "MyAlphaModel", 0.25)

        insight = Insight.Price("IBM", timedelta(minutes = 20), InsightDirection.Up, 0.0025, 1.00, "MyAlphaModel", 0.25)

We have provided a helper method to make creating Insights easier. This can be used in your Update method to create insight objects for your Alpha Model of the Price type:

.. code-block:: py

        insight = Insight.Price("IBM", timedelta(minutes = 20), InsightDirection.Up, 0.0025, 1.00, "MyAlphaModel", 0.25)

For generating insights with Classic Algorithms, see :ref:`Upgrading Classic Algorithms <alpha-streams-upgrading-classic-algorithms>`.

|

.. _alpha-streams-creating-an-alpha-determining-insight-confidence-magnitude-and-weight:

Determining Insight Confidence, Magnitude, and Weight
=====================================================
To maximize the compatibility and use of an Alpha Model, it should populate as many fields as possible. As the author, you should put some thought into the abstract, relatively artistic properties of an Insight.

**Insight Period**

How long do you expect the signal to last? Can you use any information from the data you're consuming to build a theory on the Insight time frame? Although tricky questions, with some creative thought you can likely estimate this field. High-frequency strategies generate short insights; foundational moves in the economy produce much longer predictions.

**Insight Magnitude**

What is the expected return from your Insight? Will this data cause a large change in the asset price within the time frame you are specifying? The Insight magnitude indicates how tradable the signal is for the Fund. The price movement, combined with the asset volume, gives the depth of the signal and an estimate of how much capital can be deployed to your Alpha.

**Insight Confidence**

How strong is your signal? Some Portfolio Construction techniques, such as Black Litterman, allow using the confidence of the signal as a factor in the allocation. When applicable, include Confidence in your Alpha Model, so consumers can factor this into their decision.

**Insight Weight**

How much emphasis should a fund put on your signal? The weight property is essential in using the Framework models, as it allows funds to allocate capital based on Insight weight using either the Framework models or their own.

|

Submitting an Alpha for Review
==============================

Once your Alpha is ready to be submitted, you can create a profile for it through your `Alpha Streams Dashboard <https://www.quantconnect.com/alpha/dashboard>`_. Here you can manage your Alphas and edit the pricing and description information.

.. figure:: https://cdn.quantconnect.com/docs/i/alpha-dashboard.png

For more information on submitted a new Alpha see  :ref:`Submitting an Alpha <alpha-streams-submitting-an-alpha>`.