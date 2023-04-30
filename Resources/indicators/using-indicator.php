<p>To create an automatic indicators for <code><?=$typeName?></code>, call the <code><?=$helperName?></code> helper method from the <code>QCAlgorithm</code> class. The <code><?=$helperName?></code> method creates a <code><?=$typeName?></code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code>Initialize</code> method.<p>

<div class="section-example-container">
    <pre class="csharp">public class <?=$typeName?>Algorithm : QCAlgorithm
{
    private Symbol _symbol;
    private <?=$typeName?> _<?=strtolower($helperName)?>;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _<?=strtolower($helperName)?> = <?=$helperName?>(<?=$helperArguments?>);
    }

    public override void OnData(Slice data)
    {
        if (_<?=strtolower($helperName)?>.IsReady)
        {
            Plot("<?=$typeName?>", "<?=strtolower($helperName)?>", _<?=strtolower($helperName)?>);
<? foreach ($properties as $property) { ?>
            Plot("<?=$typeName?>", "<?=strtolower($property)?>", _<?=strtolower($helperName)?>.<?=$property?>);
<? } ?>
        }
    }
}</pre>
    <pre class="python">class <?=$typeName?>Algorithm(QCAlgorithm):
    def Initialize(self) -> None:
        self.symbol = self.AddEquity("SPY", Resolution.Daily).Symbol
        self.<?=strtolower($helperName)?> = self.<?=$helperName?>(<?=$helperArguments?>)

    def OnData(self, slice: Slice) -> None:
        if self.<?=strtolower($helperName)?>.IsReady:
            self.Plot("<?=$typeName?>", "<?=strtolower($helperName)?>", self.<?=strtolower($helperName)?>.Current.Value)
<? foreach ($properties as $property) { ?>
            self.Plot("<?=$typeName?>", "<?=strtolower($property)?>", self.<?=strtolower($helperName)?>.<?=$property?>.Current.Value)
<? } ?>
</pre>
</div>

<p>The following reference table describes the <?=$helperName?> method:</p>
<? include(DOCS_RESOURCES."/qcalgorithm-api/" . strtolower($helperName) . ".html"); ?>

<p>If you don't provide a resolution, it defaults to the security resolution. If you provide a resolution, it must be greater than or equal to the resolution of the security. For instance, if you subscribe to hourly data for a security, you should update its indicator with data that spans 1 hour or longer.</p>
<p>For more information about the selector argument, see <a href="/docs/v2/writing-algorithms/indicators/automatic-indicators#07-Alternative-Price-Fields">Alternative Price Fields</a>.</p>

<p>For more information about plotting indicators, see <a href="/docs/v2/writing-algorithms/indicators/plotting-indicators">Plotting Indicators</a>.</p>

<? if($hasMovingAverageTypeParameter) { ?>
<p>The following table describes the <code>MovingAverageType</code> enumeration members:</p>
<div data-tree='QuantConnect.Indicators.MovingAverageType'></div>
<? } ?>

<p>You can manually create a <code><?=$typeName?></code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>

<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code>Update</code> method with <?=$updateParameterType?>. The indicator will only be ready after you prime it with enough data.</p>

<div class="section-example-container">
    <pre class="csharp">public class <?=$typeName?>Algorithm : QCAlgorithm
{
    private Symbol _symbol;
    private <?=$typeName?> _<?=strtolower($helperName)?>;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _<?=strtolower($helperName)?> = new <?=$typeName?>(<?=$constructorArguments?>);
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGeValue(_symbol, out var bar))
        {      
            _<?=strtolower($helperName)?>.Update(<?=$updateParameterValue?>);
        }

        if (_<?=strtolower($helperName)?>.IsReady)
        {
            Plot("<?=$typeName?>", "<?=strtolower($helperName)?>", _<?=strtolower($helperName)?>);
<? foreach ($properties as $property) { ?>
            Plot("<?=$typeName?>", "<?=strtolower($property)?>", _<?=strtolower($helperName)?>.<?=$property?>);
<? } ?>
        }
    }
}</pre>
    <pre class="python">class <?=$typeName?>Algorithm(QCAlgorithm):
    def Initialize(self) -> None:
        self.symbol = self.AddEquity("SPY", Resolution.Daily).Symbol
        self.<?=strtolower($helperName)?> = <?=$typeName?>(<?=$constructorArguments?>)

    def OnData(self, slice: Slice) -> None:
        bar = slice.Bars.get(self.symbol)
        if bar:
            self.<?=strtolower($helperName)?>.Update(<?=$updateParameterValue?>)

        if self.<?=strtolower($helperName)?>.IsReady:
            self.Plot("<?=$typeName?>", "<?=strtolower($helperName)?>", self.<?=strtolower($helperName)?>.Current.Value)
<? foreach ($properties as $property) { ?>
            self.Plot("<?=$typeName?>", "<?=strtolower($property)?>", self.<?=strtolower($helperName)?>.<?=$property?>.Current.Value)
<? } ?>
</pre>
</div>

<p>To register a manual indicator for automatic updates with the security data, call the <code>RegisterIndicator</code> method.</p>

<div class="section-example-container">
    <pre class="csharp">public class <?=$typeName?>Algorithm : QCAlgorithm
{
    private Symbol _symbol;
    private <?=$typeName?> _<?=strtolower($helperName)?>;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _<?=strtolower($helperName)?> = new <?=$typeName?>(<?=$constructorArguments?>);
        RegisterIndicator(_symbol, _<?=strtolower($helperName)?>, Resolution.Daily);
    }

    public override void OnData(Slice data)
    {
        if (_<?=strtolower($helperName)?>.IsReady)
        {
            Plot("<?=$typeName?>", "<?=strtolower($helperName)?>", _<?=strtolower($helperName)?>);
<? foreach ($properties as $property) { ?>
            Plot("<?=$typeName?>", "<?=strtolower($property)?>", _<?=strtolower($helperName)?>.<?=$property?>);
<? } ?>
        }
    }
}</pre>
    <pre class="python">class <?=$typeName?>Algorithm(QCAlgorithm):
    def Initialize(self) -> None:
        self.symbol = self.AddEquity("SPY", Resolution.Daily).Symbol
        self.<?=strtolower($helperName)?> = <?=$typeName?>(<?=$constructorArguments?>)
        self.RegisterIndicator(self.symbol, self.<?=strtolower($helperName)?>, Resolution.Daily)

    def OnData(self, slice: Slice) -> None:
        if self.<?=strtolower($helperName)?>.IsReady:
            self.Plot("<?=$typeName?>", "<?=strtolower($helperName)?>", self.<?=strtolower($helperName)?>.Current.Value)
<? foreach ($properties as $property) { ?>
            self.Plot("<?=$typeName?>", "<?=strtolower($property)?>", self.<?=strtolower($helperName)?>.<?=$property?>.Current.Value)
<? } ?>
</pre>
</div>

<p>The following reference table describes the <?=$typeName?> constructor:</p>
<? include(DOCS_RESOURCES."/indicators/constructors/" . $constructorBox . ".html"); ?>
