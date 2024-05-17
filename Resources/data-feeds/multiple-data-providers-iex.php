<?
$liveDeployment = $cloudPlatform 
  ? "/docs/v2/cloud-platform/datasets/iex-cloud#09-Live-Trading" 
  : "/docs/v2/lean-cli/live-trading/data-providers/iex-cloud#02-Deploy-Local-Algorithms"
?>
<p>
    When you <a href='<?=$liveDeployment?>'>deploy a live algorithm</a>, you can add multiple data provider.
    If you use multiple data providers, the order you select them in defines their order of precedence in Lean.
    For example, if you set IEX Cloud as the first provider and IB as the second provider, Lean only uses the IB data provider for securities that aren't available from the IEX Cloud data provider.
    This configuration makes it possible to use IEX Cloud data provider for Equity universe selection and then place Options trades on the securities in the universe.
</p>