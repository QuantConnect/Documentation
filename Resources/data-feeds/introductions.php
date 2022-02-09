<?php
$getDataFeedIntroText = function($dataFeedName, $streamOfText) {
    echo "<p>The $dataFeedName data feed is a stream of $streamOfText delivered to your trading algorithm during live execution. Live data feeds enable you to make real-time trades and update the value of the securities in your portfolio. If you trade $dataFeedName, your position values will usually be read from your brokerage account upon deployment, but your position values can only be updated as the algorithm progresses if you have the $dataFeedName data feed.</p>";
}
?>
