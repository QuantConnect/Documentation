<p>You need to <a href='/docs/v2/cloud-platform/live-trading/brokerages/bloomberg-emsx#11-Set-Up-EMSX-API-Server'>set up the Bloomberg&trade; EMSX API Server</a> before you can deploy cloud algorithms with Quant Bridge.</p>

<?
$brokerageName = "Quant Bridge";
$cashState = true;
$holdingsState = true;
$secondBullet = "";
$authentication = "<li>Click the <span class='field-name'>Connection Type</span> field and then click <span class='button-name'>SAPI</span> from the drop-down menu.</li>
<li>In the <span class='field-name'>Server Auth Id</span> field, enter your unique user identifier (UUID).</li>
<p>The UUID is a unique integer identifier that's assigned to each Bloomberg Anywhere user. If you don't know your UUID, contact Bloomberg.</p>

<li>In the <span class='field-name'>EMSX Broker</span> field, enter the EMSX broker to use.</li>

<li>In the <span class='field-name'>Server Port</span> field, enter the port where the EMSX API Server is listening.</li>
<p>The default port is 8194.</p>

<li>In the <span class='field-name'>Server Host</span> field, enter the public IP address of the AWS instance that runs the EMSX API Server.</li>

<li>In the <span class='field-name'>EMSX Account</span> field, enter the account to which LEAN should route orders.</li>

<li>In the <span class='field-name'>EMSX Team</span> field, enter the team account to receive events of your team's orders.</li>
<p>The default value is empty, which means LEAN disregards these notifications.</p>

<li>In the <span class='field-name'>OpenFIGI Api Key</span> field, enter your API key.</li>

<li>Click the <span class='field-name'>Environment</span> field and then click one of the options from the drop-down menu.</li>";
$postDeploy = "";
include(DOCS_RESOURCES."/live-trading/deploy-live-algorithm.php");
?>
