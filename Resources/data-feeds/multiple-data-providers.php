<p>
  When you <a href='<?=$liveDeployment?>'>deploy a live algorithm with the <?=$brokerageName?> brokerage</a>, you can use the <a href='/docs/v2/cloud-platform/datasets/quantconnect'>QuantConnect data provider</a>, the <?=$brokerageName?> data provider, or both.
  If you use multiple data providers, the order you select them in defines their order of precedence in Lean, so Lean only uses a provider for the securities that the providers before it don't supply.
  <? if ($brokerageName == "IB") { ?>
    If you use a third-party data provider, the assets that you subscribe to don't contribute to the <a rel="nofollow" target="_blank" href="https://interactivebrokers.github.io/tws-api/historical_limitations.html">IB data limit</a>.
  <?}?>
</p>

<p>We suggest the following configurations, in order of preference:</p>

<ol>
  <li>The QuantConnect data provider on its own. It's free and it's high quality, and it covers most asset classes.</li>
  <li>The QuantConnect data provider first and a provider that fills its gaps second. The QuantConnect data provider doesn't supply Index data (for example, SPX, VIX, and NDX) or Future Options data, so you need this configuration to trade Index Options, which need the underlying index, and to trade Future Options.</li>
  <li>The <?=$brokerageName?> data provider first and the QuantConnect data provider last. Use this configuration if you want to trade on <?=$brokerageName?> prices and let the QuantConnect data provider cover the rest.</li>
</ol>
