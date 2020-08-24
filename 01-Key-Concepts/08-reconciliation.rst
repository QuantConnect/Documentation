.. _key-concepts-reconciliation:

==============
Reconciliation
==============

|

.. figure:: https://cdn.quantconnect.com/i/tu/reconciliation-4.png

|

Introduction
============

The total reconciliation project is a way to understand, `display <https://www.quantconnect.com/forum/discussion/7454/live-reconciliation-overlayed-out-of-sample-backtests/p1>`_, and `quantify <https://www.quantconnect.com/forum/discussion/7606/a-new-reconciliation-metric/p1>`_ the difference between an algorithm's live performance and its out-of-sample (OOS) performance (a backtest run over the live deployment period).

Seeing and understanding the differences between live performance and OOS performance is essential in algorithm development. It gives traders a way to determine if their initial backtests were making unrealistic assumptions, exploiting data differences, or merely exhibiting behavior that is impractical or impossible in live trading.

A perfectly reconciled algorithm has an exact overlap between its live equity and OOS backtest curves. Any deviation means that, somehow, the performance of the algorithm has differed between the two samples. Several factors can contribute to this, and our goal is to provide our users with the best tools possible to reconcile their algorithm performance. Still, it often stems from algorithm design itself.

|

Scoring Reconciliation
======================

Reconciliation is scored using two metrics: returns correlation and dynamic time warping (DTW) distance.

**What is DTW Distance?**

Dynamic Time Warp (DTW) Distance quantifies the difference between two time-series. It is an algorithm that measures the shortest path between the points of two time-series. It uses Euclidean distance as a measurement of point-to-point distance and returns an overall measurement of the distance on the scale of the initial time-series values. We apply DTW to the returns curve of the live and OOS performance, so the DTW distance measurement is on the scale of percent returns.

For the reasons outlined in our research notebook on the topic (linked below), QuantConnect annualizes the daily DTW. An annualized distance provides a user with a measurement of the annual difference in the magnitude of returns between the two curves. A perfect score is 0, meaning the returns for each day were precisely the same. A DTW score of 0 is nearly impossible to achieve, and we consider anything below 0.2 to be a decent score. A distance of 0.2 means the returns between an algorithm's live and OOS performance deviated by 20% over a year.

**What is Returns Correlation?**

Returns correlation is the simple Pearson correlation between the live and OOS returns. Correlation gives us a rudimentary understanding of how the returns move together. Do they trend up and down at the same time? Do they deviate in direction or timing?

An algorithm's returns correlation should be as close to 1 as possible. We consider a good score to be 0.8 or above, meaning that there is a strong positive correlation. This indicates that the returns move together most of the time and that for any given return you see from one of the curves, the other curve usually has a similar direction return (positive or negative).

**Why Do We Need Both DTW and Returns Correlation?**

Each measurement provides insight into distinct elements of time-series similarity, but neither measurement alone gives us the whole picture. Returns correlation tells us whether or not the live and OOS returns move together, but it doesn't account for the possible differences in the magnitude of the returns. DTW distance measures the difference in magnitude of returns but provides no insight into whether or not the returns move in the same direction. It is possible for there to be two cases of equity curve similarity where both pairs have the same DTW distance, but one has perfectly negatively correlated returns, and the other has a perfectly positive correlation. Similarly, it is possible for two pairs of equity curves to each have perfect correlation but substantially different DTW distance. Having both measurements provides us with a more comprehensive understanding of the actual similarity between live and OOS performance. We outline several interesting cases and go into more depth on the topic of reconciliation in `research <https://www.quantconnect.com/forum/discussion/7606/a-new-reconciliation-metric/p1>`_ we have published.

|

Common Differences
==================

|

**Data Resolution Incompatibility**

Using the correct data resolution is essential. Specifically, algorithms that have events scheduled intra-day, using the Scheduled Events API or by other means, need to use minute resolution data. Using the wrong data resolution can lead to stale prices and order fills, corrupting the signals and the results they produce.

**Universe Selection**

In backtesting, universe selection happens at midnight (00:00 EST). In live trading, it occurs when market data is processed, which can occur anytime between 05:00 to 08:00 EST. This can be problematic if events are scheduled to happen before or during the time window when universe selection happens in live trading.

**External Data Sources**

Each day, a backtest is run to generate the out-of-sample curve. If an algorithm relies on an external data source, exceptional care must be used when coding. External data can be changed, stop being available, or received at a different time in live trading than in backtesting. The out-of-sample backtest might not be able to fetch the same data at the same time or frequency that it is received in live trading. This can corrupt signals, lead to stale data and order placement, or otherwise adversely affect performance in some other manner, leading to differences in out-of-sample and live trading curves.

**Existing Portfolio Securities**

When an algorithm is deployed to a live brokerage account, it will accommodate any current positions that are open, even those unrelated to the algorithm itself. In backtesting, an empty portfolio is assumed. These pre-existing live positions aren't currently reflected in our reconciliation technology, and if they contribute to the live performance of your total portfolio, differences will inevitably exist between the out-of-sample and live curves.

**Using the Time Property**

In backtesting, time is a discrete process. Time intervals are rounded and time-steps are done in precise intervals. When trading live, however, time is a constant stream. Data streams in real-time and the algorithm events might happen milliseconds before or after the same events in backtesting. You cannot write statements like "if time is 11.00", as time will never perfectly equal 11am. You would need to say "if time > 11.00 and < 11.01".

**Order Fill Differences**

In live trading, Market Orders are executed at whatever price the brokerage fills the order at. Backtesting, however, does not know what price live orders were filled at and instead models order fills based on the best price available according to backtest data.

Similarly, Limit Order fills can differ between out-of-sample backtesting and live trading. In backtesting, Limit Orders are filled as soon as the limit price is hit. Brokerages, however, might fill the same Limit Order at a different price, or fail to fill it, depending on where the order is in the order book.