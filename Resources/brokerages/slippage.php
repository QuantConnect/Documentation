<?php

function getSlippageText($brokerageName, $paperTradingSupported) {
	$result = "<p>Orders through $brokerageName do not experience slippage in backtests. In";
	if ($paperTradingSupported) {
		$result .= " paper trading and";
	}
	$result .= " live trading, your orders may experience slippage.";
	echo $result;
}

?>
