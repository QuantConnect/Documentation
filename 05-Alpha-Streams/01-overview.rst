==============================
Alpha Streams - Overview
==============================

|

Introduction
============
Alpha Streams is a global marketplace where Quants and Funds meet. Quants offer their alpha (algorithms) for licensing, and Funds can quickly locate crowd-sourced strategies that fit their target criteria. QuantConnect acts as an independent third party to verify the strategy performance, and run it out of sample in our production environment on live market data.

.. figure:: https://cdn.quantconnect.com/web/i/alpha/how-it-works-graphic-rev2.png

|

Licensing Mode
==============
Alpha Streams licensing is performed with a monthly subscription, prorated on a daily basis. Funds can manage their subscriptions programmatically, licensing your Alpha as their models require. A subscription revenue model for Alpha has several benefits over a classic revenue sharing model:

* No Audit Requirements: Assigning a profit value to a factor is subjective and requires knowledge of the fund's existing portfolio and models.
* No Allocation Tracking: Whether or not a fund allocates capital, your Alpha still generated value and will receive the same revenue from licensing.
* Optimized Alpha Value: An efficient price for an Alpha can be discovered faster with a simple subscription, maximizing the revenues for authors when Alphas are performing well, and allowing funds to cut expenses when doing poorly.

QuantConnect directly charges the funds a 30% fee for reviewing, hosting, and serving the algorithms. Charging funds directly ensures as much licensing revenue is passed back to you, the creator, as possible.

|

What Is An Alpha?
=================
An Alpha is an algorithm that generates expected return predictions funds can use to help manage their portfolio. Alphas solely focus on this prediction, actively ignoring position sizing and management. Fund managers with existing portfolios prefer to consume your signals as a factor to their model. To see more about creating an Alpha for the marketplace, see the next section, `Creating An Alpha <https://www.quantconnect.com/docs/alpha-streams/creating-an-alpha>`_.

Some examples of a focused alpha model are:

* "Profitability Alpha" - Company profitability as a signal of its upward motion.
* "Public Sentiment Alpha" - Public opinion as an indicator of company trajectory.
* "Product Demand Alpha" - Tracking sales to project the trajectory of a company.

Your alpha *does not need to perform well in all markets* to be a good alpha. The fund consuming the alpha will need to decide when to apply your signal. In fact, the more focused your alpha is, the more widely applicable it will be to potential clients.

|

What Is An Insight?
===================
Alphas create an expected return prediction called an ``Insight`` for assets in their universe. These Insight objects hold information funds need to act on your signals; for example, an Insight could signal *AAPL* to go *Up* by *0.2%* within *10 Minutes;* with *60%* confidence in the outcome.

Insights include the following pieces of information:

* Symbol - Which asset are you signaling? ``[Required]``
* Direction - Which direction are you expecting the asset to move? ``[Required]``
* Magnitude - What is the predicted move of this asset?
* Period - When will this insight complete?
* Confidence - How strong is this signal?
* Weight - How much capital should a fund allocate to, or emphasize, this signal? ``[Required]``

Only the Symbol and Direction are required properties; however, the other properties add depth to your prediction, which increases its usefulness and value in the marketplace.

If you are using the Algorithm Framework, your ``AlphaModel`` will yield Insight objects from your Update() method. We'll cover more about emitting insights in the next section, `Creating An Alpha <https://www.quantconnect.com/docs/alpha-streams/creating-an-alpha>`_.

.. tabs::

   .. code-tab:: c#

       public AlphaModel
        {
        IEnumerable<Insight> Update(QCAlgorithmFramework algorithm, Slice data)
            {
               yield return new Insight(security.Symbol, _period, _type, .... );
            }
        }

   .. code-tab:: py

        class ConstantAlphaModel(AlphaModel):
            def Update(self, algorithm, data):
               insights = []
               return insights

|

What Characteristics Are Desirable For Funds?
=============================================
Every fund is looking for unique characteristics. Some are looking to extend their portfolio for new uncorrelated markets, while others are looking for hedges to derisk their holdings. Others may be taking a broad spectrum approach and licensing algorithms to get a sense of the community. As the Alpha Streams market grows, there will be more and more buyers for your algorithm. Generally speaking, you should not design an Alpha for a specific fund, but just explore topics you find interesting.

Your Alpha should focus on a single investment thesis. It can be tempting to make an Alpha performance "perfect," overfitting to past scenarios. However, if you factor in multiple environmental conditions, the funds may see it as overfitting your strategy.

Do not worry if the performance isn't perfect for all market conditions - it is up to the funds consuming your alpha to combine Alphas to make a single portfolio strategy. As such, there are no performance requirements for Alphas. Some algorithms perform well over time; others perform well seasonally. We believe any performance judgment by QuantConnect would infer a selection bias on the resulting marketplace.

