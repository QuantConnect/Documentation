<p>Asset plots display the trade prices of an asset and the following <a href='/docs/v2/writing-algorithms/trading-and-orders/order-events'>order events</a> you have for the asset:</p>


<table class="qc-table table">
    <thead>
        <tr>
            <th>Order Event</th>
            <th>Icon</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Submissions</td>
            <td>Gray circle</td>
        </tr>
        <tr>
            <td>Updates</td>
            <td>Blue circle</td>
        </tr>
        <tr>
            <td>Fills and partial fills</td>
            <td>Green (buys) or red (sells) arrows</td>
        </tr>
    </tbody>
</table>

<p>The following image shows an example asset plot for AAPL:</p>

<img class='docs-image' src='https://cdn.quantconnect.com/i/tu/asset-plot-aapl.png' alt='AAPL stock price with order events overlaid'>

<? if ($writingAlgorithms) { ?>
<p>For more information about these charts, including how to view them in QC Cloud, see <span class='page-section-name'>Asset Plots</span> for <a href='/docs/v2/cloud-platform/backtesting/results#05-Asset-Plots'>backtests</a> or <a href='/docs/v2/cloud-platform/live-trading/results#05-Asset-Plots'>live trading</a>.</p>

<? } elseif ($cloudPlatform) { ?>
<h4>View Plots</h4>

<p>Follow these steps to open an asset plot:</p>

<ol>
    <li>Open the <?= str_contains(DOCS_URL(), "live-trading")? "live" : "backtest" ?> results page.</li>
    <li>Click the <span class='tab-name'>Orders</span> tab.</li>
    <li>Click the <img class='inline-icon' src='https://cdn.quantconnect.com/i/tu/stock-plot-icon.png'> <span class='icon-name'>Asset Plot</span> icon that's next to the asset Symbol in the Orders table.</li>
</ol>

<h4>Tool Tips</h4>

<p>When you hover over one of the order events in the table, the asset plot highlights the order event, displays the asset price at the time of the event, and displays the <a href='/docs/v2/writing-algorithms/trading-and-orders/order-properties#17-Tags'>tag</a> associated with the event. Consider adding helpful tags to each order event to help with debugging your algorithm. For example, when you cancel an order, you can add a tag that explains the reason for cancelling it.</p>


<h4>Adjust the Display Period</h4>

<p>The resolution of the asset price time series in the plot doesn't necessarily match the resolution you set when you subscribed to the asset in your algorithm. If you are displaying the entire price series, the series usually displays the daily closing price. However, when you zoom in, the chart will adjust its display period and may use higher resolution data. To zoom in and out, perform either of the following actions:</p>

<ul>
    <li>Click the <span class='button-name'>1m</span>, <span class='button-name'>3m</span>, <span class='button-name'>1y</span>, or <span class='button-name'>All</span> period in the top-right corner of the chart.</li>
    <li>Click a point on the chart and drag your mouse horizontally to highlight a specific period of time in the chart.</li>
</ul>   

<img src='https://cdn.quantconnect.com/i/tu/asset-plot-zoom-demo.gif' alt='gif that shows the price of AAPL while zooming in and out'>

<p>If you have multiple order events in a single day and you zoom out on the chart so that it displays the daily closing prices, the plot aggregates the order event icons together as the price on that day.</p>

<h4>Order Fill Prices</h4>

<p>The plot displays fill order events at the actual fill price of your orders. The fill price is usually not equal to the asset price that displays because of the following reasons:</p>
<ul>
    <li>Your order experiences <a href='/docs/v2/writing-algorithms/reality-modeling/slippage/key-concepts'>slippage</a>.</li>
    <li>If you use quote data, your order fills at the bid or ask price.</li>
    <li>The <a href='/docs/v2/writing-algorithms/reality-modeling/trade-fills/key-concepts'>fill model</a> may fill your order at the high or low price.</li>
</ul>

<? } ?>