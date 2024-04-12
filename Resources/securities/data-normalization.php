<p>The data normalization mode defines how historical data is adjusted for <a href='/docs/v2/writing-algorithms/securities/asset-classes/us-equity/corporate-actions'>corporate actions</a>.<? if ($writingAlgorithms) { ?> The data normalization mode affects the data that LEAN passes to <code>OnData</code> and the data from <a href='/docs/v2/writing-algorithms/historical-data/history-requests'>history requests</a>. <? } ?> By default, LEAN adjusts US Equity data for splits and dividends to produce a smooth price curve, but the following data normalization modes are available:</p>
    
<div data-tree='QuantConnect.DataNormalizationMode' data-fields='Raw,Adjusted,SplitAdjusted,TotalReturn<?=$research ? ",ScaledRaw" : "";?>'></div>

<? include(DOCS_RESOURCES."/datasets/data-normalization.php"); ?>

<p>To set the data normalization mode for a security, pass a <code>dataNormalizationMode</code> argument to the <code>AddEquity</code> method.</p>    

<div class='section-example-container'>
    <pre class='csharp'>var <?=$cSharpVariableName?> = <?=$writingAlgorithms ? "" : "qb." ?>AddEquity("<?=$ticker?>", dataNormalizationMode: DataNormalizationMode.Raw).Symbol;</pre>
    <pre class='python'><?=$pyVariableName?> = <?=$writingAlgorithms ? "self" : "qb" ?>.add_equity("<?=$ticker?>", data_normalization_mode=DataNormalizationMode.RAW).symbol</pre>
</div>

<? if ($writingAlgorithms) { ?>
<p>To set the data normalization mode for all securities in an algorithm, set the <a href='/docs/v2/writing-algorithms/universes/settings#07-Data-Normalization-Mode'>DataNormalizationMode universe setting</a> before you create the security subscriptions.</p>

 <div class='section-example-container'>
    <pre class='csharp'>UniverseSettings.DataNormalizationMode = DataNormalizationMode.Raw;</pre>
    <pre class='python'>self.universe_settings.data_normalization_mode = DataNormalizationMode.RAW</pre>
</div>            
<? } ?>
