<?php 
$isFutureOptions = true;
include(DOCS_RESOURCES."/securities/option-contracts.php"); 

$contractTypeName = "Option";
$pyContractTypeName = "option";
$chainTypeName = "OptionChains";
$pyChainTypeName = "option_chains";
$variableName = "optionChains";
include(DOCS_RESOURCES."/securities/open-interest.php"); 
?>

<h4>Properties</h4>
<p><code>OptionContract</code> objects have the following properties:</p>
<div data-tree='QuantConnect.Data.Market.OptionContract'></div>
