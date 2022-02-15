<?php
$getUniverseSelectionText = function($dataFeedName, $availability=true) {
    if ($availability)
    {
        echo "<p>Universe selection is compatible with the $dataFeedName data feed.</p>
        
<div class='section-example-container'>
    <pre class='csharp'>AddUniverse(CoarseUniverseSelection, FineUniverseSelection);</pre>
    <pre class='python'>self.AddUniverse(self.CoarseUniverseSelection, self.FineUniverseSelection)</pre>
</div>";
    }
    else
    {
        echo "<p>Universe selection isn't available with the $dataFeedName data feed.";
    }
}
?>