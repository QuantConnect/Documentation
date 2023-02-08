<p><?php if ($writingAlgorithms) {?>The Binance brokerage models support<?php } else { ?>Our Binance integration supports<?php } ?> trading <a href='/docs/v2/writing-algorithms/securities/asset-classes/crypto'>Crypto</a> and <a href='/docs/v2/writing-algorithms/securities/asset-classes/crypto-futures'>Crypto Futures</a>.</p>

<?php
if ($cloudPlatform) {
?>
<div class="section-example-container">
    <pre class="csharp">AddCrypto("BTCUSDT", Resolution.Minute, Market.Binance);
AddCryptoFuture("BTCUSD", Resolution.Minute, Market.Binance);
AddCrypto("BTCUSDT", Resolution.Minute, Market.BinanceUS);</pre>
    <pre class="python">self.AddCrypto("BTCUSDT", Resolution.Minute, Market.Binance)
self.AddCryptoFuture("BTCUSD", Resolution.Minute, Market.Binance)</pre>
</div>
<?php
}
?>

<p><?php if ($writingAlgorithms) {?>The Binance US brokerage model <?php } else { ?>Our Binance US integration<?php } ?> supports trading <a href='/docs/v2/writing-algorithms/securities/asset-classes/crypto'>Crypto</a>.</p>

<?php
if ($cloudPlatform) {
?>
<div class="section-example-container">
    <pre class="csharp">AddCrypto("BTCUSDT", Resolution.Minute, Market.BinanceUS);</pre>
    <pre class="python">self.AddCrypto("BTCUSDT", Resolution.Minute, Market.BinanceUS)</pre>
</div>
<?php
}
?>