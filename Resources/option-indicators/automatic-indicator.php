<p>To create an <a href='/docs/v2/writing-algorithms/indicators/automatic-indicators'>automatic indicator</a> for <?=$name?>, call the <code class="csharp">QCAlgorithm.<?=$helperMethod?></code><code class="python">QCAlgorithm.<?=strtolower($helperMethod)?></code> method with the Option contract <code class="csharp">Symbol</code><code class="python">symbol</code> object(s).</p>

<div class="section-example-container">
    <pre class="csharp">private <?=$typeName?> _<?=strtolower($typeName)?>;

public override void Initialize()
{
    var option = QuantConnect.Symbol.CreateOption("AAPL", Market.USA, OptionStyle.American, OptionRight.Put, 505m, new DateTime(2014, 6, 27));
    AddOptionContract(option);

    // Example of using the single-contract IV calculation:
    _<?=strtolower($typeName)?> = <?=$helperMethod?>(option);

    // Example of using the using mirror-contract IV calculation:
    var mirrorOption = QuantConnect.Symbol.CreateOption("AAPL", Market.USA, OptionStyle.American, OptionRight.Call, 505m, new DateTime(2014, 6, 27));
    AddOptionContract(mirrorOption);
    _<?=strtolower($typeName)?> = <?=$helperMethod?>(option, mirrorOption);
}</pre>
    <pre class="python">def initialize(self):
    option = Symbol.create_option("AAPL", Market.USA, OptionStyle.AMERICAN, OptionRight.PUT, 505, datetime(2014, 6, 27))
    self.add_option_contract(option)

    # Example of using the single-contract IV calculation:
    self.<?=strtolower($typeName)?> = self.<?=strtolower($helperMethod)?>(option)

    # Example of using the using mirror-contract IV calculation:
    mirrorOption = Symbol.create_option("AAPL", Market.USA, OptionStyle.AMERICAN, OptionRight.CALL, 505, datetime(2014, 6, 27))
    self.add_option_contract(mirror_option)
    self.<?=strtolower($typeName)?> = self.<?=strtolower($helperMethod)?>(option, mirror_option)
</pre>
</div>