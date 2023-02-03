<?php if ($includeIntro) { ?><p>The following table describes the fill logic of combo leg limit orders for each data format and order direction. The order direction in the table represents the order direction of the order leg, not the order direction of the combo order.</p><?php } ?>

<table class="qc-table table">
    <thead>
        <tr>
            <th>Data Format</th>
            <th><code>TickType</code></th>
            <th>Order Direction</th>
            <th>Fill Condition</th>
            <th>Fill Price</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>Tick</code></td>
            <td><code>Quote</code></td>
            <td>Buy</td>
            <td>Ask price < limit price</td>
            <td>min(ask price, limit price)</td>
        </tr>
        <tr>
            <td><code>Tick</code></td>
            <td><code>Quote</code></td>
            <td>Sell</td>
            <td>Bid price > limit price</td>
            <td>max(bid price, limit price)</td>
        </tr>
        <tr>
            <td><code>Tick</code></td>
            <td><code>Trade</code></td>
            <td>Buy</td>
            <td>Trade price < limit price</td>
            <td>min(trade price, limit price)</td>
        </tr>
        <tr>
            <td><code>Tick</code></td>
            <td><code>Trade</code></td>
            <td>Sell</td>
            <td>Trade price > limit price</td>
            <td>max(trade price, limit price)</td>
        </tr>
        <tr>
            <td><code>QuoteBar</code></td>
            <td><br></td>
            <td>Buy</td>
            <td>Ask low price < limit price</td>
            <td>min(ask high price, limit price)</td>
        </tr>
        <tr>
            <td><code>QuoteBar</code></td>
            <td><br></td>
            <td>Sell</td>
            <td>Bid high price > limit price</td>
            <td>max(bid low price, limit price)</td>
        </tr>
        <tr>
            <td><code>TradeBar</code></td>
            <td><br></td>
            <td>Buy</td>
            <td>Low price < limit price</td>
            <td>min(high price, limit price)</td>
        </tr>
        <tr>
            <td><code>TradeBar</code></td>
            <td><br></td>
            <td>Sell</td>
            <td>High price > limit price</td>
            <td>max(low price, limit price)</td>
        </tr>
    </tbody>
</table>


<p>The model only fills combo leg limit orders if all the following conditions are met:</p>

<ul>
    <li>The exchange is open</li>
    <li>The data isn't <a href='/docs/v2/writing-algorithms/reality-modeling/trade-fills/key-concepts#06-Stale-Fills'>stale</a></li>
    <li>All the legs can fill in the same <a href='/docs/v2/writing-algorithms/key-concepts/time-modeling/timeslices'>time step</a> after the order time step</li>
</ul>

<p>The fill quantity is the product of the leg order quantity and the combo order quantity.</p>