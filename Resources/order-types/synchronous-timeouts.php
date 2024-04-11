<p><?=$orderName?> orders are synchronous by default, so your algorithm waits for the order to fill before moving to the next line of code. If your order takes longer than five seconds to fill, your algorithm continues executing even if the trade isn't filled. To adjust the timeout period, set the <code>Transactions.MarketOrderFillTimeout</code> property.</p>
  
<div class='section-example-container'>
<pre class='csharp'>// Adjust the market fill-timeout to 30 seconds.
Transactions.MarketOrderFillTimeout = TimeSpan.FromSeconds(30);
</pre>
<pre class='python'> # Adjust the market fill-timeout to 30 seconds.
self.transactions.market_order_fill_timeout = timedelta(seconds=30)</pre>
</div>

<? if (substr_compare($orderName, 'exercise', -8) !== 0) { ?>
<p><?=$orderName?> orders may take a few minutes to fill for illiquid assets such as out-of-the-money Options<?=$otherAssetType?>.</p>
<? } ?>