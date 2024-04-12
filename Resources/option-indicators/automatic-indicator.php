<p>To create an <a href='/docs/v2/writing-algorithms/indicators/automatic-indicators'>automatic indicator</a> for <?=$name?>, call the <code>QCAlgorithm.<?=$helperMethod?></code> method with the Option contract <code>Symbol</code> object(s).</p>

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
    <pre class="python">def Initialize(self):
    option = Symbol.CreateOption("AAPL", Market.USA, OptionStyle.American, OptionRight.Put, 505, datetime(2014, 6, 27))
    self.AddOptionContract(option)

    # Example of using the single-contract IV calculation:
    self.<?=strtolower($typeName)?> = self.<?=$helperMethod?>(option)

    # Example of using the using mirror-contract IV calculation:
    mirror_option = Symbol.CreateOption("AAPL", Market.USA, OptionStyle.American, OptionRight.Call, 505, datetime(2014, 6, 27))
    self.AddOptionContract(mirror_option)
    self.<?=strtolower($typeName)?> = self.<?=$helperMethod?>(option, mirror_option)
</pre>
</div>
