<p>By default, your security subscriptions only cover regular trading hours. To subscribe to pre and post-market trading hours for a specific asset, enable the <code>extendedMarketHours</code> argument when you create the security subscription.</p>
<div class='section-example-container'>
    <pre class='csharp'><?=$cCode?></pre>
    <pre class='python'><?=$pyCode?></pre>
</div>
<? if ($supportedIntradayData) { ?><p>You only receive extended market hours data if you create the subscription with minute, second, or tick resolution. If you create the subscription with daily or hourly resolution, the bars only reflect the regular trading hours.</p><? } ?>
<? if (!is_null($marketHoursLink)) { ?><p>To view the schedule of regular and extended market hours, see <a href=<?=$marketHoursLink?>>Market Hours</a>.</p><? } ?>
 
