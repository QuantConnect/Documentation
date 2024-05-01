<?
$cCode = "AddEquity(\"SPY\", extendedMarketHours: true);";
$pyCode = "self.add_equity(\"SPY\", extended_market_hours=True)";
$supportedIntradayData = true;
$marketHoursLink = null;
include(DOCS_RESOURCES."/securities/extended-market-hours.php"); 
?>
<p>When you request historical data, the <code class="csharp">History</code><code class="python">history</code> method uses the extended market hours setting of your security subscription. To get historical data with a different extended market hours setting, pass an <code class="csharp">extendedMarketHours</code><code class="python">extended_market_hours</code> argument to the <code class="csharp">History</code><code class="python">history</code> method.</p>
<div class="section-example-container">
    <pre class="csharp">var history = qb.History(qb.Securities.Keys, qb.Time-TimeSpan.FromDays(10), qb.Time, extendedMarketHours: false);</pre>
    <pre class="python">history = qb.history(qb.securities.keys, qb.time-timedelta(days=10), qb.time, extended_market_hours=False)</pre>
</div>
