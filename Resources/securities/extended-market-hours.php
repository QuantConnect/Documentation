<?php
include(DOCS_RESOURCES."/datasets/extended-market-hours.php");
$isWritingAlgorithms = true;
$getExtendedMarketHoursText($isWritingAlgorithms);
?>

<p>You only receive extended market hours data if you create the subscription with an intraday resolution. If you create the subscription with daily resolution, the daily bars only reflect the regular trading hours.</p>
