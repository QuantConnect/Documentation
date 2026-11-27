<p>You need to <a href='https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/bloomberg-emsx#11-Set-Up-EMSX-API-Server'>set up the Bloomberg&trade; EMSX API Server</a> before you can deploy cloud algorithms with Quant Bridge.</p>

<?
$brokerageDetails = "
<li>Enter your unique user identifier (UUID).</li>
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
Configure credentials for Quant Bridge
Using 'SAPI' Connection Type
Server Auth ID: </pre>
</div>
<p>The UUID is a unique integer identifier that's assigned to each Bloomberg Anywhere user. If you don't know your UUID, contact Bloomberg.</p>

<li>Enter the environment to use.</li>
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
Environment (Production, Beta): </pre>
</div>

<li>Enter the EMSX API Server host and port.</li>
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
Server host: 
Server port:  </pre>
</div>
<p>The default port is 8194.</p>

<li>Enter the EMSX broker to use.</li>
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
EMSX broker: </pre>
</div>

<li>Enter the account to which LEAN should route orders.</li>
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
EMSX account []: </pre>
</div>

<li>Enter your OpenFIGI API key.</li>
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
OpenFIGI API key: </pre>
</div>
";
$dataProviderName = "Quant Bridge";
$brokerageName="Quant Bridge";
$isSupported=true;
$supportsCashHoldings=false;
$supportsPositionHoldings=false;
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-cloud-algorithms.php");
?>
