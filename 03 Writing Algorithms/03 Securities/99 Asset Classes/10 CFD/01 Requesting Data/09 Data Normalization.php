<? $specificAsset = ""; $additionalInformation = ""; include(DOCS_RESOURCES."/securities/data-normalization-raw-only.php"); ?>
<p>
Interactive Brokers Stock CFDs apply corporate actions to the price of the asset, including paying dividends in cash to your account. This behavior is not modeled today and splits in live trading will result in price discontinuities. You should <a href='/docs/v2/writing-algorithms/securities/asset-classes/us-equity/corporate-actions'>monitor the underlying Equity</a> for events and use the underlying Equity for indicators. 
</p>
