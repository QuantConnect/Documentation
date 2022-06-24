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


<p>To get all of the contracts in the <code>OptionFilterUniverse</code>, follow the below example.</p>
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
    for symbol in puts:
        contract = OptionContract(symbol, option_filter_universe.Underlying.Symbol)
        if contract.Greeks.Delta > 0: 
            symbols.append(contract.Symbol)
    return option_filter_universe.Contracts(symbols)</pre>
</div>



<p>By default, LEAN adds contracts to the <code>OptionChain</code> that pass the filter criteria at every time step in your algorithm. In backtests, if a contract in the chain doesn't pass the filter criteria, LEAN removes it from the chain at the start of the next day. In live trading, LEAN removes these contracts from the chain every 15 minutes.</p>
