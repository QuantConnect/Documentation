<?php
$getUniverseSelectionText = function($dataFeedName, $availability=true) {
    echo "<p><a href='/docs/v2/writing-algorithms/universes/getting-started'>Universe selection</a> is";
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
