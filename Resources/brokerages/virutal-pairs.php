<?php
$getVirtualPairsText = function($appendedTest)
{
    echo "
    <p>All fiat and Crypto currencies are individual assets. When you buy a pair like BTCUSD, you trade USD for BTC. In this case, LEAN removes some USD from your portfolio <a href='/docs/v2/writing-algorithms/portfolio/cashbook'>cashbook</a> and adds some BTC. The virtual pair BTCUSD represents your position in that trade, but the virtual pair doesn't actually exist. It simply represents an open trade. {$appendedTest}</p>
    ";
}
?>
