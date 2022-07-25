<?php
$getKeyHistoryConceptsText = function($isWritingAlgorithms)
{
    $universeText = $isWritingAlgorithms ? "the entire universe" : "all of the assets you created subscriptions for in the notebook" ;
    
    echo "
    
<p>The historical data API has many different options to give you the greatest flexibility in how to apply it to your algorithm.</p>


<h4>Request Options</h4>
<p>You can request historical data based on a trailing number of bars, a trailing period of time, or a defined period of time.</p>

<h4>Return Formats</h4>
<p>Each asset class supports slightly different data formats. When you make a history request, consider what data returns. Depending on how you request the data, history requests return a specific data type. For example, if you don't provide <code>Symbol</code> objects, you get <code>Slice</code> objects that contain {$universeText}.</p>


<h4>Time Index</h4>

<p>The data that a history request returns is indexed by the <code>EndTime</code> of each data point. For daily US Equity data, this results in data points appearing on Saturday and skipping Monday.</p>
    
    
    
";
}

?>
