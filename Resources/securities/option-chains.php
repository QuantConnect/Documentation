<?php
$getOptionChainsText = function($isFutureOptionUniverse, $cSharpMemberName, $pythonMemberName, $cSharpVariablName="canonicalSymbol", $pythonVariableName="canonical_symbol")
{
    echo "
<p><code>OptionChain</code> objects represent and entire chain of Option contracts for a single underlying security. They have the following properties:</p>
<div data-tree='QuantConnect.Data.Market.OptionChain'></div>";
    
    
    if ($isFutureOptionUniverse)
    {
        echo file_get_contents(DOCS_RESOURCES."/securities/future-option-chains.html");
    }
    else
    {
        include(DOCS_RESOURCES."/securities/regular-option-chains.php");
        $getRegularOptionChainsText($cSharpMemberName, $pythonMemberName, $cSharpVariablName, $pythonVariableName);
    }
}
?>
