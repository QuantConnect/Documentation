<p>You can create the indicator timeseries with the <code>Indicator</code> helper method or you can manually create the timeseries.</p>

<h4>Indicator Helper Method</h4>
<p>To create an indicator timeseries with the helper method, call the <code>Indicator</code> method.</p>
<div class="section-example-container">
    <pre class="csharp">// Create a dataframe with a date index, and columns are indicator values.
var <?=$variableName?>Indicator = qb.Indicator(<?=$variableName?>, symbol, 50, Resolution.Daily);</pre>
    <pre class="python"># Create a dataframe with a date index, and columns are indicator values.
<?=$variableName?>_dataframe = qb.indicator(<?=$variableName?>, symbol, 50, Resolution.DAILY)</pre>
</div>
<?=$pythonImage?>

<h4>Manually Create the Indicator Timeseries</h4>
<p>Follow these steps to manually create the indicator timeseries:</p>

<ol>
    <li>Get some <a href="/docs/v2/research-environment/datasets/key-concepts">historical data</a>.</li>
    <div class="section-example-container">
        <pre class="csharp">// Request historical trading data with the daily resolution.
var history = qb.History(symbol, 70, Resolution.Daily);</pre>
        <pre class="python"># Request historical trading data with the daily resolution.
history = qb.history[TradeBar](symbol, 70, Resolution.DAILY)</pre>
    </div>

    <li>Set the indicator <code class="csharp">Window.Size</code><code class="python">window.size</code> for each attribute of the indicator to hold their values.</li>
    <?=$setWindowSizeExampleContainer?>

    <li>Iterate through the historical market data and update the indicator.</li>
    <div class="section-example-container">
        <pre class="csharp">foreach (var bar in history)
{
    <?=$variableName?>.Update(<?=strcmp($variableName, 'bb') !== 0 ? "bar": "bar.EndTime, bar.Close" ?>);
}</pre>
        <pre class="python">for bar in history:
    <?=$variableName?>.update(<?=strcmp($variableName, 'bb') !== 0 ? "bar" : "bar.end_time, bar.close"?>)</pre>
    </div>
<?=$dataDisplayStep?>
</ol>