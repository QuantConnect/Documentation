<p>When you trade a large portfolio of assets, you may want to send orders in batches and not wait for the response of each one. To send <a href='/docs/v2/writing-algorithms/trading-and-orders/key-concepts#09-Asynchronous-Execution'>asynchronous orders</a>, set the <code>asynchronous</code> argument to <code class="csharp">true</code><code class="python">True</code>.</p>

<div class="section-example-container">
<pre class="csharp"><?=$csharpOrder?>;</pre>
<pre class="python"><?=$pythonOrder?>;</pre>
</div>

<p>The <code>asynchronous</code> parameter comes before the <code>tag</code> parameter, so pass the tag as a named argument, such as <code class="csharp">tag: "Entry"</code><code class="python">tag="Entry"</code>. If you pass the tag by position, it fills the <code>asynchronous</code> parameter and the method call fails.</p>