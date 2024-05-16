<p>
  When you <a href='<?=$liveDeployment?>'>deploy a live algorithm with the <?=$brokerageName?> brokerage</a>, you can use a third-party data provider, the <?=$brokerageName?> data provider, or both. 
  If you use multiple data providers, the order you select them in defines their order of precedence in Lean.
  For example, if you set QC as the first provider and <?=$brokerageName?> as the second provider, Lean only uses the <?=$brokerageName?> data provider for securities that aren't available from the QC data provider.
  This configuration makes it possible to use our data provider for Equity universe selection and then place Options trades on the securities in the universe.
  <? if ($brokerageName == "IB") { ?>
    If you use a third-party data provider, the assets that you subscribe to don't contribute to the <a href='/docs/v2/cloud-platform/datasets/interactive-brokers#03-Universe-Selection'>IB data limit</a>.
  <?}?>
</p>