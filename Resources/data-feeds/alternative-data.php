<?php
$getAltDataText = function($brokerageName, $hybridSupported) {
    echo "<p>Brokerage data feeds support most alternative data feeds, except feeds that stream real-time intraday data. Streaming data feeds, like the <a href='/datasets/tiingo-news-feed'>Tiingo News Feed</a> and <a href='/datasets/benzinga-news-feed'>Benzinga News Feed</a>, require the QuantConnect data feed. ";
    if ($hybridSupported) {
        echo "The hybrid QuantConnect-$brokerageName data feed supports streaming data feeds."; 
    }
    echo "</p>";
}
?>
