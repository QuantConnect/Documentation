.. _algorithm-framework-algorithm-scoring:

=======================================
Algorithm Framework - Algorithm Scoring
=======================================

|

Introduction
============

The Algorithm Framework performs real-time analysis into the effectiveness of your strategy and calculates various scores on your algorithm. These scores can be used to help you quickly identify areas of weakness to improve your models. There are three key scores calculated based on the Alpha Model insights: Direction Score, Magnitude Score, and Estimated Alpha Value.

Scoring an algorithm is a balance of art and science. Between the start of your Insight signal and the end of your timeframe, there are infinite variations in the price movements. To address this, we've created scoring functions which assign weight based on time.

.. figure:: https://cdn.quantconnect.com/web/i/docs/20180213-framework-light.png

Algorithm Framework Backtest View

|

Direction Score
===============

The direction score is a measure of the *directional* accuracy of the predictions of your algorithm. When you create an Insight from an Alpha Module, you create a prediction the market will move Up or Down. If your prediction is correct during your insight timeframe, you receive a positive score of [1]. If the asset moves in the wrong direction, you will receive a [0] score.

The scoring system only reviews the moment of the insight completion, giving you a binary score of 1 or 0 depending on if you correctly predicted the direction. The sum of the insights for the backtest is averaged to give your overall direction score as a percentage.

|

Magnitude Score
===============

An insight can optionally set the expected magnitude change of the asset over the insight period. This expected return can be used in the Portfolio Construction model to improve results.

The magnitude score is a measure of the magnitude accuracy of the predictions of your algorithm. If the sign of the actual and the expected magnitude change are different, you will receive a [0] score. If the magnitude change of the asset is equal or greater than the expected, your magnitude is considered correct, and you will receive a positive score of [1]. Otherwise, you will receive a score that is linearly proportional to the actual magnitude change:

.. tabs::

   .. code-tab:: c#

        var actual = currentValue / startingValue - 1;
        var expected = insight.Magnitude.Value;
        var score = Math.Min(actual / expected, 1);

   .. code-tab:: py

        actual = currentValue / startingValue - 1
        expected = insight.Magnitude
        score = min(actual / expected, 1)

The scoring system only reviews the moment of the insight completion. The sum of the insights for the backtest is averaged to give your overall magnitude score as a percentage.

|

Estimated Alpha Value
=====================

To assign an approximate value of the revenue potential for framework algorithms, we calculate the mean insight value. When an insight is created and successfully fulfills its expectations, there is potential for a profit. If an investor had followed the signal blindly and exited on completion of the insight period, the resulting gain or loss is the *Insight Value*. The insight value is calculated as:

*Insight Value = Insight Price Change x Volume Depth Available*

The estimated algorithm hypothetical value is the sum of these insight values calculated on a monthly basis. Over time we hope to improve the Insight Value formulas to give you an estimate of potential Alpha Streams licensing revenues. It is important to note this is entirely hypothetical and the estimations are backwards-looking.

|

Insight Confidence
==================

Insights can optionally be assigned a confidence score. This is an indication of the strength of evidence for a specific insight. Models consuming insight scores can use these confidences to assign more weight to high confidence expectations.

When calculating the confidence of an insight, you should try and apply statistical techniques. How frequently has this pattern or input resulted in a successful prediction? If you normalize your signal into a standard distribution, how often does the signal reach this threshold?