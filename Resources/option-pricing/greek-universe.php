<p>The Greeks and implied volatility values in the filter function are daily, pre-calculated values based on the end of the previous trading day.</p>

<div class="section-example-container">
    <pre class="csharp">public override void Initialize()
{
    var option = <?=$addOptionC?>
    option.SetFilter(optionFilterUniverse => optionFilterUniverse.IncludeWeeklys().Delta(0.3m, 0.7m).Expiration(0, 7);
}</pre>
    <pre class="python">def initialize(self):
    option = <?=$addOptionPy?>
    option.set_filter(lambda option_filter_universe: option_filter_universe.include_weeklys().delta(0.3, 0.7).expiration(0,7))</pre>
</div>
