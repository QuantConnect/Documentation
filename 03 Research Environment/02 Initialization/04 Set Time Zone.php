<p>The notebook time zone determines which time zone the <code class='csharp'>DateTime</code><code class='python'>datetime</code> objects are in when you make a <a href='/docs/v2/research-environment/datasets/key-concepts'>history request</a> based on a defined period of time. <span class='python'>When your history request returns a <code>DataFrame</code>, the timestamps in the <code>DataFrame</code> are based in the data time zone.</span> When your history request returns a <code>TradeBars</code>, <code>QuoteBars<code>, <code>Ticks</code>, or <code>Slice</code> object, the <code>EndTime</code> property of these objects are based on the notebook time zone, but the <code>EndTime</code> of the individual <code>TradeBar</code>, <code>QuoteBar</code>, and <code>Tick</code> objects are timestamped based on the <a href='/docs/v2/writing-algorithms/key-concepts/time-modeling/time-zones#05-Data-Time-Zone'>data time zone</a>.</p>

<?php 
include(DOCS_RESOURCES."/initialization/set-time-zone.php"); 
$getSetTimeZoneText(false);
?>

