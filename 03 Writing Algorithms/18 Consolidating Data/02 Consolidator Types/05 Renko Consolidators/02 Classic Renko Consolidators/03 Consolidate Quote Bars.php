<?php 
include(DOCS_RESOURCES."/consolidators/manage-consolidators.php");
$output = "<code>RenkoBar</code> objects";
$consolidationHandlerType = "RenkoBar";
$dataFormatInfo = new QuoteBarConsolidatorFormatInfo($output, $consolidationHandlerType);

$extraExamples = "
<p>The following arguments enable you to create Renko bars that aggregate the excess liquidity on the bid.</p>

<div class='section-example-container'>
	<pre class='python'>self.consolidator = ClassicRenkoConsolidator(10, lambda data: data.Value, lambda data: data.LastBidSize - data.LastAskSize)</pre>
<pre class='csharp'>_consolidator = new ClassicRenkoConsolidator(10, null, 
    data => (data as QuoteBar).LastBidSize - (data as QuoteBar).LastAskSize);</pre>
</div>
";
$consolidatorInfo = new ClassicRenkoConsolidatorInfo($extraExamples);

$getConsolidatorText($dataFormatInfo, $consolidatorInfo);
?>
