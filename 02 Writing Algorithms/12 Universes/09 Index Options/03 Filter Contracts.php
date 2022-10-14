<?php include(DOCS_RESOURCES."/universes/option/set-filter.php"); ?>


<p>The following table describes the filter methods of the <code>OptionFilterUniverse</code> class:</p>

<?php 
echo file_get_contents(DOCS_RESOURCES."/universes/option/option-filter-universe.html"); 
echo file_get_contents(DOCS_RESOURCES."/universes/option/filter-examples.html"); 
?>


<p>By default, LEAN adds contracts to the <code>OptionChain</code> that pass the filter criteria at every time step in your algorithm. If a contract has been in the universe for a duration that matches the <code>MinimumTimeInUniverse</code> <a href='/docs/v2/writing-algorithms/universes/key-concepts#05-Universe-Settings'>setting</a> and it no longer passes the filter criteria, LEAN removes it from the chain</p>
