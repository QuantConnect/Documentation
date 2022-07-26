<?php 
include(DOCS_RESOURCES."/securities/markets/crypto.php"); 
$getMarketsText(true);

echo file_get_contents(DOCS_RESOURCES."/securities/supported-markets.html"); 
?>
