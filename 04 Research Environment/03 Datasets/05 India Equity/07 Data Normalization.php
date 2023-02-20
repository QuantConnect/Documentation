<?
$cSharpVariableName = "icicibank";
$pyVariableName = "icicibank";
$ticker = "ICICIBANK";
include(DOCS_RESOURCES."/securities/data-normalization.php");
?>

<p>When you request historical data, the <code>History</code> method uses the data normalization of your security subscription. To get historical data with a different data normalization, pass a <code>dataNormalizationMode</code> argument to the <code>History</code> method.</p>
<div class="section-example-container">
<pre class="csharp">var history = qb.History(qb.Securities.Keys, qb.Time-TimeSpan.FromDays(10), qb.Time, dataNormalizationMode: DataNormalizationMode.SplitAdjusted);</pre>
<pre class="python">history = qb.History(qb.Securities.Keys, qb.Time-timedelta(days=10), qb.Time, dataNormalizationMode=DataNormalizationMode.SplitAdjusted)</pre>
</div>
