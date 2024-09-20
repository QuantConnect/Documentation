<p>
  The Greeks and IV values that you get from the current <a href='/docs/v2/writing-algorithms/key-concepts/time-modeling/timeslices'>Slice</a> are from the calculations of the <a href='/docs/v2/writing-algorithms/reality-modeling/options-models/pricing'>price model</a>.
  These values aren't the same as the values you get in the universe filter function.
</p>

<div class="section-example-container">
    <pre class="csharp">public override Initialize()
{
    var option = <?=$addOptionC?>
    option.PriceModel = OptionPriceModels.CrankNicolsonFD();
}</pre>
    <pre class="python">def initialize(self):
    option = <?=$addOptionPy?>
    option.price_model = OptionPriceModels.crank_nicolson_fd()</pre>
</div>

<p>To view all the models LEAN supports, see <a href='/docs/v2/writing-algorithms/reality-modeling/options-models/pricing#06-Supported-Models'>Supported Models</a>.</p>
