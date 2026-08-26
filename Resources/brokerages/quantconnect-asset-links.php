<?
// Turns $quantConnectAssets, a list of asset class names, into $quantConnectAssetLinks, a sentence
// fragment that links each name to its Datasets > QuantConnect page.
$quantConnectAssetSlugs = [
    "US Equities"       => "us-equities",
    "US Equity Options" => "us-equity-options",
    "US Index Options"  => "us-index-options",
    "Futures"           => "futures",
    "Crypto"            => "crypto",
    "Crypto Futures"    => "crypto-futures",
    "Forex"             => "forex",
    "CFD"               => "cfd"
];

$links = [];
foreach ($quantConnectAssets as $asset) {
    $links[] = "<a href='/docs/v2/cloud-platform/datasets/quantconnect/" . $quantConnectAssetSlugs[$asset] . "'>" . $asset . "</a>";
}

$last = array_pop($links);
$quantConnectAssetLinks = $links
    ? implode(", ", $links) . (count($links) > 1 ? ", and " : " and ") . $last
    : $last;
?>