<?php if ($includeIntro) { ?><p>The following table describes the fill price of combo market orders for each data format and order direction:</p><?php } ?>

<table class="qc-table table">
    <thead>
        <tr>
            <th>Data Format</th>
            <th><code>TickType</code></th>
            <th>Order Direction</th>
            <th>Fill Price</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>Tick</code></td>
            <td><code>Quote</code></td>
            <td>Buy</td>
            <td>Ask quote price + slippage</td>
        </tr>
        <tr>
            <td><code>Tick</code></td>
            <td><code>Quote</code></td>
            <td>Sell</td>
            <td>Bid quote price - slippage</td>
        </tr>
        <tr>
            <td><code>Tick</code></td>
            <td><code>Trade</code></td>
            <td>Buy</td>
            <td>Trade price + slippage</td>
        </tr>
        <tr>
            <td><code>Tick</code></td>
            <td><code>Trade</code></td>
            <td>Sell</td>
            <td>Trade price - slippage</td>
        </tr>
        <tr>
            <td><code>QuoteBar</code></td>
            <td><br></td>
            <td>Buy</td>
            <td>Ask close price + slippage</td>
        </tr>
        <tr>
            <td><code>QuoteBar</code></td>
            <td><br></td>
            <td>Sell</td>
            <td>Bid close price - slippage</td>
        </tr>
        <tr>
            <td><code>TradeBar</code></td>
            <td><br></td>
            <td>Buy</td>
            <td>Close price + slippage</td>
        </tr>
        <tr>
            <td><code>TradeBar</code></td>
            <td><br></td>
            <td>Sell</td>
            <td>Close price - slippage</td>
        </tr>
    </tbody>
</table>

<p>The model only fills combo market orders if all the following conditions are met:</p>

<ul>
    <li>The exchange is open</li>
    <li>The data isn't <a href='/docs/v2/writing-algorithms/reality-modeling/trade-fills/key-concepts#06-Stale-Fills'>stale</a></li>
    <li>All the legs can fill in the same <a href='/docs/v2/writing-algorithms/key-concepts/time-modeling/timeslices'>time step</a> after the order time step</li>
</ul>

<p>The fill quantity of each leg is the product of the leg order quantity and the combo market order quantity.</p>