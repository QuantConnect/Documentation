.. _alpha-streams-common-alpha-features:

=====================
Common Alpha Features
=====================

|

Introduction
============

There are several common metrics, themes, and features that funds look for when licensing an Alpha. We've outlined below some of the most common factors that go into selecting an Alpha.

|

Backtests
=========

Backtesting is not the only or even most important part of the Alpha development process. However, it is essential to display how a strategy performs in a variety of market conditions and not just in a market selected to boost overall performance during that period.

**Length**

Having a backtest that displays excellent performance over the past 9 months doesn't mean that the strategy isn't valuable, but it displays considerably less information than one that covers the last 5 years. High-value Alphas perform consistently over long periods, and longer backtests contain more information about the algorithm performance across time. Backtests should consist of 5 or more years worth of data before being submitted.

**Market Conditions**

Algorithm performance across a variety of market conditions is significant. It is easier to develop algorithms that perform well in bull-markets than it is in a down-market. Alphas that can display robust performance across bull-markets, bear-markets, volatile markets, and illiquid markets have much more appeal than an algorithm that can only succeed under specific market conditions.

Market conditions aren't the same across all asset classes. An Alpha that trades Forex needs to consider market conditions specific to their securities, which might not overlap with the same kind of equities market. The same holds for derivatives like options, futures, and CFDs -- different securities experience times of strong growth, volatility, and decline at different times, even if they are somewhat correlated or inter-dependent. Make sure that the backtests reflect the different market conditions specific to the securities the algorithm is trading.

**Benchmark**

Investors want to know whether or not a strategy can outperform the broader market to which it is generally exposed. For equities, investors often set the benchmark as the S&P 500, Russell 2000, or another equity index. For example, an algorithm that trades commodity ETFs should pick a benchmark of a popular commodity ETF while a fixed income algorithm should select a fixed income ETFs. This allows investors to compare performance and determine whether the risks, borrowing costs, and commission are worth it to achieve the algorithm results rather than just buying and holding the benchmark.

|

Performance Metrics
===================

**Sharpe Ratio**

The Sharpe ratio is likely the most common performance measurement as it conveys much information very quickly. Despite its limitations, a number expressing risk-adjusted performance is a valuable tool that investors use to screen algorithms. Sharpe ratios need to be positive, but a good minimum to aim for is an annualized Sharpe ratio of 1.

**Maximum Drawdown**

Drawdown is the total decline from a strategy's maximum value to its lowest value over a specified period. It is a good measure of risk and performance and is something that is closely scrutinized by investors. Algorithms with consistent returns and low drawdown are more likely to provide value to investors than a strategy with higher overall returns and a significant drawdown, which demonstrates higher strategy risk and volatility.

**Time Underwater**

Time underwater is the amount of time that a strategy is in a drawdown. That is, the amount of time a strategy spends below its peak value before recovering. A short time underwater demonstrates resilience and adaptability, reassuring investors that any time spent underwater is likely to be short-lived and that losses will be recovered.

**Long-Short Ratio**

Due to borrowing costs, funds often want to be dollar-neutral in their strategies. A strategy that achieves this helps offset the cost of trading on margin and reduces exposure risk. Not all strategies need to be market-neutral, but it is a feature that is common among high-value Alphas.

|

Market Correlation
==================

One question every Alpha author must be able to answer is why people should invest in their strategy. Active trading involves commission costs, borrowing costs, and other risks. The signal that the algorithm finds in the data needs to be unique and provide sufficient Alpha to warrant the extra risk. If the strategy produces an equity curve that is too correlated with a relevant benchmark, then it is likely cheaper and safer for an investor to just buy the benchmark. Be sure to compare the strategy against common benchmarks to see how much additional value it produces beyond the broader market returns.

**Equities**

* S&P 500
* Russell 2000
* Russell 3000

**Fixed Income**

* Short-term US Treasury Bond ETFs
* Medium-term US Treasury Bond ETFs
* Long-term US Treasury Bond ETFs
* Investment-grade Corporate Bond ETFs
* Municipal Bond ETFs

**Forex**

* Libor Rates

**Options**

* Underlying security

**Futures**

* S&P 500
* VIX ETFs
* Commodity ETFs

|

Informative Description
=======================

Investors want to understand the basic ideas behind the signals they are receiving. Backtests and performance metrics are significant and can convey a lot of information about a strategy, but the best way to sell your Alpha is to give an informative, comprehensive summary involving the basic premise, risk-management methods, order management, universe selection, etc. A thorough description gives funds more information and demonstrates that you are a well-informed developer, which can help establish trust between yourself and the funds looking to invest.