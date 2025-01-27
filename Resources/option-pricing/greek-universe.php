<p>The Greeks and IV values in the <a href='<?=$filterLink?>'>filter function</a> are the daily, pre-calculated values based on the end of the previous trading day.</p>

<div class="section-example-container">
    <pre class="csharp">public override void Initialize()
{
    var option = <?=$addOptionC?>
    option.SetFilter(universe => universe.IncludeWeeklys().Delta(0.3m, 0.7m).Expiration(0, 7));
}</pre>
    <pre class="python">def initialize(self):
    option = <?=$addOptionPy?>
    option.set_filter(lambda universe: universe.include_weeklys().delta(0.3, 0.7).expiration(0,7))</pre>
</div>

<p><?=$calculationMethod?></p>

<p>
    You can't customize the Greeks and IV values that the filter function receives.
    However, you can create <a href='<?=$indicatorLink?>'>indicators</a> to customize how the Greeks and IV are calculated for the contracts already in your universe.
</p>
