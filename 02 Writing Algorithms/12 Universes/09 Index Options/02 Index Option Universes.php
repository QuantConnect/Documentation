<p>To add a universe of Index Option contracts, in the <code>Initialize</code> method, call the <code>AddIndexOption</code> method. This method returns an <code>Option</code> object, which contains the canonical <code>Symbol</code>. You can't trade with the canonical Option <code>Symbol</code>, but save a reference to it so you can easily access the Option contracts in the <a href='/docs/v2/writing-algorithms/securities/asset-classes/index-options/handling-data#03-Option-Chains'>OptionChain</a> that LEAN passes to the <code>OnData</code> method.</p>


<div class="section-example-container">
    <pre class="csharp">var option = AddIndexOption("VIX");
_symbol = option.Symbol;</pre>
    <pre class="python">option = self.AddIndexOption("VIX")
self.symbol = option.Symbol</pre>
</div>

<p>The following table describes the <code>AddIndexOption</code> method arguments:</p>
<table class="qc-table table">
    <thead>
        <tr>
            <th>Argument</th>
            <th>Data Type</th>
            <th>Description</th>
            <th>Default Value</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>ticker</code></td>
	    <td><code>string</code></td>
            <td>The underlying Index ticker. To view the available indices, see <a href='/docs/v2/writing-algorithms/securities/asset-classes/index/requesting-data#03-Supported-Indices'>Supported Indices</a>.</td>
            <td></td>
        </tr>
        <tr>
            <td><code>resolution</code></td>
	    <td><code>Resolution?</code></td>
            <td>The resolution of the market data.</td>
            <td><code class="python">None</code><code class="csharp">null</code></td>
        </tr>
        <tr>
            <td><code>market</code></td>
	    <td><code>string</code></td>
            <td>The Index Option market.</td>
            <td><code>Market.USA</code></td>
        </tr>
        <tr>
            <td><code>fillDataForward</code></td>
	    <td><code>bool</code></td>
            <td>If true, the current slice contains the last available data even if there is no data at the current time.</td>
            <td><code class="python">True</code><code class="csharp">true</code></td>
        </tr>
    </tbody>
</table>


<p>If you add an Option universe for an underlying Index that you don't have a subscription for, LEAN automatically subscribes to the underlying Index.</p>

<?php echo file_get_contents(DOCS_RESOURCES."/universes/option/set-filter.html"); ?>


<p>The following table describes the filter methods of the <code>OptionFilterUniverse</code> class:</p>

<?php 
echo file_get_contents(DOCS_RESOURCES."/universes/option/option-filter-universe.html"); 
echo file_get_contents(DOCS_RESOURCES."/universes/option/filter-examples.html"); 
?>


<p>By default, LEAN adds contracts to the <code>OptionChain</code> that pass the filter criteria at every time step in your algorithm. In backtests, if a contract in the chain doesn't pass the filter criteria, LEAN removes it from the chain at the start of the next day. In live trading, LEAN removes these contracts from the chain every 15 minutes.</p>
