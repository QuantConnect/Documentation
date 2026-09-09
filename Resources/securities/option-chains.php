<p><code>OptionChain</code> objects represent an entire chain of Option contracts for a single underlying security.</p>

<?    
    if ($path == "regular")
    {
        include(DOCS_RESOURCES."/securities/regular-option-chains.php");
    }
    else if ($path == "universe")
    {
        include(DOCS_RESOURCES."/securities/universe-option-chains.html");
    }
    else if ($path == "future-option")
    {
        include(DOCS_RESOURCES."/securities/future-option-chains.html");
    }
?>

<?
    $optionUniverseUrl = $optionUniverseUrl ?? "/docs/v2/writing-algorithms/universes/equity-options";
    include(DOCS_RESOURCES."/securities/option-chain-filters.php");
    include(DOCS_RESOURCES."/securities/option-chain-selection.html");
?>

<h4>Properties</h4>
<p><code>OptionChain</code> objects have the following properties:</p>
<div data-tree='QuantConnect.Data.Market.OptionChain'></div>
