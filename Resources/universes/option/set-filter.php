<p>To set a contract filter, in the <code class="csharp">Initialize</code><code class="python">initialize</code> method, call the <code class="csharp">SetFilter</code><code class="python">set_filter</code> method of the <code>Option</code> object.</p>

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

<p>The following table describes the available filter techniques:</p>

<table class="qc-table table vertical-table">
    <tbody>
            <tr>
                <td>
                    <code class="csharp">SetFilter(int minStrike, int maxStrike)</code><code class="python">set_filter(minStrike: int, maxStrike: int)</code>
                    <p class="property-description">Selects the contracts that have a strike price within a minimum and maximum strike level relative to the underlying price. For example, say the underlying price is $302 and there are strikes at every $5. If you set <code class="csharp">minStrike</code><code class="python">m_strike</code> to -1 and <code class="csharp">maxStrike</code><code class="python">max_strike</code> to 1, LEAN selects the contracts that have a strike of $300 or $305. This filter runs <a href='/docs/v2/writing-algorithms/universes/settings#09-Asynchronous-Selection'>asynchronously</a> by default.</p>
                </td>
            </tr>
            <tr>
                <td>
                    <code class="csharp">SetFilter(TimeSpan minExpiry, TimeSpan maxExpiry)</code><code class="python">set_filter(minExpiry: timedelta, maxExpiry: timedelta)</code>
                    <p class="property-description">Selects the contracts that expire within the range you set. This filter runs <a href='/docs/v2/writing-algorithms/universes/settings#09-Asynchronous-Selection'>asynchronously</a> by default.</p>
                </td>
            </tr>
            <tr>
                <td>
                    <code class="csharp">SetFilter(int minStrike, int maxStrike, TimeSpan minExpiry, TimeSpan maxExpiry)</code><code class="python">set_filter(minStrike: int, maxStrike: int, minExpiry: timedelta, maxExpiry: timedelta)</code>
                    <p class="property-description">Selects the contracts that expire and have a strike within the range you set. This filter runs <a href='/docs/v2/writing-algorithms/universes/settings#09-Asynchronous-Selection'>asynchronously</a> by default.</p>
                </td>
            </tr>
            <tr>
                <td>
                    <code class="csharp">SetFilter(Func&lt;OptionFilterUniverse, OptionFilterUniverse&gt; universeFunc)</code><code class="python">set_filter(universeFunc: Callable[[OptionFilterUniverse], OptionFilterUniverse])</code>
                    <p class="property-description">Selects the contracts that a function selects.</p>
                </td>
            </tr>
    </tbody>
</table>


