<?php

$getIntroText = function($isCLIDocs)
{
	$product = $isCLIDocs ? "the CLI" : "QuantConnect Local" ;

    echo "
<p>
    If you run the LEAN engine locally with {$product}, LEAN executes in a Docker container.
    These Docker containers contain a minimal Linux-based operating system, the LEAN engine, and all the packages available to you on QuantConnect.com.
    It is therefore required to install Docker if you plan on using {$product} to run the LEAN engine locally.
</p>
    ";
}

?>






