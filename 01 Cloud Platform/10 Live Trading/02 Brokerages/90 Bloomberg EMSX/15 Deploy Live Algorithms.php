<p>You need to <a href='/docs/v2/cloud-platform/live-trading/brokerages/bloomberg-emsx#14-Set Up SAPI'>set up the Bloomberg SAPI</a> before you can deploy cloud algorithms with Terminal Link.</p>

<?
$brokerageName = "Terminal Link";
$cashState = true;
$holdingsState = true;
$secondBullet = "";
$authentication = "<li>Click the <span class='field-name'>Connection Type</span> field and then click <span class='button-name'>SAPI</span> from the drop-down menu.</li>
<li>In the <span class='field-name'>Server Auth Id</span> field, enter your unique user identifier (UUID).</li>
<p>The UUID is a unique integer identifier that's assigned to each Bloomberg Anywhere user. If you don't know your UUID, contact Bloomberg.</p>

<li>In the <span class='field-name'>EMSX Broker</span> field, enter the EMSX broker to use.</li>

<li>In the <span class='field-name'>Server Port</span> field, enter the port where SAPI is listening.</li>
<p>The default port is 8194.</p>

<li>In the <span class='field-name'>Server Host</span> field, enter the public IP address of the SAPI AWS server.</li>

<li>In the <span class='field-name'>EMSX Account</span> field, enter the account to which LEAN should route orders.</li>

<li>In the <span class='field-name'>OpenFIGI Api Key</span> field, enter your API key.</li>";
$postDeploy = "";
include(DOCS_RESOURCES."/live-trading/deploy-live-algorithm.php");
?>
