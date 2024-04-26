<? if($hasAutomaticIndicatorHelper) { ?>
<p>To create an automatic indicators for <code><?=$typeName?></code>, call the <code><?=$helperName?></code> helper method from the <code>QCAlgorithm</code> class. The <code><?=$helperName?></code> method creates a <code><?=$typeName?></code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>

<div class="section-example-container">
    <pre class="csharp">public class <?=$typeName?>Algorithm : QCAlgorithm
{
    private Symbol _symbol;
<? if($hasReference) { ?>
    private Symbol _reference;
<?} else if($isOptionIndicator) { ?>
    private Symbol _option, _mirrorOption;
<?}?>
    private <?=$typeName?> _<?=strtolower($helperName)?>;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
<? if($hasReference) { ?>
        _reference = AddEquity("QQQ", Resolution.Daily).Symbol;
<?} else if($isOptionIndicator) { ?>
        _option = QuantConnect.Symbol.CreateOption("SPY", Market.USA, OptionStyle.American, OptionRight.Put, 450m, new DateTime(2023, 12, 22));
        AddOptionContract(_option, Resolution.Daily);
        _mirrorOption = QuantConnect.Symbol.CreateOption("SPY", Market.USA, OptionStyle.American, OptionRight.Call, 450m, new DateTime(2023, 12, 22));
        AddOptionContract(_mirrorOption, Resolution.Daily);
<?}?>
        _<?=strtolower($helperName)?> = <?=$helperPrefix?><?=$helperName?>(<?=str_replace("symbol", "_symbol", str_replace("option_mirror_symbol", "_mirrorOption", str_replace("option_symbol", "_option", $helperArguments)))?>);
    }

    public override void OnData(Slice data)
    {
        if (_<?=strtolower($helperName)?>.IsReady)
        {
            // The current value of _<?=strtolower($helperName)?> is represented by itself (_<?=strtolower($helperName)?>)
            // or _<?=strtolower($helperName)?>.Current.Value
            Plot("<?=$typeName?>", "<?=strtolower($helperName)?>", _<?=strtolower($helperName)?>);
            <? if (count($properties) + count($otherProperties)  > 0) { ?>// Plot all properties of <?=strtolower($helperName)?><? }?>

<? foreach (array_merge($properties, $otherProperties) as $property) { ?>
            Plot("<?=$typeName?>", "<?=strtolower($property)?>", _<?=strtolower($helperName)?>.<?=$property?>);
<? } ?>
        }
    }
}</pre>
    <pre class="python">class <?=$typeName?>Algorithm(QCAlgorithm):
    def Initialize(self) -> None:
        self._symbol = self.AddEquity("SPY", Resolution.Daily).Symbol
<? if($hasReference) { ?>
        self.reference = self.AddEquity("QQQ", Resolution.Daily).Symbol
<?} else if($isOptionIndicator) { ?>
        self.option = Symbol.CreateOption("SPY", Market.USA, OptionStyle.American, OptionRight.Put, 450, datetime(2023, 12, 22))
        self.AddOptionContract(self.option, Resolution.Daily)
        self.mirrorOption = Symbol.CreateOption("SPY", Market.USA, OptionStyle.American, OptionRight.Call, 450, datetime(2023, 12, 22))
        self.AddOptionContract(self.mirrorOption, Resolution.Daily)
<?}?>
        self.<?=strtolower($helperName)?> = self.<?=$helperPrefix?><?=$helperName?>(<?=str_replace("symbol", "self.symbol", str_replace("option_mirror_symbol", "self.mirrorOption", str_replace("option_symbol", "self.option", $helperArguments)))?>)

    def on_data(self, slice: Slice) -> None:
        if self.<?=strtolower($helperName)?>.IsReady:
            # The current value of self.<?=strtolower($helperName)?> is represented by self.<?=strtolower($helperName)?>.Current.Value
            self.plot("<?=$typeName?>", "<?=strtolower($helperName)?>", self.<?=strtolower($helperName)?>.Current.Value)
            <? if (count($properties) + count($otherProperties) > 0) { ?># Plot all attributes of self.<?=strtolower($helperName)?><? } ?>

<? foreach ($properties as $property) { ?>
            self.plot("<?=$typeName?>", "<?=strtolower($property)?>", self.<?=strtolower($helperName)?>.<?=$property?>.Current.Value)
<? } ?>
<? foreach ($otherProperties as $property) { ?>
            self.plot("<?=$typeName?>", "<?=strtolower($property)?>", self.<?=strtolower($helperName)?>.<?=$property?>)
<? } ?>
</pre>
</div>

<p>The following reference table describes the <code><?=$helperName?></code> method:</p>
<? include(DOCS_RESOURCES."/qcalgorithm-api/" . strtolower($helperName) . ".html"); ?>

<p>If you don't provide a resolution, it defaults to the security resolution. If you provide a resolution, it must be greater than or equal to the resolution of the security. For instance, if you subscribe to hourly data for a security, you should update its indicator with data that spans 1 hour or longer.</p>
<p>For more information about the selector argument, see <a href="/docs/v2/writing-algorithms/indicators/automatic-indicators#07-Alternative-Price-Fields">Alternative Price Fields</a>.</p>

<p>For more information about plotting indicators, see <a href="/docs/v2/writing-algorithms/indicators/plotting-indicators">Plotting Indicators</a>.</p>

<? if($hasMovingAverageTypeParameter) { ?>
<p>The following table describes the <code>MovingAverageType</code> enumeration members:</p>
<div data-tree='QuantConnect.Indicators.MovingAverageType'></div>

<p>To avoid parameter ambiguity, use the <code>resolution</code> argument to set the <code>Resolution</code>.</p>

<div class="section-example-container">
    <pre class="csharp">public class <?=$typeName?>Algorithm : QCAlgorithm
{
    private Symbol _symbol;
<? if($hasReference) { ?>
    private Symbol _reference;
<?} else if($isOptionIndicator) { ?>
    private Symbol _option, _mirrorOption;
<?}?>
    private <?=$typeName?> _<?=strtolower($helperName)?>;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Hour).Symbol;
<? if($hasReference) { ?>
        _reference = AddEquity("QQQ", Resolution.Hour).Symbol;
<?} else if($isOptionIndicator) { ?>
        _option = QuantConnect.Symbol.CreateOption("SPY", Market.USA, OptionStyle.American, OptionRight.Put, 450m, new DateTime(2023, 12, 22));
        AddOptionContract(_option, Resolution.Daily);
        _mirrorOption = QuantConnect.Symbol.CreateOption("SPY", Market.USA, OptionStyle.American, OptionRight.Call, 450m, new DateTime(2023, 12, 22));
        AddOptionContract(_mirrorOption, Resolution.Daily);
<?}?>
        _<?=strtolower($helperName)?> = <?=$helperPrefix?><?=$helperName?>(<?=str_replace("symbol", "_symbol", str_replace("option_mirror_symbol", "_mirrorOption", str_replace("option_symbol", "_option", $helperArguments)))?>, resolution: Resolution.Daily);
    }
}</pre>
    <pre class="python">class <?=$typeName?>Algorithm(QCAlgorithm):
    def Initialize(self) -> None:
        self._symbol = self.AddEquity("SPY", Resolution.Hour).Symbol
<? if($hasReference) { ?>
        self.reference = self.AddEquity("QQQ", Resolution.Hour).Symbol
<?} else if($isOptionIndicator) { ?>
        self.option = Symbol.CreateOption("SPY", Market.USA, OptionStyle.American, OptionRight.Put, 450, datetime(2023, 12, 22))
        self.AddOptionContract(self.option, Resolution.Daily)
        self.mirrorOption = Symbol.CreateOption("SPY", Market.USA, OptionStyle.American, OptionRight.Call, 450, datetime(2023, 12, 22))
        self.AddOptionContract(self.mirrorOption, Resolution.Daily)
<?}?>
        self.<?=strtolower($helperName)?> = self.<?=$helperPrefix?><?=$helperName?>(<?=str_replace("symbol", "self.symbol", str_replace("option_mirror_symbol", "self.mirrorOption", str_replace("option_symbol", "self.option", $helperArguments)))?>, resolution=Resolution.Daily)
</pre>
</div>
<? } ?>
<? } else {?>
<p><?=$typeName?> does not have an automatic indicator implementation available.</p>
<? } ?>

<p>You can manually create a <code><?=$typeName?></code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>

<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method with <?=$updateParameterType?>. The indicator will only be ready after you prime it with enough data.</p>

<div class="section-example-container">
    <pre class="csharp">public class <?=$typeName?>Algorithm : QCAlgorithm
{
    private Symbol _symbol;
<? if($hasReference) { ?>
    private Symbol _reference;
<?} else if($isOptionIndicator) { ?>
    private Symbol _option, _mirrorOption;
<?}?>
    private <?=$typeName?> _<?=strtolower($helperName)?>;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
<? if($hasReference) { ?>
        _reference = AddEquity("QQQ", Resolution.Daily).Symbol;
<?} else if($isOptionIndicator) { ?>
        _option = QuantConnect.Symbol.CreateOption("SPY", Market.USA, OptionStyle.American, OptionRight.Put, 450m, new DateTime(2023, 12, 22));
        AddOptionContract(_option, Resolution.Daily);
        _mirrorOption = QuantConnect.Symbol.CreateOption("SPY", Market.USA, OptionStyle.American, OptionRight.Call, 450m, new DateTime(2023, 12, 22));
        AddOptionContract(_mirrorOption, Resolution.Daily);
<?}?>
        _<?=strtolower($helperName)?> = new <?=$typeName?>(<?=str_replace("symbol", "_symbol", str_replace("option_mirror_symbol", "_mirrorOption", str_replace("option_symbol", "_option", $constructorArguments)))?>);
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
        {      
            _<?=strtolower($helperName)?>.Update(<? if($isOptionIndicator) { ?>new IndicatorDataPoint(_symbol, bar.EndTime, bar.Close)<? } else { ?><?=$updateParameterValue?><? } ?>);
        }
<? if($hasReference) { ?>
        if (data.Bars.TryGetValue(_reference, out bar))
        {      
            _<?=strtolower($helperName)?>.Update(<?=$updateParameterValue?>);
        }
<?} else if($isOptionIndicator) { ?>
        if (data.QuoteBars.TryGetValue(_option, out bar))
        {      
            _<?=strtolower($helperName)?>.Update(new IndicatorDataPoint(_option, bar.EndTime, bar.Close));
        }
        if (data.QuoteBars.TryGetValue(_mirrorOption, out bar))
        {      
            _<?=strtolower($helperName)?>.Update(new IndicatorDataPoint(_mirrorOption, bar.EndTime, bar.Close));
        }
<?}?>   
        if (_<?=strtolower($helperName)?>.IsReady)
        {
            // The current value of _<?=strtolower($helperName)?> is represented by itself (_<?=strtolower($helperName)?>)
            // or _<?=strtolower($helperName)?>.Current.Value
            Plot("<?=$typeName?>", "<?=strtolower($helperName)?>", _<?=strtolower($helperName)?>);
            <? if (count($properties) + count($otherProperties) > 0) { ?>// Plot all properties of <?=strtolower($helperName)?><? } ?>

<? foreach (array_merge($properties, $otherProperties) as $property) { ?>
            Plot("<?=$typeName?>", "<?=strtolower($property)?>", _<?=strtolower($helperName)?>.<?=$property?>);
<? } ?>
        }
    }
}</pre>
    <pre class="python">class <?=$typeName?>Algorithm(QCAlgorithm):
    def Initialize(self) -> None:
        self._symbol = self.AddEquity("SPY", Resolution.Daily).Symbol
<? if($hasReference) { ?>
        self.reference = self.AddEquity("QQQ", Resolution.Daily).Symbol
<?} else if($isOptionIndicator) { ?>
        self.option = Symbol.CreateOption("SPY", Market.USA, OptionStyle.American, OptionRight.Put, 450, datetime(2023, 12, 22))
        self.AddOptionContract(self.option, Resolution.Daily)
        self.mirrorOption = Symbol.CreateOption("SPY", Market.USA, OptionStyle.American, OptionRight.Call, 450, datetime(2023, 12, 22))
        self.AddOptionContract(self.mirrorOption, Resolution.Daily)
<?}?>
        self.<?=strtolower($helperName)?> = <?=$typeName?>(<?=str_replace("symbol", "self.symbol", str_replace("option_mirror_symbol", "self.mirrorOption", str_replace("option_symbol", "self.option", $constructorArguments)))?>)

    def on_data(self, slice: Slice) -> None:
        bar = slice.Bars.get(self.symbol)
        if bar:
            self.<?=strtolower($helperName)?>.Update(<? if($isOptionIndicator) { ?>IndicatorDataPoint(self.symbol, bar.EndTime, bar.Close)<? } else { ?><?=$updateParameterValue?><? } ?>)
<? if($hasReference) { ?>
        bar = slice.Bars.get(self.referece)
        if bar:
            self.<?=strtolower($helperName)?>.Update(<?=$updateParameterValue?>)
<?} else if($isOptionIndicator) { ?>
        bar = slice.QuoteBars.get(self.option)
        if bar:
            self.<?=strtolower($helperName)?>.Update(IndicatorDataPoint(self.option, bar.EndTime, bar.Close))
        bar = slice.QuoteBars.get(self.mirrorOption)
        if bar:
            self.<?=strtolower($helperName)?>.Update(IndicatorDataPoint(self.mirrorOption, bar.EndTime, bar.Close))
<?}?>
        if self.<?=strtolower($helperName)?>.IsReady:
            # The current value of self.<?=strtolower($helperName)?> is represented by self.<?=strtolower($helperName)?>.Current.Value
            self.plot("<?=$typeName?>", "<?=strtolower($helperName)?>", self.<?=strtolower($helperName)?>.Current.Value)
            <? if (count($properties) + count($otherProperties) > 0) { ?># Plot all attributes of self.<?=strtolower($helperName)?><? } ?>

<? foreach ($properties as $property) { ?>
            self.plot("<?=$typeName?>", "<?=strtolower($property)?>", self.<?=strtolower($helperName)?>.<?=$property?>.Current.Value)
<? } ?>
<? foreach ($otherProperties as $property) { ?>
            self.plot("<?=$typeName?>", "<?=strtolower($property)?>", self.<?=strtolower($helperName)?>.<?=$property?>)
<? } ?>
</pre>
</div>

<p>To register a manual indicator for automatic updates with the security data, call the <code>RegisterIndicator</code> method.</p>

<div class="section-example-container">
    <pre class="csharp">public class <?=$typeName?>Algorithm : QCAlgorithm
{
    private Symbol _symbol;
<? if($hasReference) { ?>
    private Symbol _reference;
<?} else if($isOptionIndicator) { ?>
    private Symbol _option, _mirrorOption;
<?}?>
    private <?=$typeName?> _<?=strtolower($helperName)?>;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
<? if($hasReference) { ?>
        _reference = AddEquity("QQQ", Resolution.Daily).Symbol;
<?} else if($isOptionIndicator) { ?>
        _option = QuantConnect.Symbol.CreateOption("SPY", Market.USA, OptionStyle.American, OptionRight.Put, 450m, new DateTime(2023, 12, 22));
        AddOptionContract(_option, Resolution.Daily);
        _mirrorOption = QuantConnect.Symbol.CreateOption("SPY", Market.USA, OptionStyle.American, OptionRight.Call, 450m, new DateTime(2023, 12, 22));
        AddOptionContract(_mirrorOption, Resolution.Daily);
<?}?>
        _<?=strtolower($helperName)?> = new <?=$typeName?>(<?=str_replace("symbol", "_symbol", str_replace("option_mirror_symbol", "_mirrorOption", str_replace("option_symbol", "_option", $constructorArguments)))?>);
        RegisterIndicator(_symbol, _<?=strtolower($helperName)?>, Resolution.Daily);
<? if($hasReference) { ?>
        RegisterIndicator(reference, _<?=strtolower($helperName)?>, Resolution.Daily);
<?} else if($isOptionIndicator) { ?>
        RegisterIndicator(_option, _<?=strtolower($helperName)?>, Resolution.Daily);
        RegisterIndicator(_mirrorOption, _<?=strtolower($helperName)?>, Resolution.Daily);
<?}?>
    }

    public override void OnData(Slice data)
    {
        if (_<?=strtolower($helperName)?>.IsReady)
        {
            // The current value of _<?=strtolower($helperName)?> is represented by itself (_<?=strtolower($helperName)?>)
            // or _<?=strtolower($helperName)?>.Current.Value
            Plot("<?=$typeName?>", "<?=strtolower($helperName)?>", _<?=strtolower($helperName)?>);
            <? if (count($properties) > 0) { ?>// Plot all properties of <?=strtolower($helperName)?><? } ?>

<? foreach ($properties as $property) { ?>
            Plot("<?=$typeName?>", "<?=strtolower($property)?>", _<?=strtolower($helperName)?>.<?=$property?>);
<? } ?>
        }
    }
}</pre>
    <pre class="python">class <?=$typeName?>Algorithm(QCAlgorithm):
    def Initialize(self) -> None:
        self._symbol = self.AddEquity("SPY", Resolution.Daily).Symbol
<? if($hasReference) { ?>
        self.reference = self.AddEquity("QQQ", Resolution.Daily).Symbol
<?} else if($isOptionIndicator) { ?>
        self.option = Symbol.CreateOption("SPY", Market.USA, OptionStyle.American, OptionRight.Put, 450, datetime(2023, 12, 22))
        self.AddOptionContract(self.option, Resolution.Daily)
        self.mirrorOption = Symbol.CreateOption("SPY", Market.USA, OptionStyle.American, OptionRight.Call, 450, datetime(2023, 12, 22))
        self.AddOptionContract(self.mirrorOption, Resolution.Daily)
<?}?>
        self.<?=strtolower($helperName)?> = <?=$typeName?>(<?=str_replace("symbol", "self.symbol", str_replace("option_mirror_symbol", "self.mirrorOption", str_replace("option_symbol", "self.option", $constructorArguments)))?>)
        self.RegisterIndicator(self.symbol, self.<?=strtolower($helperName)?>, Resolution.Daily)
<? if($hasReference) { ?>
        self.RegisterIndicator(reference, self.<?=strtolower($helperName)?>, Resolution.Daily)
<?} else if($isOptionIndicator) { ?>
        self.RegisterIndicator(self.option, self.<?=strtolower($helperName)?>, Resolution.Daily)
        self.RegisterIndicator(self.mirrorOption, self.<?=strtolower($helperName)?>, Resolution.Daily)
<?}?>

    def on_data(self, slice: Slice) -> None:
        if self.<?=strtolower($helperName)?>.IsReady:
            # The current value of self.<?=strtolower($helperName)?> is represented by self.<?=strtolower($helperName)?>.Current.Value
            self.plot("<?=$typeName?>", "<?=strtolower($helperName)?>", self.<?=strtolower($helperName)?>.Current.Value)
            <? if (count($properties) > 0) { ?># Plot all attributes of self.<?=strtolower($helperName)?><? } ?>

<? foreach ($properties as $property) { ?>
            self.plot("<?=$typeName?>", "<?=strtolower($property)?>", self.<?=strtolower($helperName)?>.<?=$property?>.Current.Value)
<? } ?>
</pre>
</div>

<p>The following reference table describes the <code><?=$typeName?></code> constructor:</p>
<? include(DOCS_RESOURCES."/indicators/constructors/" . $constructorBox . ".html"); ?>
