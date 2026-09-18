<?
$streamOfText = "US Index Option trades, quotes, and open interest";
include(DOCS_RESOURCES."/data-feeds/introductions.php");
?>

<p>The QuantConnect data provider streams the SPX and VIX index values in its <a href='/docs/v2/cloud-platform/datasets/quantconnect/us-indices'>US Indices</a> dataset, so you can trade SPX and VIX Index Options without another data provider. To trade Index Options on other indices, such as NDX, add a data provider that supplies the underlying index.</p>

<p>The QuantConnect data provider also provides the <a href='/docs/v2/cloud-platform/datasets/quantconnect/auxiliary-data#05-US-Index-Option-Universe'>US Index Option Universe</a> dataset, which lists the available contracts with their daily Greeks and implied volatility values.</p>
