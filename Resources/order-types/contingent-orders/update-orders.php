<p>You can update any open order in the set<? if ($hasParent) { ?>, including a child order that waits for its parent to fill<? } ?>. To update an order, pass an <code>UpdateOrderFields</code> object to the <code class="csharp">Update</code><code class="python">update</code> method on the <code>OrderTicket</code>, or call one of its <a href='/docs/v2/writing-algorithms/trading-and-orders/order-management/order-tickets#04-Update-Orders'>update methods</a>.<? if ($hasParent) { ?> An update doesn't release a held order. If you give a held limit order a marketable price, the order fills only after its parent fills.<? } ?></p>

<div class="section-example-container">
<pre class="csharp"><?=$csharpUpdate?></pre>
<pre class="python"><?=$pythonUpdate?></pre>
</div>

<p>Some brokerages don't support updates to contingent orders or to their quantity. To change these orders, cancel the set and place it again.</p>
