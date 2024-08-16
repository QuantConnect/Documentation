<p>Options with zero days till expiry, or 0DTE options for short, are option contracts that expire on the same day they are traded. To create an universe with 0DTE options, call the <code class="csharp">SetFilter</code><code class="python">set_filter</code> method with the following argument.</p>

<div class="section-example-container">
    <pre class="csharp">option.SetFilter(u => u.IncludeWeeklys().Expiration(0, 0).Strikes(minStrike, maxStrike));</pre>
    <pre class="python">option.set_filter(lambda u: u.include_weeklys().expiration(0, 0).strikes(min_strike, max_strike))
</pre>
</div>