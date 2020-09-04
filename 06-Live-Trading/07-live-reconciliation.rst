.. _live-trading-live-reconciliation:

===================
Live Reconciliation
===================

|

Introduction
============
It's possible to backtest and live trade a strategy over the same period and end up with different results. There can 
be several reasons for this, including: 

|

Non-Deterministic State From Algorithm Restarts
===============================================
If a live algorithm is stopped and restarted during execution, it needs to restart in a stateful way to avoid 
performance discrepancies. To restart in a stateful way, utilize the 
:ref:`SetWarmUp <algorithm-reference-initializing-algorithms-setting-warm-up-period>` and 
:ref:`History <algorithm-reference-historical-data-history-request-for-known-symbols>` methods. Additionally, consider 
using the :ref:`ObjectStore <algorithm-reference-machine-learning-storing-trained-models>` to save state information 
between live trading deployments.

|

Universe Selection Timing
=========================
When backtesting, :ref:`universe selection <algorithm-framework-universe-selection>` is performed at midnight by 
default; During live trading, universe selection is done about 6 hours later. This delay can cause the universe to be 
composed of different securities when switching between the two trading modes. Deploying before the market close should 
ensure you pick up the universe selection before the market opens.

|

Reality Modeling Error
======================
We provide models for predicting fees, slippage, and fills. These model predictions may not always match the fees 
that a live algorithm actually faces though. Users can edit their slippage models to better reflect their specific 
assets. Refer to our :ref:`documentation <algorithm-reference-reality-modelling>` for assistance.

|

Market Impact
=============
LEAN does not currently model market impact. Thus, fill prices can be better during a backtest than in live trading. 
Users can follow the instructions in our :ref:`documentation <algorithm-reference-reality-modeling-fill-models>` to 
implement a custom fill model which incorporates market impact.

|

Stale Fills
===========
Backtest orders that are submitted on stale data may have simulated fill prices that are different from the real 
market price. This commonly happens when creating a :ref:`scheduled event <algorithm-reference-scheduled-events>`
with an incompatible data resolution. To avoid this, we recommend using the highest resolution data as much as 
possible such as minute data, even if you only need hourly bars. This will give you an up to date price feed.

|

Different Backtest Parameters
=============================
If the live deployment and backtest aren't started on the same date, cash balance, and holdings, trades and 
performance may differ. To resolve this, ensure the backtest parameters are the same as the live deployment.

|

Data Adjustments
================
Sometimes data providers fix a bug that surfaced in their live data feed. Differences in performance can occur if 
the data is corrected after the live algorithm views the original data but before the backtest algorithm views it. 
For reference, all of our reported data issues are displayed `here <https://www.quantconnect.com/data/issues/open>`_. 
To fix this, closely review your order fill prices to see if there were trade prices which seem unrealistic. This can 
be a signal of a live feed data error. It is rare to see an error in the live price; but it is common to miss a split 
or corporate event in live trading for smaller companies. There are unfortunately no professional data feeds for 
corporate events.

|

Brokerage Limitations
=====================
LEAN supports the use of many different :ref:`order types <algorithm-reference-trading-and-orders-placing-orders>`. 
With backtesting, these orders are simulated. In live trading, the broker an algorithm uses to fulfill orders may not 
support the order type, leading to the trades not being placed. Futhermore, if the buying power on the algorithm's 
brokerage account isn't sufficient, orders will not be placed. To avoid issues, 
:ref:`set a brokerage model <algorithm-reference-initializing-algorithms-cash-and-brokerage-models>`. 

|

Portfolio Allocations on Small Accounts
=======================================
Its hard to achieve accurate portfolio allocations on smaller capital sizes where the price per share is larger than
the allocation percentage. This can be addressed by using fractional shares, but fractional share trading is only 
supported by some brokerages. To get the closest results when backtesting and live-trading over the same period, 
ensure both algorithms have the same starting cash balance. 

|

Discrete Time Steps
===================
In a backtest, the :ref:`Time Frontier<key-concepts-understanding-time>`_ moves in precise steps every time. Live data time 
moves as a stream not a set of discrete steps. Therefore, if an algorithm is checking for a specific time without using 
a tolerance value (i.e. time is 9:30:15am) , the logic may not work the same when switching to live trading. As a 
remedy, we suggest our users use a time range (time &gt; 9.30.10 and time &lt; 9.30.20) or use a 
:ref:`scheduled event <algorithm-reference-scheduled-events>`.

|

Look-ahead Bias
===============
Although we go to great lengths to eliminate look-ahead bias with the 
:ref:`Time Frontier <key-concepts-understanding-time>`, it can still creep into a backtest. For instance, if an 
algorithm integrates a :ref:`custom data source <algorithm-reference-importing-custom-data>` that is implemented to 
have look-ahead bias, the backtest may have different results than live trading. To ensure an algorithm eliminates 
this bias, always set a period on your custom data types. The time the data is emitted will be the Time + Period.

|

Custom Data Emission Times
==========================
Custom data is often timestamped at midnight but in reality may not be updated for several days. Ensure the custom 
data source you are using updates each day by 2-3am ET or your algorithm will not have it available for live trading.

|

Split Adjustment of Indicators
==============================
If an algorithm is indicator-heavy and a split occurs, the algorithm will have to reset and refresh the indicators 
using historical data. We can monitor for split events in the 
:ref:`slice.Splits[] collection <algorithm-reference-handling-data-time-slices>`.

|

Borrowing Costs
===============
We do not currently simulate the cost of borrowing shorts in our backtests. Therefore, the fees of a live algorithm 
that places short orders may be larger than a backtest of the strategy shows. To simulate these fees, users can 
implement a :ref:`custom fee model <algorithm-reference-reality-modeling-fee-models>`.