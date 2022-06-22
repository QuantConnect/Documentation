<p>To add a universe of Future Option contracts, in the <code>Initialize</code> method, <a href="/docs/v2/writing-algorithms/universes/futures#11-Future-Universes">define a Future universe</a> and then pass the canonical <code>Symbol</code> to the <code>AddFutureOption</code> method.<br></p>

<div class="section-example-container">
    <pre class="csharp">var future = AddFuture(Futures.Currencies.BTC);
future.SetFilter(0, 90);
AddFutureOption(future.Symbol);</pre>
    <pre class="python">future = self.AddFuture(Futures.Currencies.BTC)
future.SetFilter(0, 90)
self.AddFutureOption(future.Symbol)</pre>
</div>


<p>The following table describes the <code>AddFutureOption</code> method arguments:</p>

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
            <td><code>symbol</code></td>
	    <td><code>Symbol</code></td>
            <td>The Future canonical symbol</td>
            <td></td>
        </tr>
        <tr>
            <td><code>optionFilter</code></td>
	    <td><code>Func&lt;OptionFilterUniverse, OptionFilterUniverse&gt;</code></td>
            <td>A function that selects Future Option contracts</td>
            <td><code class='csharp'>null</code><code class='python'>None</code></td>
        </tr>
    </tbody>
</table>


<p>By default, LEAN adds all of the available Future Option contracts to the <a href="/docs/v2/writing-algorithms/key-concepts/time-modeling/timeslices">Slice</a> it passes to the <code>OnData</code> method. To narrow down the universe of contracts, pass a filter function to the <code>AddFutureOption</code> method.</p>



<div class="section-example-container">
    <pre class="csharp">AddFutureOption(future.Symbol, optionFilterUniverse => optionFilterUniverse.Strikes(-1, 1));</pre>
    <pre class="python">self.AddFutureOption(future.Symbol, lambda option_filter_universe: option_filter_universe.Strikes(-1, 1))</pre>
</div>


<p>The following table describes the filter methods of the <code>OptionFilterUniverse</code> class:</p>

<?php echo file_get_contents(DOCS_RESOURCES."/universes/option/option-filter-universe.html"); ?>

<p>The preceding methods return an <code>OptionFilterUniverse</code>, so you can chain the methods together.</p>

<div class="section-example-container">
    <pre class="csharp">AddFutureOption(future.Symbol, optionFilterUniverse => optionFilterUniverse.Strikes(-1, 1).CallsOnly());</pre>
    <pre class="python">self.AddFutureOption(future.Symbol, lambda option_filter_universe: option_filter_universe.Strikes(-1, 1).CallsOnly())</pre>
</div>


<p>To get all of the contracts in the <code>OptionFilterUniverse</code>, call the <code>GetEnumerator</code> method.</p>
<div class="section-example-container">
    <pre class="csharp"># In Initialize
AddFutureOption(future.Symbol, Selector);
        
private FutureFilterUniverse Selector(FutureFilterUniverse futureFilterUniverse)
{
    List&lt;Symbol&gt; symbols = new();
    var standards = futureFilterUniverse.StandardsOnly();

    foreach (var symbol in standards)
    {
        var contract = new OptionContract(symbol, futureFilterUniverse.Underlying.Symbol);
        if (contract.OpenInterest &gt; 0)
        { 
            symbols.Add(contract.Symbol);
        }
    }

    return futureFilterUniverse.Contracts(symbols);
}</pre>
    <pre class="python"># In Initialize
self.AddFutureOption(future.Symbol, self.contract_selector)
    
def contract_selector(self, option_filter_universe: Callable[OptionFilterUniverse, OptionFilterUniverse]) -&gt; OptionFilterUniverse:
    puts = option_filter_universe.PutsOnly()
    symbols = []
    for _, symbol in enumerate(puts.GetEnumerator()):
        contract = OptionContract(symbol, option_filter_universe.Underlying.Symbol)
        if contract.Greeks.Delta > 0: 
            symbols.append(contract.Symbol)
    return option_filter_universe.Contracts(symbols)</pre>
</div>



<p>By default, LEAN adds contracts to the <code>OptionChain</code> that pass the filter criteria at every time step in your algorithm. In backtests, if a contract in the chain doesn't pass the filter criteria, LEAN removes it from the chain at the start of the next day. In live trading, LEAN removes these contracts from the chain every 15 minutes.</p>
