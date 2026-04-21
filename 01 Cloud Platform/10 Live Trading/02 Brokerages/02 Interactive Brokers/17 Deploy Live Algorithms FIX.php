<?
$brokerageName = "<a rel='nofollow' target='_blank' href='https://qnt.co/interactivebrokers'>Interactive Brokers</a>";
$cashState = false;
$holdingsState = false;
$secondBullet = "";
$authentication = "  <li>Enter your <a rel='nofollow' target='_blank' href='https://qnt.co/interactivebrokers'>IB</a> user name (see <a href='/docs/v2/cloud-platform/live-trading/brokerages/interactive-brokers#03-FIX-Integration'>FIX Integration</a>).</li>";
$dataProviderDetails =  "<p>In most cases, we suggest using <a href='/docs/v2/cloud-platform/datasets/interactive-brokers#06-Hybrid-Data-Provider'>both the QC and IB data providers</a>.</p>" . file_get_contents(DOCS_RESOURCES."/brokerages/interactive-brokers/paper-trading-data-feeds.html");
$postDeploy = "<li>If your IB account has 2FA enabled, tap the notification on your IB Key device and then enter your pin.</li>";
include(DOCS_RESOURCES."/live-trading/deploy-live-algorithm.php");
?>
