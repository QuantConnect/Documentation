<p><span class='new-term'>0DTE Options</span> are Option contracts that expire on the same day you trade them. To create a universe with 0DTE Options, call the <code class="csharp">SetFilter</code><code class="python">set_filter</code> method with the following argument:</p>

<div class="section-example-container">
    <pre class="csharp">option.SetFilter(u => u.IncludeWeeklys().Expiration(0, 0).Strikes(minStrike, maxStrike));</pre>
    <pre class="python">option.set_filter(lambda u: u.include_weeklys().expiration(0, 0).strikes(min_strike, max_strike))
</pre>
</div>
