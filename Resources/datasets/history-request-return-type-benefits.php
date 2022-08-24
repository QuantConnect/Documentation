<?php
$getReturnTypeBenefitText = function($supportsTrades, $supportsQuotes)
{
    echo "<p class='python'>History requests can return different types of data. The most popular return type is a <code>DataFrame</code>. If you request a <code>DataFrame</code>, LEAN unpacks the data from <code>Slice</code> objects to populate the <code>DataFrame</code>. If you intend to use the data in the <code>DataFrame</code> to create ";
    
    if ($supportsTrades && !$supportsQuotes)
    {
        echo "<code>TradeBar</code> ";
    }
    elseif (!$supportsTrades && $supportsQuotes)
    {
        echo "<code>QuoteBar</code> ";
    }
    else 
    {
        echo "<code>TradeBar</code> or <code>QuoteBar</code> ";
    }
    echo "objects, request that the history request returns the data type you need. Otherwise, LEAN will waste computational resources populating the <code>DataFrame</code>.</p>";
}
?>
