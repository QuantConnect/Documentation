<p>By default, LEAN subscribes to the Option contracts that have the following characteristics:</p>

<? $annotation = "exclude weeklys"; include(DOCS_RESOURCES."/universes/option/default-filter.php"); ?>

<p>To adjust the universe of contracts, set a filter. The filter usually runs at the first bar of every day. When the filter selects a contract that isn't currently in your universe, LEAN adds the new contract data to the next <code>Slice</code> that it passes to the <code>OnData</code> method.</p>

<p>To set a contract filter, in the <code>Initialize</code> method, call the <code>SetFilter</code> method of the <code>Option</code> object. The following table describes the available filter techniques:</p>

<table class="table qc-table">
    <thead>
        <tr>
            <th>Method<br></th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code class="csharp">SetFilter(int minStrike, int maxStrike)</code><code class="python">SetFilter(minStrike: int, maxStrike: int)</code></td>
	        <td>Selects the contracts that have a strike price within a minimum and maximum strike level relative to the underlying price. For example, say the underlying price is $302 and there are strikes at every $5. If you set <code>minStrike</code> to -1 and <code>maxStrike</code> to 1, LEAN selects the contracts that have a strike of $300 or $305. This filter runs <a href='/docs/v2/writing-algorithms/universes/settings#09-Asynchronous-Selection'>asynchronously</a> by default.</td>
        </tr>
        <tr>
            <td><code class="csharp">SetFilter(TimeSpan minExpiry, TimeSpan maxExpiry)</code><code class="python">SetFilter(minExpiry: timedelta, maxExpiry: timedelta)</code></td>
	        <td>Selects the contracts that expire within the range you set. This filter runs <a href='/docs/v2/writing-algorithms/universes/settings#09-Asynchronous-Selection'>asynchronously</a> by default.</td>
        </tr>
        <tr>
            <td><code class="csharp">SetFilter(int minStrike, int maxStrike, TimeSpan minExpiry, TimeSpan maxExpiry)</code><code class="python">SetFilter(minStrike: int, maxStrike: int, minExpiry: timedelta, maxExpiry: timedelta)</code></td>
	        <td>Selects the contracts that expire and have a strike within the range you set. This filter runs <a href='/docs/v2/writing-algorithms/universes/settings#09-Asynchronous-Selection'>asynchronously</a> by default.</td>
        </tr>
        <tr>
            <td><code class="csharp">SetFilter(Func&lt;OptionFilterUniverse, OptionFilterUniverse&gt; universeFunc)</code><code class="python">SetFilter(universeFunc: Callable[[OptionFilterUniverse], OptionFilterUniverse])</code></td>
	        <td>Selects the contracts that a function selects.</td>
        </tr>
    </tbody>
</table>

<div class="section-example-container">
    <pre class="csharp">// Select contracts that have a strike price within 1 strike level above and below the underlying price
option.SetFilter(minStrike: -1, maxStrike: 1);

// Select contracts that expire within 30 days
option.SetFilter(minExpiry: TimeSpan.FromDays(0), maxExpiry: TimeSpan.FromDays(30));

// Select contracts that have a strike price within 1 strike level and expire within 30 days
option.SetFilter(minStrike: -1, maxStrike: 1, minExpiry: TimeSpan.FromDays(0), maxExpiry: TimeSpan.FromDays(30));

// Select call contracts
option.SetFilter(optionFilterUniverse => optionFilterUniverse.CallsOnly());</pre>
    <pre class="python"># Select contracts that have a strike price within 1 strike level above and below the underlying price
option.set_filter(min_strike=-1, max_strike=1)

# Select contracts that expire within 30 days
option.set_filter(min_expiry=timedelta(days=0), maxExpiry=timedelta(days=30))

# Select contracts that have a strike price within 1 strike level and expire within 30 days
option.set_filter(min_strike=-1, max_strike=1, min_expiry=timedelta(days=0), maxExpiry=timedelta(days=30))

# Select call contracts
option.set_filter(lambda option_filter_universe: option_filter_universe.calls_only())
</pre>
</div>
