<p>To cancel an order in the set, call the <code class="csharp">Cancel</code><code class="python">cancel</code> method on the <code>OrderTicket</code>. LEAN also cancels the orders that depend on it.<? if ($hasParent) { ?> If you cancel a parent order, LEAN cancels the orders it triggers and the orders they trigger in turn.<? } ?><? if ($hasSiblings) { ?> If you cancel a member of an OCO or OUO set, LEAN cancels the rest of the set.<? if ($hasParent) { ?> This rule applies to working orders and to orders that wait for their parent to fill. The parent of the set keeps working.<? } ?><? } ?></p>

<div class="section-example-container">
<pre class="csharp"><?=$csharpCancel?></pre>
<pre class="python"><?=$pythonCancel?></pre>
</div>

<p>The message of the cancel <a href='/docs/v2/writing-algorithms/trading-and-orders/order-events'>order event</a> of each dependent order names the order that caused the cancellation.</p>
