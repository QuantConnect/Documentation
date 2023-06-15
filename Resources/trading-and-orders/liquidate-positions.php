<?php
if ($leanCli)
{
?>

<p>The <code>lean cloud live liquidate</code> command acts as a "kill switch" to sell all of your portfolio holdings. If your algorithm has a bug in it that caused it to purchase a lot of securities that you didn't want, this command let's you easily liquidate your portfolio instead of placing many manual trades. When you run the command, if the market is open for an asset you hold, the algorithm liquidates it with market orders. If the market is not open, the algorithm places market on open orders. After the algorithm submits the liquidation orders, it stops executing.</p>

<?php
}
else
{
?>

<p>The live results page has a <span class='button-name'>Liquidate</span> button that acts as a "kill switch" to sell all of your portfolio holdings. If your algorithm has a bug in it that caused it to purchase a lot of securities that you didn't want, this button let's you easily liquidate your portfolio instead of placing many manual trades. When you click the <span class='button-name'>Liquidate</span> button, if the market is open for an asset you hold, the algorithm liquidates it with market orders. If the market is not open, the algorithm places market on open orders. After the algorithm submits the liquidation orders, it stops executing.</p>

<p>Follow these steps to liquidate your positions:</p>

<ol>
    <li>Open your algorithm's <a href='/docs/v2/<? if ($cloudPlatform) { ?>cloud<? } elseif ($localPlatform) { ?>local<? } ?>-platform/live-trading/results#02-View-Live-Results'>live results page</a>.</li>
    <li>Click <span class='button-name'>Liquidate</span>.</li>
    <li>Click <span class='button-name'>Liquidate</span> again.</li>
</ol>

<?php
}
?>
