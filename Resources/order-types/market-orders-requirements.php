<p><?=$orderType ?> must be submitted during market hours for all security types.</p>
<p>If your algorithm place <?=strtolower($orderType) ?> at or after the last minute of regular market hours, they will be converted into <a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/market-on-open-orders'>market-on-open orders</a> and will have to observe their <a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/market-on-open-orders#07-Requirements'></a> for the following asset types:
<ol>
    <li>Equities</li>
    <li>Equity Options</li>
    <li>Forex</li>
    <li>CFDs</li>
    <li>Index Options</li>
</ol>
<p><?=$orderType ?> for Futures and Future Options can be submitted during extended market hours, or they will be invalid.</p>
