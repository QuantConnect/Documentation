<?php
$getFilterCaveatText = function($assetClass)
{
  echo "<p>Some of the preceding filter methods only set an internal enumeration in the <code>{$assetClass}FilterUniverse</code> that it uses later on in the filter process. This subset filter methods don't immediately reduce the number of contract <code>Symbol</code> objects in the <code>{$assetClass}FilterUniverse</code>.</p>";
}
?>
