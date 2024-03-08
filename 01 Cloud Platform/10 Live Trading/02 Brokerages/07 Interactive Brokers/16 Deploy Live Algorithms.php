<?
$brokerageName = "Interactive Brokers";
$cashState = false;
$holdingsState = false;
$secondBullet = "";
$authentication = file_get_contents(DOCS_RESOURCES."/brokerages/interactive-brokers/deploy-steps.php");
$dataProviderDetails = file_get_contents(DOCS_RESOURCES."/brokerages/interactive-brokers/paper-trading-data-feeds.html");
$postDeploy = "<li>If your IB account has 2FA enabled, tap the notification on your IB Key device and then enter your pin.</li>";
include(DOCS_RESOURCES."/live-trading/deploy-live-algorithm.php");
?>
