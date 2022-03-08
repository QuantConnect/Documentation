<?php
$getCustomChartText = function($backtest) {

    echo "<p>Create custom charts and they will display on the results page of your algorithm. We support the following types of plots:</p>";
    echo "<div class='section-example-container'>";
    echo "<pre class='all prettyprint prettyprinted'>";
    echo     "<span class='typ'>SeriesType</span><span class='pun'>.</span><span class='typ'>Line</span><br>";
    echo     "<span class='pln'>          </span><span class='pun'>.</span><span class='typ'>Scatter</span><br>";
    echo     "<span class='pln'>          </span><span class='pun'>.</span><span class='typ'>Candle</span><br>";
    echo     "<span class='pln'>          </span><span class='pun'>.</span><span class='typ'>Bar</span><br>";
    echo     "<span class='pln'>          </span><span class='pun'>.</span><span class='typ'>Flag</span>";
    echo "</pre></div>";

    if ($backtest) {
        echo "<p>To create other types of charts, save the plot data in the ObjectStore and then load it into the Research Environment. In the Research Environment, you can <a href='/docs/v2/research-environment/charting/key-concepts'>create other types of charts with third-party charting packages</a>.</p>";
    }

    echo "<p>When you create scatter plots, you can set a marker symbol. We support the following marker symbols:</p>";
    echo "<div class='section-example-container'>";
    echo "<pre class='all prettyprint prettyprinted'>";
    echo     "<span class='typ'>ScatterMarkerSymbol</span><span class='pun'>.</span><span class='typ'>Circle</span><br>";
    echo     "<span class='pln'>                   </span><span class='pun'>.</span><span class='typ'>Diamond</span><br>";
    echo     "<span class='pln'>                   </span><span class='pun'>.</span><span class='typ'>Square</span><br>";
    echo     "<span class='pln'>                   </span><span class='pun'>.</span><span class='typ'>Triangle</span><br>";
    echo     "<span class='pln'>                   </span><span class='pun'>.</span><span class='typ'>TriangleDown</span>";
    echo "</pre></div>";

    if ($backtest) {
        echo "<p>Custom charts are limited to 4,000 data points. Intensive charting requires hundreds of megabytes of data, which is too much to stream online or display in a web browser. If you exceed the limit, the Console displays the following message:</p>";
        echo "<p><span class='error-messages'>Exceeded maximum points per chart, data skipped</span></p>";
    }
}
?>
