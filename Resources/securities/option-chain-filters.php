<h4>Filter Chains</h4>

<p>
  <code>OptionChain</code> objects provide the same filter methods as <a href="<?=$optionUniverseUrl?>">Option universe selection</a>, with the same names and the same behavior.
  Each method returns a new <code>OptionChain</code> and leaves the source chain unchanged, so you can chain the calls together.
</p>

<div class='section-example-container'>
    <pre class='csharp'>// Select the call contracts that expire in 20-40 days and are within 3 strikes of the underlying price.
var contracts = chain.CallsOnly().Expiration(20, 40).Strikes(-3, 3);</pre>
    <pre class='python'># Select the call contracts that expire in 20-40 days and are within 3 strikes of the underlying price.
contracts = chain.calls_only().expiration(20, 40).strikes(-3, 3)</pre>
</div>

<p>
  The contract property filters, the Greeks and implied volatility filters, and the <a href="/docs/v2/writing-algorithms/trading-and-orders/option-strategies">Option strategy</a> filters are all available.
  The strategy filters select the legs of the strategy, or return an empty chain when no set of contracts matches the criteria.
</p>

<div class='section-example-container'>
    <pre class='csharp'>// Select the four legs of an Iron Condor that expires in at least 30 days.
var legs = chain.IronCondor(30, 5, 10);</pre>
    <pre class='python'># Select the four legs of an Iron Condor that expires in at least 30 days.
legs = chain.iron_condor(30, 5, 10)</pre>
</div>

<p class='csharp'>To filter the chain with your own predicate, use LINQ's <code>Where</code> method.</p>
<p class='python'>To filter the chain with your own predicate, call the <code>where</code> method.</p>

<div class='section-example-container'>
    <pre class='csharp'>var liquidContracts = chain.Where(contract => contract.OpenInterest > 100);</pre>
    <pre class='python'>liquid_contracts = chain.where(lambda contract: contract.open_interest > 100)</pre>
</div>

<p>
  The <code class='csharp'>StandardsOnly</code><code class='python'>standards_only</code> and <code class='csharp'>WeeklysOnly</code><code class='python'>weeklys_only</code> methods of a chain apply to the contracts the chain already holds, so you can combine them with the expiration filters in any order.
  In universe selection, these two filters always run last.
</p>
