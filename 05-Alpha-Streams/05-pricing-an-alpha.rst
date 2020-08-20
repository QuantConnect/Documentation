.. _alpha-streams-pricing-an-alpha:

================
Pricing an Alpha
================

|

Introduction
============
In this section, we explore various factors that contribute to the value of your Alpha. QuantConnect does not directly set the price of your alpha, instead preferring that the marketplace finds an efficient price for the Alpha.

However, normal marketplace dynamics apply, and for the best possible market, it is essential both parties have accurate information to find an efficient price.

Marketplace Dynamics
====================
The Alpha Streams marketplace efficiency is, in part, determined by the infrastructure in place to find the efficient price. Although we have plans for a more advanced marketplace, currently it is a principally manual process. An Alpha price can be changed manually by the Authors once per month. If there are funds actively licensing the strategy, they will have the choice to continue or stop their license.

Licenses are per month, but billing is prorated daily with a minimum of one day of licensing revenue. This barrier is intended to be low enough that funds can easily harness your strategy.

Pricing Factors
===============
Although pricing is driven by the marketplace, many factors go into a buyer's decision-making process that can help focus your design. Some of these factors include:

**Uniqueness**

Is your alpha highly similar to other Alphas in the market? Does it utilize identical assets to Alphas already listed? Listing a near-identical Alpha will likely drive the price of both Alpha models down.

**Beta**

Does your Alpha correlate highly with the broader market benchmark? Alphas with high S&P correlations are less valuable, as they can be replaced with the benchmark.

**Forward Trading Track Record**

Does your Alpha have any live trading track record or perform differently relative to its backtests? Alphas will become more valuable when they gain an out-of-sample track record.

**Insight Accuracy**

Insight prediction accuracy is the most basic predictor of the Alpha value. If your Alpha can reliably predict the expected return of the market, it will be easily monetizable.

**Supply and Demand**

Are there multiple hedge funds in the market focused on your Alpha investment style? Alphas with more competing bidders will likely be able to command a higher fee.

**Asset Volume**

High volume assets can support greater trading capital meaning the resulting Insights are more valuable. Alphas operating on liquid asset classes will be able to attract greater licensing revenues.