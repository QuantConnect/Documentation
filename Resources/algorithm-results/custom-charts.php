<?php
$getCustomChartText = function($backtest) {

    echo "
        <p>The results page shows the custom charts that you create.</p>
        <h4>Supported Chart Types</h4>
        <p>We support the following types of charts:</p>
        <div data-tree='QuantConnect.SeriesType'></div>
     ";

    if ($backtest) {
        echo "<p>To create other types of charts, save the plot data in the ObjectStore and then load it into the Research Environment. In the Research Environment, you can <a href='/docs/v2/research-environment/charting'>create other types of charts with third-party charting packages</a>.</p>";
    }

    echo "
        <h4>Supported Markers</h4>
        <p>When you create scatter plots, you can set a marker symbol. We support the following marker symbols:</p>
        <div data-tree='QuantConnect.ScatterMarkerSymbol'></div>
    ";

    echo "<h4>Chart Quotas</h4>";
    if ($backtest) {
        echo "
            <p>Custom charts are limited to 4,000 data points. Intensive charting requires hundreds of megabytes of data, which is too much to stream online or display in a web browser. If you exceed the quota, the Cloud Terminal displays the following message:</p>
            <p><span class='error-messages'>Exceeded maximum points per chart, data skipped</span></p>
        ";
    }
    echo "
            <p>You can create up to 10 custom chart series per algorithm. If you exceed the quota, your algorithm stops executing and the Cloud Terminal displays the following message:</p>
            <p><span class='error-messages'>Exceeded maximum series count: Each backtest can have up to 10 series in total.</span></p>
        ";
    
    echo "
        <h4>Demonstration</h4>
        <p>For more information about creating custom charts, see <a href='/docs/v2/writing-algorithms/charting'>Charting</a>.
    ";
}
?>
