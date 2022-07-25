<?php
$getDataNormalizationAdjustmentText = function($isWritingAlgorithms)
{
    $endDateWording = $isWritingAlgorithms ? "backtest"  : "<code>QuantBook</code>";
    echo "<p>We use the entire split and dividend history to adjust historical prices. This process ensures your algorithms receive the same adjusted prices, regardless of the {$endDateWording} end date.</p>";
}

?>
