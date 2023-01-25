<?php
$getLogIntroText = function($terminalLink)
{
    echo "
<p>Algorithms can record string messages ("log statements") to a file for analysis after a backtest is complete, or as a live algorithm is running. These records can assist in debugging logical flow errors in the project code. Consider adding them in the code block of an <code>if</code> statement to signify an error has been caught.</p>

<p>It's good practice to add logging statements to live algorithms so you can understand its behavior and keep records to compare against backtest results. If you don't add logging statements to a live algorithm and the algorithm doesn't trade as you expect, it's difficult to evaluate the underlying problem.</p>
    ";
}
?>
