.. _algorithm-reference-common-errors:

===================================
Algorithm Reference - Common Errors
===================================

|

Using Data
==========

**Runtime Error: 'AAPL' wasn't found in the TradeBars object**

You're attempting to access data that is not in the TradeBars dictionary. There are three key reasons for this: You have not requested data for this security in the Initialize method, or the data has gaps because it is not filling forward, or you're accessing the data after-hours when there won't be any price information.

**Backtest Error: Error Initializing Algorithm: Date Invalid**

You may have accidentally put invalid start and end dates into your algorithm. Or potentially you pressed backtest before the algorithm was fully saved.    Please Ctrl+S to save the document, wait for its confirmation, and then click Backtest again.
