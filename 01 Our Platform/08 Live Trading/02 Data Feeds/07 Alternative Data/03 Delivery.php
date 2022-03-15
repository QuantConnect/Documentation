<p>The delivery schedule of alternative data feeds depends on the specific data feed you're using. We inject the data into your algorithms when the vendor provides the data. For most alternative data feeds, the data is updated on a daily or hourly basis. Some data feeds, like the <a href='/datasets/tiingo-news-feed'>Tiingo News Feed</a> or <a href='/datasets/benzinga-news-feed'>Benzinga News Feed</a>, include a live stream. In these cases, we deliver the data as a live stream to your algorithm.</p>

<?php 
include(DOCS_RESOURCES."/data-feeds/data-delay.php"); 
$getDataFeedDataDelayText();
?>

<p>Live data takes time to travel from the source to your algorithm. The latency of the alternative data feeds depends on the specific data feed you're using.</p>