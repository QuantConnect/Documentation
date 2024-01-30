<p>By default, LEAN doesn't add any contracts to the <a href="/docs/v2/writing-algorithms/securities/asset-classes/futures/handling-data#05-Futures-Chains">FuturesChain</a> it passes to the <code>OnData</code> method. To add a universe of Future contracts, in the <code>Initialize</code> method, call the <code>SetFilter</code> method of the <code>Future</code> object. The following table describes the available filter techniques:</p>

<table class="table qc-table">
    <thead>
        <tr>
            <th>Method<br></th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code class="csharp">SetFilter(int minExpiryDays, int maxExpiryDays)</code><code class="python">SetFilter(minExpiryDays: int, maxExpiryDays: int)</code></td>
	        <td>Selects the contracts that expire within the range you set. This filter runs <a href='/docs/v2/writing-algorithms/universes/settings#09-Asynchronous-Selection'>asynchronously</a> by default.</td>
        </tr>
        <tr>
            <td><code class="csharp">SetFilter(Func&lt;FutureFilterUniverse, FutureFilterUniverse&gt; universeFunc)</code><code class="python">SetFilter(universeFunc: Callable[[FutureFilterUniverse], FutureFilterUniverse])</code></td>
	        <td>Selects the contracts that a function selects.</td>
        </tr>
    </tbody>
</table>


<div class="section-example-container">
    <pre class="python"># Select the contracts which expire within 182 days
self.future.SetFilter(0, 182)

# Select the front month contract
self.future.SetFilter(lambda future_filter_universe: future_filter_universe.FrontMonth())</pre>
    <pre class="csharp">// Select the contracts which expire within 182 days
_future.SetFilter(0, 182);

// Select the front month contract
_future.SetFilter(futureFilterUniverse =&gt; futureFilterUniverse.FrontMonth());
</pre>
</div>

<p>The following table describes the filter methods of the <code>FutureFilterUniverse</code> class:</p>

<?php echo file_get_contents(DOCS_RESOURCES."/universes/future/future-filter-universe.html");?>

<p>The preceding methods return an <code>FutureFilterUniverse</code>, so you can chain the methods together.</p>

<div class="section-example-container">
    <pre class="csharp">// Select the front month standard contracts
_future.SetFilter(futureFilterUniverse =&gt; futureFilterUniverse.StandardsOnly().FrontMonth());</pre>
    <pre class="python"># Select the front month standard contracts
self.future.SetFilter(lambda future_filter_universe: future_filter_universe.StandardsOnly().FrontMonth())</pre>
</div>


<p>You can also define an isolated filter method.</p>
<div class="section-example-container">
    <pre class="csharp">// In Initialize
_future.SetFilter(Selector);
    
private FutureFilterUniverse Selector(FutureFilterUniverse futureFilterUniverse)
{
    return futureFilterUniverse.StandardsOnly().FrontMonth();
}</pre>
    <pre class="python"># In Initialize
self.future.SetFilter(self.contract_selector)
    
def contract_selector(self, 
    future_filter_universe: Callable[[FutureFilterUniverse], FutureFilterUniverse]) -&gt; FutureFilterUniverse:
    return future_filter_universe.StandardsOnly().FrontMonth()</pre>
</div>

<?
$assetClass = "Future";
include(DOCS_RESOURCES."/universes/option/filter-caveats.php");
?>

<p>By default, LEAN adds contracts to the <code>FutureChain</code> that pass the filter criteria at every time step in your algorithm. If a contract has been in the universe for a duration that matches the <a href='/docs/v2/writing-algorithms/universes/settings#06-Minimum-Time-in-Universe'>MinimumTimeInUniverse setting</a> and it no longer passes the filter criteria, LEAN removes it from the chain</p>
