<?php
$getExtMarketHoursText = function($cCode, $pyCode, $supportedIntradayData, $marketHoursLink = null)
{
	echo "
<p>By default, your security subscriptions only cover regular trading hours. To subscribe to pre- and post-market trading hours for a specific asset, enable the <code>extendedMarketHours</code> argument when you create the security subscription.</p>

<div class='section-example-container'>
    <pre class='csharp'>{$cCode}</pre>
    <pre class='python'>{$pyCode}</pre>
</div>
";
	if ($supportedIntradayData)
	{
		echo "
<p>You only receive extended market hours data if you create the subscription with an intraday resolution. If you create the subscription with daily resolution, the daily bars only reflect the regular trading hours.</p>
		";
	}
	if (!is_null($marketHoursLink))
	{
echo "
<p>To view the schedule of regular and extended market hours, see <a href='{$marketHoursLink}'>Market Hours</a>.</p>
	";
	}
}

?>
<!--
 $cCode = "";
 $pyCode = "";
 $supportedIntradayData = true;
 $marketHoursLink = "";

 import(extended-market-hours.php);

 $function(
	"c cdoe ", 
	"py code",
 	..
)

====

function exportData() {
} 
-->

<!-- 
<p>By default, your security subscriptions only cover regular trading hours. To subscribe to pre- and post-market trading hours for a specific asset, enable the <code>extendedMarketHours</code> argument when you create the security subscription.</p>
<div class='section-example-container'>
    <pre class='csharp'>{$cCode}</pre>
    <pre class='python'>{$pyCode}</pre>
</div>
<? if $supportedIntradayData ?>
<p>You only receive extended market hours data if you create the subscription with an intraday resolution. If you create the subscription with daily resolution, the daily bars only reflect the regular trading hours.</p>
<? } ?>
<? if (!is_null($marketHoursLink)) { ?> 
<p>To view the schedule of regular and extended market hours, see <a href='{$marketHoursLink}'>Market Hours</a>.</p>
<? } ?>
--> 
