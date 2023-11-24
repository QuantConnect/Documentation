<?
$brokerageName = "Coinbase";
$cashState = false;
$holdingsState = false;
$secondBullet = "";
$authentication = "<li>Enter your Coinbase API key, API secret, and passphrase.</li>" .
file_get_contents(DOCS_RESOURCES."/brokerages/create-credentials/coinbase.html") .
"<li>Click on the <span class='field-name'>Environment</span> field and then click one of the environments.</li>
<p>The following table shows the supported environments:</p>

<table class='table qc-table'>
    <thead>
        <tr>
            <th>Environment</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Live</td>
            <td>Trade with real money</td>
        </tr>
        <tr>
            <td>Paper</td>
            <td>Trade with paper money</td>
        </tr>
    </tbody>
</table>";
$postDeploy = "";
include(DOCS_RESOURCES."/live-trading/deploy-live-algorithm.php");
?>