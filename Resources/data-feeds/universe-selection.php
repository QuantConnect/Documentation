<?php
$getUniverseSelectionText = function($dataFeedName, $availability=true) {
    echo "<p>Universe selection is";
    if (!$availability) {
        echo "n't";
    }
    echo " available with the $dataFeedName data feed.</p>";
    if ($availability)
    {
        echo "<div class='section-example-container'>
                  <pre class='csharp'>AddUniverse(CoarseUniverseSelection, FineUniverseSelection);</pre>
                  <pre class='python'>self.AddUniverse(self.CoarseUniverseSelection, self.FineUniverseSelection)</pre>
              </div>";
    }
}
?>
