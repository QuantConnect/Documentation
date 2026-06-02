<p>
  dYdX is not a brokerage, it is a decentralised, disintermediated and permissionless protocol. However many parts of the QuantConnect platform include dYdX in a brokerage list for simple user-experience.
</p>
<?
$brokerageName = "dYdX Exchange";
$cashState = false;
$holdingsState = false;
$secondBullet = "";
$authentication = "<li>Enter API your private key, wallet address, and sub-account number</li>";
$authentication .= file_get_contents(DOCS_RESOURCES."/brokerages/create-credentials/dydx.html");
$postDeploy = "";
include(DOCS_RESOURCES."/live-trading/deploy-live-algorithm.php");
?>
