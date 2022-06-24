<?php
$getOptionChainsText = function($isFutureOptionUniverse)
{
    echo "
<p><code>OptionChain</code> objects represent and entire chain of Option contracts for a single underlying security. They have the following properties:</p>
<div data-tree='QuantConnect.Data.Market.OptionChain'></div>";
    
    
    if ($isFutureOptionUniverse)
    {
        $path = "future";
    }
    else
    {
        $path = "regular";
    }
    echo file_get_contents(DOCS_RESOURCES."/securities/{$path}-option-chains.html");
}
?>
