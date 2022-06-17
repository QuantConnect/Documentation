<p>When a company stops trading on an exchange, it's delisted. <code>Delisting</code> objects have the following properties:</p>
<div data-tree="QuantConnect.Data.Market.Delisting"></div>


<p>You receive <code>Delisting</code> objects when a delisting is in the near future and when it occurs. To know if the delisting occurs in the near future or now, check the <code>Type</code> property.</p>
<?php echo file_get_contents(DOCS_RESOURCES."/enumerations/delisting_type.html"); ?>

<p>To get the <code>Delisting</code> objects in the <code>Slice</code>, index the <code>Delistings</code> property of the <code>Slice</code> with the security <code>Symbol</code>. The <code>Slice</code> may not contain data for your <code>Symbol</code>. To avoid issues, check if the <code>Delistings</code> property contains data for your security before you index it with the security <code>Symbol</code>.</p>

<div class="section-example-container">
        <pre class="csharp">if (data.Delistings.ContainsKey(_symbol))
{
    var delisting = data.Delistings[_symbol];
}</pre>
        <pre class="python">if data.ContainsKey(self.symbol):
    delisting = data.Delistings[self.symbol]</pre>
</div>

<p>The delist warning occurs on the final trading day of the stock to give you time to gracefully exit out of positions before LEAN automatically liquidates them. To set the ticket for the order that liquidates the position, call the <code>SetOrderTicket</code> method on the <code>Delisting</code> object.</p>

<div class="section-example-container">
        <pre class="csharp">// Example ^^</pre>
        <pre class="python"># Example ^^</pre>
</div>

<p>For a full example, see the <a rel='nofollow' target='_blank' href='https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/DelistingEventsAlgorithm.cs' class='csharp'>DelistingEventsAlgorithm</a><a rel='nofollow' target='_blank' href='https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/DelistingEventsAlgorithm.py' class='python'>DelistingEventsAlgorithm</a> in the LEAN GitHub repository.</p>
