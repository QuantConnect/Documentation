<p>In backtests, LEAN simulates how the brokerage manages the set with the following rules:</p>

<ul>
<? if ($hasParent) { ?>
    <li>When a parent order fills, LEAN triggers the child orders at the fill time. Triggered market orders fill right away. Other triggered orders require new data before they can fill, so they don't fill with prices from before the trigger.</li>
<? } ?>
<? if ($hasSiblings) { ?>
    <li>If an OCO or OUO order and its siblings can all fill with the same data, LEAN fills the stop orders first. For a bracket order, the stop loss fills and LEAN cancels the take profit.</li>
<? } ?>
<? if ($hasParent) { ?>
    <li>A triggered <a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/trailing-stop-orders'>trailing stop order</a> sets its initial stop price from the market price at the trigger time.</li>
    <li>LEAN measures the <a href='/docs/v2/writing-algorithms/trading-and-orders/order-properties#03-Time-In-Force'>day time in force</a> of a child order from the time its parent fills.</li>
<? } ?>
</ul>

<p>For more information about how LEAN models order fills in backtests, see <a href="/docs/v2/writing-algorithms/reality-modeling/trade-fills/key-concepts">Trade Fills</a>.</p>
