====================================
Alpha Streams - Submitting an Alpha
====================================

|

Introduction
============
Alphas submitted to be licensed are hosted live in QuantConnect's production environment for free by QuantConnect. Their insights are recorded in real-time and timestamped to the millisecond.

To get an Alpha listed, we ask for information about the author along with meta information about the strategy itself, such as a description of its focus. This information will be consumed by marketplace participants to determine whether to license an Alpha.

Once the Alpha is approved and deployed, it begins the process of generating a live track record. Insights generated out-of-sample are marked as such and displayed separately in the marketplace.

To get started, navigate to your `fund dashboard <https://www.quantconnect.com/alpha/dashboard>`_ to submit an Alpha.

.. figure:: https://cdn.quantconnect.com/docs/i/alpha-dashboard.png

|

Submission Information
======================
Alpha submissions provide some information funds can use to help them license your strategy. Start by giving your strategy an appropriate name for the Alpha along with a description of the Alpha strategy. Most Alphas are relatively simple, so a sentence or two is fine here. Alpha pricing is covered in the `Pricing an Alpha <https://www.quantconnect.com/docs/alpha-streams/pricing-an-alpha>`_ section.

.. figure:: https://cdn.quantconnect.com/docs/i/alpha-dashboard-description.png

Don't worry too much about making this perfect; you can update these fields later as your Alpha improves.

From there, you should select an icon to represent the Alpha, along with your project and the backtest with the code snapshot you are submitting. For funds to consume the Alphas, they must generate Insight objects, so only Framework or Upgraded Classic algorithms are supported.

.. figure:: https://cdn.quantconnect.com/docs/i/alpha-dashboard-icons.png

Submitted Alphas require a short review process to ensure they are technically sound and follow the community guidelines. We'll explore this in the next section.

|

Minimum Criteria
================
Before your submission is sent to us for the final code review, we run an analysis on the selected backtest. This is done to ensure that seven essential criteria are met.

+----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Pre-Submission Screening Criteria                                                                                                                                                                                                                                                                                                            |
+=======================================+======================================================================================================================================================================================================================================================================================================+
| **Length**                            | Backtests must be a minimum of 5 years in length.                                                                                                                                                                                                                                                    |
+---------------------------------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| **Recent History**                    | The backtest must include the most most recent 5 years of data and can't have an End Date more than 7 days priod to the date of submission.                                                                                                                                                          |
+---------------------------------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| **Alpha Streams Brokerage Model**     | ``AlphaStreamsBrokerageModel()`` Alphas are required to use the `Alpha Streams Brokerage Model <https://www.quantconnect.com/docs/alpha-streams/alpha-fee-models>`_ and cannot include any additional `Reality Modeling <https://www.quantconnect.com/docs/algorithm-reference/reality-modelling>`_. |
+---------------------------------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| **Profitability**                     | All Alpha submissions must be profitable over the course of the backtest.                                                                                                                                                                                                                            |
+---------------------------------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| **Known Data Sources**                | We can't guarantee the integrity of any external data sources. Only data sources supported by QuantConnect can be used in Alphas.                                                                                                                                                                    |
+---------------------------------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| **PSR Greater than 80%**              | All Alpha submissions must have a Probabilistic Sharpe Ratio (PSR) of greater than 80%.                                                                                                                                                                                                              |
+---------------------------------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| **Low Correlation to Current Alphas** | Alphas need to be sufficiently unique, so we test the correlation between submissions and an author's other accepted Alphas. Correlation must be less than 85%. Updates to current versions are not tested for correlation.                                                                          |
+---------------------------------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+

|

Subsequent Review Process
=========================
Once your Alpha has met the minimum criteria listed above, it is then reviewed by our team. We aim to not impose any value bias during an Alpha review, but we need to ensure they are mechanically sound. When reviewing your Alpha, we will consider the following points:

**Stateless & Resilient**

Mechanisms should be set up to restore the internal state of an Alpha in the event of reboots. Alphas will be very long-running; over the course of months or years, the server will need patches and occasional restarting. The alpha should be smart enough to automatically recover in the event of restart using History and WarmUp API methods.

**Grounded in Reality**

Alphas should have clear strategic reasoning underpinning the strategy. A single reason-based foundation for the algorithm is necessary to understand the alpha behavior when things go wrong, and it helps improve your alpha application for funds. Obscure correlations or overfitted strategies tend to perform poorly out of sample.

**Transparent Sourcing**

Authors need a transparent employment history and alphas must not infringe on other IP. We'd like to know the employment history of authors on the platform to ensure the alpha is not infringing on any intellectual property, including your current and past employers. Public or shared content may be used as a foundation and extended. Where you may have licensed the IP from a third party, presenting consents for usage are acceptable.

**Edge Case Handling**

Alphas need to be able to handle edge cases such as Dividends, Splits, and Delistings. This adds another layer of resiliency and will keep an Alpha running in the event of data changes. This can involve simple control logic, checking ``data.ContainsKey(symbol)``, explicitly removing certain affected securities from the Universe, or other methods.

**Supported Data Sources**

When a fund licenses an Alpha, we need to guarantee them that it will perform without error and be of the highest quality possible. To do this, we can't accept any submissions that use an external data source that is not built into LEAN (i.e., dropbox files, SubscriptionDataReader, etc.). If we don't directly support the data, then we are unable to guarantee its integrity and that it will be maintained faithfully.

We currently don't support futures or option data in Alpha Streams. We are actively working on this and hope to enable futures and options support soon.

**Insights**

Insights are predictions about the price movement of specific securities and are required in all Alpha Streams submissions. For classic algorithms, one of the `Insight constructors <https://www.quantconnect.com/docs/alpha-streams/creating-an-alpha>`_ must be used and emitted using the `EmitInsights API <https://www.quantconnect.com/docs/alpha-streams/upgrading-classic-algorithms>`_. An Insight must be emitted before any orders are placed. Insights provide funds with information about the predictive power of your models and give them insight into why the orders they see are being placed. For Framework-style algorithms, Insights need to be generated in the Update() method of the Alpha Model. These will then be used in the Portfolio Construction model to build a portfolio.

**Daily Data**

We aggregate our `Daily data <https://www.quantconnect.com/docs/key-concepts/understanding-time>`_ and then pass it through the algorithm at 00:00 UTC the day after (i.e., the Daily resolution TradeBar for 2019-10-22 will pass through the algorithm at 2019-10-23 00:00). When using daily data, any operations performed during an intraday event will be using stale data. Using daily data can lead to unexpected results and trades as well as unrealistic performance. In general, it is best to use Minute or Hour resolution and a `Scheduled Event <https://www.quantconnect.com/docs/algorithm-reference/scheduled-events>`_ to perform daily operations to achieve realistic performance.

**Open-Source IP**

We provide the community with lots of example algorithms. A few examples to get you started can be found in `this blog post <https://www.quantconnect.com/blog/from-research-to-production-tutorials/>`_, or on `GitHub <https://github.com/QuantConnect/Lean/tree/master/Algorithm.Python>`_. The goal of this is to demonstrate how to use the API correctly, incorporate new data sources into their existing algorithms, implement our recommended best practices for Alpha Streams, and more. However, we cannot accept any copies or near-copies of our demonstration algorithms into Alpha Streams. We love to see that our work inspired someone, but each submission must provide sufficient originality that the work can indeed be called the author's own.

**Overfitting**

`Overfitting <https://www.quantconnect.com/docs/key-concepts/research-guide#Research-Guide-What-Is-Overfitting>`_ will doom an algorithm in live trading. To prevent this and try to boost the quality of submissions, we can't accept any Alphas that obviously overfit to data. Overfitting can manifest itself in countless ways, but the most common things we see are:

* Coding of indicator parameters that work for certain hand-picked assets but perhaps not for any others.
* Using thresholds for indicator values that are hard-coded and have no fundamental theory behind their value.
* Look-ahead bias, such as hard-coding specific dates to perform specific actions. This can only be done if there is prior knowledge of an event. Look-ahead bias might boost the backtest, but it does not mean the model is fundamentally valuable and can sustain performance during future outliers/extreme events.
* Selection bias, such as picking stocks known ahead of time to perform exceptionally well during specific periods.

**Slow Recovery**

Algorithms that fail to recover from a drawdown within 6 months will likely not be accepted. Funds understand that all algorithms experience drawdowns, and the smaller, the better, but the recovery time is especially important. An algorithm that is in a sustained drawdown for more than 6-months most likely won't be traded by a fund and would likely be dropped if this occurs in live trading.

**Infrequent Insight Generation or Trading**

Algorithms don't need to emit Insights or trade daily or intraday, but the maximum holding period for funds is usually a matter of days or weeks. Anything longer than monthly-rebalancing likely won't be accepted. Alphas need to place at least 10 trades per month for the majority of the backtest.

**IP Infringement**

All Alphas must be the intellectual property (IP) of the submitting author.