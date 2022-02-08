<?php
$getDataFeedIntroText = function($dataFeedName, $streamOfText) {
    echo "<p>The $dataFeedName data feed is a stream of $streamOfText delivered to your trading algorithm during live execution. You need live data feeds to inject data into your algorithm so that you can make real-time trading decisions and so that the values of the securities in your portfolio are updated. For instance, if you're trading $dataFeedName, your position values will usually be read from your brokerage account when you deploy the algorithm, but your position values can only be updated as the algorithm progresses if you have the $dataFeedName data feed.</p>";
}
?>