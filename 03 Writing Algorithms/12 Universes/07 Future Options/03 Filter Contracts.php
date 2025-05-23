<p>By default, LEAN subscribes to the Option contracts that have the following characteristics:</p>

<? $annotation = "weeklies and non-standard contracts are not available"; include(DOCS_RESOURCES."/universes/option/default-filter.php"); ?>

<p>To adjust the universe of contracts, set a filter. The filter usually runs at every <a href="/docs/v2/writing-algorithms/key-concepts/time-modeling/timeslices">time step</a> in your algorithm. When the filter selects a contract that isn't currently in your universe, LEAN adds the new contract data to the next <code>Slice</code> that it passes to the <code class="csharp">OnData</code><code class="python">on_data</code> method.</p>

<p>To set a contract filter, in the <code class="csharp">Initialize</code><code class="python">initialize</code> method, pass a filter function to the <code class="csharp">AddFutureOption</code><code class="python">add_future_option</code> method. The following table describes the available filter techniques:</p>

<div class="section-example-container">
    <pre class="csharp">AddFutureOption(future.Symbol, optionFilterUniverse => optionFilterUniverse.Strikes(-1, 1));</pre>
    <pre class="python">self.add_future_option(future.symbol, lambda option_filter_universe: option_filter_universe.strikes(-1, 1))</pre>
</div>

<? include(DOCS_RESOURCES."/universes/option/fop-filter-universe.html"); ?>

<div class="section-example-container">
    <pre class="csharp">AddFutureOption(future.Symbol, optionFilterUniverse => optionFilterUniverse.Strikes(-1, 1).CallsOnly());</pre>
    <pre class="python">self.add_future_option(future.symbol, lambda option_filter_universe: option_filter_universe.strikes(-1, 1).calls_only())</pre>
</div>


<p>To perform thorough filtering on the <code>OptionFilterUniverse</code>, define an isolated filter method.</p>

<div class="section-example-container">
    <pre class="csharp"># In Initialize
AddFutureOption(future.Symbol, Selector);
        
private OptionFilterUniverse Selector(OptionFilterUniverse optionFilterUniverse)
{
    var symbols = optionFilterUniverse.PutsOnly();
    var strike = symbols.Select(symbol => symbol.ID.StrikePrice).Min();
    symbols = symbols.Where(symbol => symbol.ID.StrikePrice == strike);
    return optionFilterUniverse.Contracts(symbols);
}</pre>
    <pre class="python"># In Initialize
self.add_future_option(future.Symbol, self._contract_selector)

def _contract_selector(self, option_filter_universe: OptionFilterUniverse) -> OptionFilterUniverse:
    symbols = option_filter_universe.PutsOnly()
    strike = min([symbol.id.strike_price for symbol in symbols])
    symbols = [symbol for symbol in symbols if symbol.id.strike_price == strike]
    return option_filter_universe.contracts(symbols)</pre>
</div>

<?
$assetClass = "Option";
include(DOCS_RESOURCES."/universes/option/filter-caveats.php");
?>
