<?php
$getDataNormalizationAdjustmentText = function($isWritingAlgorithms)
{
    $endDateWording = $isWritingAlgorithms ? "backtest end date"  : "<code>QuantBook</code> time";
    echo "<p>We use the entire split and dividend history to adjust historical prices. This process ensures your algorithms receive the same adjusted prices, regardless of the {$endDateWording}.</p>";
}

?>
