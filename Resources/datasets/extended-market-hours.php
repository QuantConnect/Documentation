<?php
$getExtendedMarketHoursText = function($isWritingAlgorithms) 
{
    $pyVariableName = $isWritingAlgorithms ? "self" : "qb";
    $cVariableName = $isWritingAlgorithms ? "" : "qb.";
    
    echo "
<p>By default, your security subscriptions only cover regular trading hours. To subscribe to pre- and post-market trading hours for a specific asset, enable the <code>extendedMarketHours</code> argument when you create the security subscription.</p>

<div class='section-example-container'>
    <pre class='csharp'>{$cVariableName}AddEquity(\"SPY\", extendedMarketHours: true);</pre>
    <pre class='python'>{$pyVariableName}.AddEquity(\"SPY\", extendedMarketHours=True)</pre>
</div>
";
}

?>
