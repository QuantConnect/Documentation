<?php

$getIntroText = function($isCLIDocs)
{
    if ($isCLIDocs)
    {
        echo "
        <p>
            The Lean CLI is distributed as a Python package, so it requires <code>pip</code> to be installed.
            Because <code>pip</code> is distributed as a part of Python, you must install Python before you can install the CLI.
        </p>
        ";
    }
    else
    {
        echo "
        <p>
            QuantConnect Local requires <code>pip</code> to be installed.
            Because <code>pip</code> is distributed as a part of Python, you must install Python before you can install the QuantConnect Local extension.
        </p>
        ";
    }
}

?>
