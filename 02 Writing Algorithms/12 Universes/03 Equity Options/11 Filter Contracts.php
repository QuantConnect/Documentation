<?php echo file_get_contents(DOCS_RESOURCES."/universes/option/set-filter.html"); ?>


<p>The following table describes the filter methods of the <code>OptionFilterUniverse</code> class:</p>

<?php 
echo file_get_contents(DOCS_RESOURCES."/universes/option/option-filter-universe.html"); 
echo file_get_contents(DOCS_RESOURCES."/universes/option/filter-examples.html"); 
?>

<p>By default, LEAN adds contracts to the <code>OptionChain</code> that pass the filter criteria at every time step in your algorithm. In backtests, if a contract in the chain doesn't pass the filter criteria, LEAN removes it from the chain at the start of the next day. In live trading, LEAN removes these contracts from the chain every 15 minutes.</p>
