<p>To add a universe of Option contracts, in the <code>Initialize</code> method, call the <code>AddOption</code> method. This method returns an <code>Option</code> object, which contains the canonical <code>Symbol</code>. You can't trade with the canonical Option <code>Symbol</code>, but save a reference to it so you can easily access the Option contracts in the <a href='/docs/v2/writing-algorithms/securities/asset-classes/equity-options/handling-data#04-Options-Chain'>OptionChain</a> that LEAN passes to the <code>OnData</code> method.</p>

<div class="section-example-container">
    <pre class="csharp">var option = AddOption("SPY");
_symbol = option.Symbol;</pre>
    <pre class="python">option = self.AddOption("SPY")
self.symbol = option.Symbol</pre>
</div>

<p>The following table describes the <code>AddOption</code> method arguments:</p>
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
            <td>The underlying Equity ticker.</td>
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
            <td>The underlying Equity market.</td>
            <td><code class="python">None</code><code class="csharp">null</code></td>
        </tr>
        <tr>
            <td><code>fillDataForward</code></td>
	    <td><code>bool</code></td>
            <td>If true, the current slice contains the last available data even if there is no data at the current time.</td>
            <td><code class="python">True</code><code class="csharp">true</code></td>
        </tr>
        <tr>
            <td><code>leverage</code></td>
	    <td><code>decimal</code><br></td>
            <td>The leverage for this Equity.</td>
            <td><code>Security.NullLeverage</code></td>
        </tr>
        <tr>
            <td><code>extendedMarketHours</code></td>
	    <td><code>bool</code></td>
            <td>A flag that signals if LEAN should send data during pre- and post-market trading hours.</td>
            <td><code class="python">False</code><code class="csharp">false</code></td>
        </tr>
    </tbody>
</table>


<p>If you add an Option universe for an underlying Equity that you don't have a subscription for, LEAN automatically subscribes to the underlying Equity with a <a href='/docs/v2/writing-algorithms/securities/asset-classes/us-equity/requesting-data#09-Data-Normalization'>data normalization mode</a> of <code>Raw</code>. If you already have a subscription to the underlying Equity but it's not <code>Raw</code>, LEAN automatically changes it to <code>Raw</code>.</p>

<p>By default, LEAN adds all of the available Option contracts to the <a href="/docs/v2/writing-algorithms/key-concepts/time-modeling/timeslices">Slice</a> it passes to the <code>OnData</code> method. To narrow down the universe of Option contracts, call the <code>SetFilter</code> method of the <code>Option</code> object. The following table describes the available filter techniques:</p>


<table class="table qc-table">
    <thead>
        <tr>
            <th>Method<br></th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>SetFilter(int minStrike, int maxStrike)</code></td>
	    <td>Selects the contracts that have a strike price within a minimum and maximum strike level relative to the undelrying price. For example, say the underlying price is $302 and there are strikes at every $5. If you set <code>minStrike</code> to -1 and <code>maxStrike</code> to 1, LEAN selects the contracts that have a strike of $300 or $305. <br></td>
        </tr>
        <tr>
            <td><code>SetFilter(TimeSpan minExpiry, TimeSpan maxExpiry)</code></td>
	    <td>Selects the contracts that expire within the range you set.<br></td>
        </tr>
        <tr>
            <td><code>SetFilter(int minStrike, int maxStrike, TimeSpan minExpiry, TimeSpan maxExpiry)</code></td>
	    <td>Selects the contracts that expire and have a strike within the range you set.</td>
        </tr>
        <tr>
            <td><code>SetFilter(Func&lt;OptionFilterUniverse, OptionFilterUniverse&gt; universeFunc)</code></td>
	    <td>Selects the contracts that a function selects.</td>
        </tr>
    </tbody>
</table>





<div class="section-example-container">
    <pre class="csharp"></pre>
    <pre class="python"># Select contracts that have a strike price within 1 strike level above and below the underlying price
option.SetFilter(minStrike=-1, maxStrike=1)

# Select contracts that expire within 30 days
option.SetFilter(minExpiry=timedelta(days=0), maxExpiry=timedelta(days=30))

# Select contracts that have a strike price within 1 strike level and expire within 30 days
option.SetFilter(minStrike=-1, maxStrike=1, minExpiry=timedelta(days=0), maxExpiry=timedelta(days=30))

# Select call contracts
option.SetFilter(lambda option_filter_universe: option_filter_universe.CallsOnly())
</pre>
</div>



<p>The following table describes the filter methods of the <code>OptionFilterUniverse</code> class:</p>

<?php echo file_get_contents(DOCS_RESOURCES."/universes/option/option-filter-universe.html"); ?>

<p>The preceding methods return an <code>OptionFilterUniverse</code>, so you can chain the methods together.</p>

<div class="section-example-container">
    <pre class="csharp"></pre>
    <pre class="python"># Select the front month call contracts
option.SetFilter(lambda option_filter_universe: option_filter_universe.CallsOnly().FrontMonth())</pre>
</div>

<p>To get all of the contracts in the <code>OptionFilterUniverse</code>, call the <code>GetEnumerator</code> method.</p>
<div class="section-example-container">
    <pre class="csharp"></pre>
    <pre class="python"># In Initialize
option.SetFilter(self.option_filter)
    
def option_filter(self, option_filter_universe):
    puts = option_filter_universe.PutsOnly()
    symbols = []
    for symbol in puts.GetEnumerator():
    	contract = OptionContract(symbol, option_filter_universe.Underlying)
	if contract.Greeks.Delta > 0: 
            symbols.append(contract.Symbol)
    return option_filter_universe.Contracts(contracts)</pre>
</div>


<p>By default, LEAN adds contracts to the <code>OptionChain</code> that pass the filter criteria at every time step in your algorithm. In backtests, if a contract in the chain doesn't pass the filter criteria, LEAN removes it from the chain at the start of the next day. In live trading, LEAN removes these contracts from the chain every 15 minutes.</p>
