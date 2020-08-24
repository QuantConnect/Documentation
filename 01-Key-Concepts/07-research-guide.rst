.. _key-concepts-research-guide:

==============
Research Guide
==============

|

Introduction
============

QuantConnect aims to teach and inspire our community to create high performing algorithmic trading strategies. We measure our success by the profits created by the community through their live trading. As such, we try to build the best quantitative research techniques possible into the product to encourage a robust research process.

|

Hypothesis-Driven Research
==========================

QuantConnect recommends developing an algorithmic trading strategy based on a central hypothesis. An algorithm hypothesis should be developed at the start of your research, and the remaining time spent exploring how to test your theory. If you find yourself deviating from your core theory, or introducing code that isn't based around that hypothesis, you should stop and go back to thesis development.

Hypothesis development is somewhat of an art and requires creativity and great observation skills. It is one of the most powerful skills a quant can learn. We recommend that an algorithm hypothesis follow the pattern of cause and effect. Your aim should be to express your strategy in the following sentence:

``A change in {cause} leads to an {effect}.``

You can search for inspiration by considering causes from your own experience, intuition, or the media. Generally, causes of financial market movements fall into three categories: human psychology, real-world events/fundamentals, or invisible financial actions.

Consider the following examples:

.. list-table::
   :widths: 40 20 40
   :header-rows: 1

   * - Cause
     - leads to
     - Effect
   * - Share class stocks are the same company so any price divergence is irrational...
     - >
     - A perfect pairs trade. Since they are the same company the price will revert.
   * - New stock addition to the S&P500 Index causes fund managers to buy up stock...
     - >
     - An increase in the price of the new asset in the universe from buying pressure.
   * - Increase in sunshine-hours increases the production of oranges...
     - >
     - An increase in the supply of oranges, decreasing price of Orange Juice Futures.
   * - Allegations of fraud by the CEO causes investor faith in the stock to fall...
     - >
     - A collapse of stock prices for the company as people panic.
   * - FDA approval of a new drug opens up new markets for the pharmaceutical company...
     - >
     - A jump in stock prices for the company.
   * - Increasing federal interest rates restrict lending from banks, raising interest rates...
     - >
     - Restricted REIT leverage and lower REIT ETF returns.

There are millions of potential alpha strategies to explore, each of them a candidate for an algorithm. Once you have chosen a strategy, we recommend exploring it no more than 8-32 hours, depending on your coding ability.

|

Research Panel
==============

We launched the Research Guide in 2019 to inform you about common quantitative research pitfalls. It displays a power gauge for the number of backtests performed, the number of parameters used, and the time invested in the strategy. These measures can give a ballpark estimate of the overfitting risk of the project. Generally, as a strategy becomes more overfit on historical data, it is less likely to perform well in live trading. These properties were selected based on the recommended best practices of the global quantitative research community.

.. figure:: https://cdn.quantconnect.com/docs/i/research-guide_rev0.png

|

|battery-bars| **Restricting Backtests**

.. |battery-bars| image:: https://cdn.quantconnect.com/terminal/i/backtest_tab_icons/battery_3bars_rev0.svg

According to current research, the number of backtests performed on an idea should be limited to prevent overfitting. In theory, each backtest performed on an idea moves it one step closer to being overfitted as you are testing and selecting for strategies written into your code instead of being based on a central thesis. For more information, see the paper "`Probability of Backtest Overfitting <https://papers.ssrn.com/sol3/papers.cfm?abstract_id=2326253>`_."

QuantConnect does not restrict the number of backtests performed on a project, but we have implemented the counter as a guide for your reference. Your coding skills are a factor in how many backtests constitute overfitting, so if you are a new programmer you can increase these targets.

+------------------------------------------------------------------------------------+
| Backtest Count Overfit Reference                                                   |
+==========================+=============================+===========================+
| 0-30: Likely Not Overfit | 30-70: Possibly Overfitting | 70+: Probably Overfitting |
+--------------------------+-----------------------------+---------------------------+

|

|parameters| **Reducing Strategy Parameters**

.. |parameters| image:: https://cdn.quantconnect.com/terminal/i/backtest_tab_icons/parameters_rev1.svg

With just a handful of parameters, it is possible to create an algorithm that perfectly models historical markets. Current research suggests keeping your parameter count to a minimum to decrease the risk of overfitting.

+------------------------------------------------------------------------------------+
| Parameter Overfit Reference                                                        |
+==========================+=============================+===========================+
| 0-10: Likely Not Overfit | 10-20: Possibly Overfitting | 20+: Probably Overfitting |
+--------------------------+-----------------------------+---------------------------+

|

|clock| **Limiting Research Time Invested**

.. |clock| image:: https://cdn.quantconnect.com/terminal/i/backtest_tab_icons/clock_rev1.svg

As you spend more time on one algorithm, `research suggests <https://papers.ssrn.com/sol3/papers.cfm?abstract_id=2308659>`_ you are more likely to be overfitting the strategy to the data. It is common to become attached to an idea and spend weeks or months to perform well in a backtest. Assuming you are a proficient coder who fully understands the QuantConnect API, we recommend no more than 16 hours of work per experiment. In theory, within two full working days, you should be able to test a single hypothesis thoroughly.

+----------------------------------------------------------------------------------------------------+
| Research Time Overfit Reference                                                                    |
+===============================+==================================+=================================+
| 0-8 Hours: Likely Not Overfit | 8-16 Hours: Possibly Overfitting | 16+ Hours: Probably Overfitting |
+-------------------------------+----------------------------------+---------------------------------+

|

Parameter Detection
===================

Using parameters is almost unavoidable, but a strategy trends toward being overfitted as more parameters get added or fine-tuned. Adding or optimizing parameters should only be done by a robust methodology such as `walk-forward optimization <https://en.wikipedia.org/wiki/Walk_forward_optimization>`_.

.. figure:: https://cdn.quantconnect.com/docs/i/parameter-detection_rev0.png

The parameter detection system is a general guide to inform you of how many parameters are present in the algorithm. It looks for the following criteria to warn that code is potentially a parameter:

**Parameters**

.. list-table::
   :header-rows: 1

   * - Parameter Types
     - Example Instances
   * - Numeric Comparison
     - Numeric operators used to compare numeric arguments: <= < > >=
   * - Time Span
     - Setting the interval of TimeSpan or timedelta()
   * - Order Event
     - Inputting numeric arguments when placing orders
   * - Scheduled Events
     - Inputting numeric arguments when scheduling an algorithm event to occur
   * - Variable Assignment
     - Assigning numeric values to variables
   * - Mathematical Operation
     - Any mathematical operation involving explicit numbers
   * - LEAN API
     - Numeric arguments passed to Indicators, Consolidators, Rolling Windows, etc.

**Common expressions that are** *not* **parameters**

.. list-table::
   :header-rows: 1

   * - Common APIs
     - SetStartDate, SetEndDate, SetCash, etc.
   * - Boolean Comparison
     - Testing for True or False conditions
   * - String Numbers
     - Numbers formatted as part of Log or Debug statements
   * - Variable Names
     - Any variable names that use numbers as part of the name -- i.e., smaIndicator200
   * - Common Functions
     - Rounding, array indexing, boolean comparison using 1/0 for True/False, etc.

|

.. _key-concepts-research-guide-overfitting:

What Is Overfitting?
====================

Overfitting happens when the parameters of an algorithm are fine-tuned to fit the detail and noise of backtesting data to the extent that it negatively impacts the performance of the algorithm on new data. The problem is that the parameters do not necessarily apply to new data and thus negatively impact the algorithm's ability to generalize and trade well in all market conditions.

**Overfitting can manifest itself in several ways:**

+----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Data Practice                                                                                                                                                                                                                          |
+========================================================================================+===============================================================================================================================================+
| `Data Dredging <https://en.wikipedia.org/wiki/Data_dredging>`_                         | Performing many statistical tests on data and only paying attention to those that come back with significant results.                         |
+----------------------------------------------------------------------------------------+-----------------------------------------------------------------------------------------------------------------------------------------------+
| `Hyper-Tuning Parameters <https://en.wikipedia.org/wiki/Hyperparameter_optimization>`_ | Manually changing algorithm parameters to produce better results without altering the test data.                                              |
+----------------------------------------------------------------------------------------+-----------------------------------------------------------------------------------------------------------------------------------------------+
| `Overfit Regression Models <https://en.wikipedia.org/wiki/Overfitting#Regression>`_    | Regression, machine learning, or other statistical models with too many variables will likely introduce overfitting to an algorithm.          |
+----------------------------------------------------------------------------------------+-----------------------------------------------------------------------------------------------------------------------------------------------+
| Stale Testing Data                                                                     | Not changing the backtesting data set when testing the algorithm. Any improvements might not be able to be generalized to different datasets. |
+----------------------------------------------------------------------------------------+-----------------------------------------------------------------------------------------------------------------------------------------------+

An algorithm that is dynamic and generalizes to new data is more valuable to funds and individual investors. It is more likely to survive across different market conditions and apply to new asset classes and markets.