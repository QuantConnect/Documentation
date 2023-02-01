<p>We model buying power and margin calls to ensure your algorithm stays within the margin requirements.</p>

<h4>Buying Power</h4>
<p>Binance allows up to 3x leverage for spot trades in margin accounts and up to 25x leverage for Futures trades. Binance US doesn't currently support margin accounts.</p>

<?php include(DOCS_RESOURCES."/brokerages/margin-calls.html"); ?>

<h4>Margin Interest Payments</h4>

<p>If you trade Crypto Perpetual Futures, we model the margin cost and payments of your Crypto Future holdings by directly adjusting your portfolio cash. For more information about Futures margin interest modeling, see <a href='/docs/v2/writing-algorithms/reality-modeling/margin-interest-rate/supported-models#03-Binance-Futures-Model'>Binance Futures Model</a>.</p>