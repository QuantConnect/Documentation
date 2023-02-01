<p><code>OptionChain</code> objects represent an entire chain of Option contracts for a single underlying security. They have the following properties:</p>
<div data-tree='QuantConnect.Data.Market.OptionChain'></div>

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