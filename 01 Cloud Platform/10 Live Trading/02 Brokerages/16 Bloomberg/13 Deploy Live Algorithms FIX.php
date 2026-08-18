<p>This section explains how to deploy with the Bloomberg&trade; FIX connection. To deploy with Terminal Link, see <a href='/docs/v2/cloud-platform/live-trading/brokerages/bloomberg#12-Deploy-Live-Algorithms'>Deploy Live Algorithms</a>.</p>

<?
$brokerageName = "Bloomberg Fix";
$cashState = true;
$holdingsState = true;
$secondBullet = "";
$authentication = "<li>In the <span class='field-name'>Sender Sub ID</span> field, enter your QuantConnect user Id.</li>
<p>The connection sends this value as FIX tag 50 so Bloomberg&trade; can map the orders to you. To get your QuantConnect user Id, <a href='/docs/v2/cloud-platform/community/profile#09-Request-API-Token'>request an API token</a>. We email you your user Id and API token.</p>

<li>In the <span class='field-name'>On Behalf Of Comp ID</span> field, enter the CompID that identifies your trading firm.</li>
<p>Bloomberg&trade; assigns this value when they provision your FIX session. It's FIX tag 115.</p>

<li>In the <span class='field-name'>Deliver To Comp ID</span> field, enter the CompID of the prime brokerage that receives your orders.</li>
<p>It's FIX tag 128. Contact your prime brokerage if you don't know their CompID.</p>

<li>Click the <span class='field-name'>Environment</span> field and then click <span class='button-name'>Live</span> from the drop-down menu.</li>";
$postDeploy = "";
include(DOCS_RESOURCES."/live-trading/deploy-live-algorithm.php");
?>
