.. _live-trading-universe-selection:

==================
Universe Selection
==================

|

Universe Selection in Live Trading
==================================

Universe selection occurs roughly 10-12 hours after the market stops trading for the day at 04:00 - 07:00 EST Tuesday, Wednesday, Thursday, Friday, and Saturday. Once the market completely closes (21:00 EST), we process all the ticks into trade bars and universe data for the live environment.

Universe selection does not happen when you deploy your algorithm. You must deploy before 4 a.m. or wait for the next trading day for universe selection to take place. This is identical behaviour to backtesting; however, as time is compressed in backtesting, you may not realize that the universe selection happens in the early morning hours.

We are aware immediate universe selection would be a great feature to support and are considering the best way to implement this in LEAN and QuantConnect.

Universe selection is only supported by the *QuantConnect Data Feed*. If you select a brokerage data feed on deploying your algorithm, it will not receive universe data at this time.