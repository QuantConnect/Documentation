<?
$liveDeployment = $cloudPlatform 
  ? "/docs/v2/cloud-platform/datasets/databento#09-Live-Trading" 
  : "/docs/v2/lean-cli/live-trading/data-providers/databento#02-Deploy-Local-Algorithms"
?>
<p>
    When you <a href='<?=$liveDeployment?>'>deploy a live algorithm</a>, you can add multiple data providers.
    If you use multiple data providers, the order you select them in defines their order of precedence in Lean.
<? if ($cloudPlatform) { ?>
    For example, if you set Databento as the first provider and QuantConnect as the second provider, Lean only uses the QuantConnect data provider for securities that aren't available from the Databento data provider.
    This configuration makes it possible to use Databento for Futures and QuantConnect data provider for Equity.
<?} else {?>
    For example, if you set Databento as the first provider and IB as the second provider, Lean only uses the IB data provider for securities that aren't available from the Databento data provider.
    This configuration makes it possible to use Databento data provider for Futures and use IB for Equity.
<?}?>
</p>
