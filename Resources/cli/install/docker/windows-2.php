<?php

$getInstallWindowsText = function($isCLIDocs)
{
    $product = $isCLIDocs ? "the CLI" : "QuantConnect Local";

    echo "
<p>
    By default, Docker doesn't automatically start when your computer starts.
    So, when you run the LEAN engine with {$product} for the first time after starting your computer, you must manually start Docker.
    To automatically start Docker, open the Docker Desktop application, click <span class='menu-name'>Settings &gt; General</span>, and  then enable the <span class='box-name'>Start Docker Desktop when you log in</span> check box.
</p>
    ";
}
?>

