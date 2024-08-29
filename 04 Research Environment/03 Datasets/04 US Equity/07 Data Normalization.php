<p>The data normalization mode defines how historical data is adjusted for <a href='/docs/v2/writing-algorithms/securities/asset-classes/us-equity/corporate-actions'>corporate actions</a>. By default, LEAN adjusts US Equity data for splits and dividends to produce a smooth price curve, but the following data normalization modes are available:</p>
    
<div data-tree='QuantConnect.DataNormalizationMode' data-fields='Raw,RAW,Adjusted,ADJUSTED,SplitAdjusted,SPLIT_ADJUSTED,TotalReturn,TOTAL_RETURN,ScaledRaw,SCALED_RAW'></div>

<? include(DOCS_RESOURCES."/datasets/data-normalization.php"); ?>

<p>To set the data normalization mode for a security, pass a <code class="csharp">dataNormalizationMode</code><code class="python">data_normalization_mode</code> argument to the <code class="csharp">AddEquity</code><code class="python">add_equity</code> method.</p>    

<div class='section-example-container'>
    <pre class='csharp'>var spy = qbAddEquity("SPY", dataNormalizationMode: DataNormalizationMode.Raw).Symbol;</pre>
    <pre class='python'>spy = qb.add_equity("SPY", data_normalization_mode=DataNormalizationMode.RAW).symbol</pre>
</div>

<p>When you request historical data, the <code class="csharp">History</code><code class="python">history</code> method uses the data normalization of your security subscription. To get historical data with a different data normalization, pass a <code class="csharp">dataNormalizationMode</code><code class="python">data_normalization_mode</code> argument to the <code class="csharp">History</code><code class="python">history</code> method.</p>
<div class="section-example-container">
<pre class="csharp">var history = qb.History(qb.Securities.Keys, qb.Time-TimeSpan.FromDays(10), qb.Time, dataNormalizationMode: DataNormalizationMode.SplitAdjusted);</pre>
<pre class="python">history = qb.history(qb.securities.keys(), qb.time-timedelta(days=10), qb.time, dataNormalizationMode=DataNormalizationMode.SPLIT_ADJUSTED)</pre>
</div>
