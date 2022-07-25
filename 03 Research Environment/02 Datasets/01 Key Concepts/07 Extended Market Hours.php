
<?php
include(DOCS_RESOURCES."/datasets/extended-market-hours.php");
$isWritingAlgorithms = false;
$getExtendedMarketHoursText($isWritingAlgorithms);
?>

<p>When you request historical data, the <code>History</code> method uses the extended market hours setting of your security subscription. To get historical data with a different fill forward setting, pass an <code>extendedMarket</code> argument to the <code>History</code> method.</p>

<div class="section-example-container">
    <pre class="csharp">var history = qb.History(spy, 10, extendedMarket: false);</pre>
    <pre class="python">history = qb.History(spy, 10, extendedMarket=False)</pre>
</div>
