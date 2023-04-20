<?
$cCode = "AddEquity(\"SPY\", extendedMarketHours: true);";
$pyCode = "self.AddEquity(\"SPY\", extendedMarketHours=True)";
$supportedIntradayData = true;
include(DOCS_RESOURCES."/securities/extended-market-hours.php"); 
?>
<p>When you request historical data, the <code>History</code> method uses the extended market hours setting of your security subscription. To get historical data with a different extended market hours setting, pass an <code>extendedMarketHours</code> argument to the <code>History</code> method.</p>
<div class="section-example-container">
    <pre class="csharp">var history = qb.History(qb.Securities.Keys, qb.Time-TimeSpan.FromDays(10), qb.Time, extendedMarketHours: false);</pre>
    <pre class="python">history = qb.History(qb.Securities.Keys, qb.Time-timedelta(days=10), qb.Time, extendedMarketHours=False)</pre>
</div>
