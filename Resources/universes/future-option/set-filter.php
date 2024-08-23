<p>To set a contract filter, in the <code class="csharp">Initialize</code><code class="python">initialize</code> method, call the <code class="csharp">AddFutureOption</code><code class="python">add_future_option</code> method and pass the <code class="csharp">optionFilter</code><code class="python">option_filter</code> argument.

<div class="section-example-container">
    <pre class="csharp">// Select contracts that have a strike price within 1 strike level above and below the underlying price
AddFutureOption(_future.Symbol, optionFilterUniverse => optionFilterUniverse.Strikes(-1,1));

// Select contracts that expire within 30 days
AddFutureOption(_future.Symbol, optionFilterUniverse => optionFilterUniverse.Expiration(0, 30));

// Select contracts that have a strike price within 1 strike level and expire within 30 days
AddFutureOption(_future.Symbol, optionFilterUniverse => optionFilterUniverse.Strikes(-1,1).Expiration(0, 30));

// Select call contracts
AddFutureOption(_future.Symbol, optionFilterUniverse => optionFilterUniverse.CallsOnly());</pre>
    <pre class="python"># Select contracts that have a strike price within 1 strike level above and below the underlying price
self.add_future_option(self._future.symbol, lambda option_filter_universe: option_filter_universe.strikes(-1,1))

# Select contracts that expire within 30 days
self.add_future_option(self._future.symbol, lambda option_filter_universe: option_filter_universe.expiration(0,30))

# Select contracts that have a strike price within 1 strike level and expire within 30 days
self.add_future_option(self._future.symbol, lambda option_filter_universe: option_filter_universe.strikes(-1,1).expiration(0,30))

# Select call contracts
self.add_future_option(self._future.symbol, lambda option_filter_universe: option_filter_universe.calls_only())</pre>
</div>
