<?
$liveDeployment = $cloudPlatform 
  ? "/docs/v2/cloud-platform/datasets/polygon#09-Live-Trading" 
  : "/docs/v2/lean-cli/live-trading/data-providers/polygon#02-Deploy-Local-Algorithms"
?>
<p>
    When you <a href='<?=$liveDeployment?>'>deploy a live algorithm</a>, you can add multiple data providers.
    If you use multiple data providers, the order you select them in defines their order of precedence in Lean.
<? if ($cloudPlatform) { ?>
    For example, if you set QuantConnect as the first provider and Polygon as the second provider, Lean only uses the Polygon data provider for securities that aren't available from the QuantConnect data provider.
    This configuration makes it possible to use QuantConnect data provider for Equity universe selection and use Polygon for Options on the securities in the universe.
<?} else {?>
    For example, if you set Polygon as the first provider and IB as the second provider, Lean only uses the IB data provider for securities that aren't available from the Polygon data provider.
    This configuration makes it possible to use Polygon data provider for Equity and use IB for Futures.
<?}?>
</p>
