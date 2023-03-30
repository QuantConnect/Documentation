<p>If the <?=$modelType?> model manages some indicators or <a href='/docs/v2/writing-algorithms/consolidating-data/getting-started'>consolidators</a> for securities in the universe and the universe selection runs during the indicator sampling period or the consolidator aggregation period, the indicators and consolidators might be missing some data. For example, take the following scenario:</p>

<ul>
    <li>The security resolution is minute</li>
    <li>You have a consolidator that aggregates the security data into daily bars to update the indicator</li>
    <li>The universe selection runs at noon</li>
</ul>

</p>In this scenario, you create and <a href='/docs/v2/writing-algorithms/indicators/manual-indicators#06-Warm-Up-Indicators'>warm-up the indicator</a> at noon. Since it runs at noon, the history request that gathers daily data to warm up the indicator won't contain any data from the current day and the consolidator that updates the indicator also won't aggregate any data from before noon. This process doesn't cause issues if the indicator only uses the close price to calculate the indicator value (like the <a href='/docs/v2/writing-algorithms/indicators/supported-indicators/simple-moving-average'>simple moving average</a> indicator) because the first consolidated bar that updates the indicator will have the correct close price. However, if the indicator uses more than just the close price to calculate its value (like the <a href='/docs/v2/writing-algorithms/indicators/supported-indicators/true-range'>True Range</a> indicator), the open, high, and low values of the first consolidated bar may be incorrect, causing the initial indicator values to be incorrect.</p>
