<p>To create an automatic indicators for <code><?=$typeName?></code>, call the <code><?=$helperMethod?></code> helper method from the QCAlgorithm class.</p>

<div class="section-example-container">
    <pre class="csharp">private <?=$typeName?> _<?=strtolower($typeName)?>;

public override void Initialize()
{
    var option = QuantConnect.Symbol.CreateOption("AAPL", Market.USA, OptionStyle.American, OptionRight.Put, 505m, new DateTime(2014, 6, 27));
    AddOptionContract(option);

    // indicator using single-contract IV calculation
    _<?=strtolower($typeName)?> = <?=$helperMethod?>(option);

    // indicator using mirror-contract IV calculation
    var mirrorOption = QuantConnect.Symbol.CreateOption("AAPL", Market.USA, OptionStyle.American, OptionRight.Call, 505m, new DateTime(2014, 6, 27));
    AddOptionContract(mirrorOption);
    _<?=strtolower($typeName)?> = <?=$helperMethod?>(option, mirrorOption);
}</pre>
    <pre class="python">def Initialize(self):
    option = Symbol.CreateOption("AAPL", Market.USA, OptionStyle.American, OptionRight.Put, 505, datetime(2014, 6, 27))
    self.AddOptionContract(option)

    # indicator using single-contract IV calculation
    self.<?=strtolower($typeName)?> = self.<?=$helperMethod?>(option)

    # indicator using mirror-contract IV calculation
    mirror_option = Symbol.CreateOption("AAPL", Market.USA, OptionStyle.American, OptionRight.Call, 505, datetime(2014, 6, 27))
    self.AddOptionContract(mirror_option)
    self.<?=strtolower($typeName)?> = self.<?=$helperMethod?>(option, mirror_option)
</pre>
</div>
