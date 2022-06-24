<?php
$getOptionChainsText = function($path, $cSharpMemberName, $pythonMemberName, $cSharpVariableName="canonicalSymbol", $pythonVariableName="canonical_symbol")
{
    echo "
<p><code>OptionChain</code> objects represent and entire chain of Option contracts for a single underlying security. They have the following properties:</p>
<div data-tree='QuantConnect.Data.Market.OptionChain'></div>";
    
    if ($path == "regular")
    {
        include(DOCS_RESOURCES."/securities/regular-option-chains.php");
        $getRegularOptionChainsText($cSharpMemberName, $pythonMemberName, $cSharpVariableName, $pythonVariableName);
    }
    else if ($path == "universe")
    {
        echo file_get_contents(DOCS_RESOURCES."/securities/universe-option-chains.html");
    }
    else if ($path == "future-option")
    {
        echo file_get_contents(DOCS_RESOURCES."/securities/future-option-chains.html");
    }
}
?>
