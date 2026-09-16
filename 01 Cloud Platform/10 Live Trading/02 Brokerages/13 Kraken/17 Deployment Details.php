<?
$brokerageName = "Kraken";
$deploymentDetails = [
    "kraken-verification-tier" => "The verification tier of the account, which sets the API rate limits. It's <code>Starter</code>, <code>Intermediate</code>, or <code>Pro</code>.",
    "kraken-orderbook-depth" => "The number of price levels in the order book feed that LEAN subscribes to for each pair. The default is <code>10</code>."
];
include(DOCS_RESOURCES."/live-trading/deployment-details.php");
?>
