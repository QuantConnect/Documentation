<?php
$getCustomChartText = function($backtest) {

    echo "
        <p>The results page shows the custom charts that you create.</p>
        <h4>Supported Chart Types</h4>
        <p>We support the following types of charts:</p>
        <div class='section-example-container'>
        <pre class='all prettyprint prettyprinted'>
<span class='typ'>SeriesType</span><span class='pun'>.</span><span class='typ'>Line</span>
<span class='pln'>          </span><span class='pun'>.</span><span class='typ'>Scatter</span>
<span class='pln'>          </span><span class='pun'>.</span><span class='typ'>Candle</span>
<span class='pln'>          </span><span class='pun'>.</span><span class='typ'>Bar</span>
<span class='pln'>          </span><span class='pun'>.</span><span class='typ'>Flag</span></pre></div>
     ";

    if ($backtest) {
        echo "<p>To create other types of charts, save the plot data in the ObjectStore and then load it into the Research Environment. In the Research Environment, you can <a href='/docs/v2/research-environment/charting/key-concepts'>create other types of charts with third-party charting packages</a>.</p>";
    }

    echo "
        <h4>Supported Markers</h4>
        <p>When you create scatter plots, you can set a marker symbol. We support the following marker symbols:</p>
        <div class='section-example-container'>
        <pre class='all prettyprint prettyprinted'>
<span class='typ'>ScatterMarkerSymbol</span><span class='pun'>.</span><span class='typ'>Circle</span>
<span class='pln'>                   </span><span class='pun'>.</span><span class='typ'>Diamond</span>
<span class='pln'>                   </span><span class='pun'>.</span><span class='typ'>Square</span>
<span class='pln'>                   </span><span class='pun'>.</span><span class='typ'>Triangle</span>
<span class='pln'>                   </span><span class='pun'>.</span><span class='typ'>TriangleDown</span></pre></div>
    ";

    if ($backtest) {
        echo "
            <h4>Chart Quotas</h4>
            <p>Custom charts are limited to 4,000 data points. Intensive charting requires hundreds of megabytes of data, which is too much to stream online or display in a web browser. If you exceed the limit, the Cloud Terminal displays the following message:</p>
            <p><span class='error-messages'>Exceeded maximum points per chart, data skipped</span></p>
        ";
    }
    
    echo "
        <h4>Demonstration</h4>
        <p>For more information about creating custom charts, see <a href='/docs/v2/writing-algorithms/user-guides/charting'>Charting</a>.
    ";
}
?>
