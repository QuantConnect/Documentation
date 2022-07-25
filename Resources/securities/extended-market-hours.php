<?php
include(DOCS_RESOURCES."/datasets/extended-market-hours.php");
$isWritingAlgorithms = true;
$getExtendedMarketHoursText($isWritingAlgorithms);
?>

<p>To subscribe to pre- and post-market trading hours for all assets, enable the <code>ExtendedMarketHours</code> <a href='/docs/v2/writing-algorithms/universes/key-concepts#05-Universe-Settings'>universe setting</a> before you create the security subscriptions.</p>

<div class="section-example-container">
    <pre class="csharp">UniverseSettings.ExtendedMarketHours = true;</pre>
    <pre class="python">self.UniverseSettings.ExtendedMarketHours = True</pre>
</div>

<p>You only receive extended market hours data if you create the subscription with an intraday resolution. If you create the subscription with daily resolution, the daily bars only reflect the regular trading hours.</p>
