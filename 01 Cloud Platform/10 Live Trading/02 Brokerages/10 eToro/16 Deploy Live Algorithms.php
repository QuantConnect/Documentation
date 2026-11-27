<?
$brokerageName = "eToro";
$cashState = false;
$holdingsState = false;
$secondBullet = "";
$authentication = "<li>Click on the <span class='field-name'>Environment</span> field and then click one of the environments.</li>
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
            <td>Demo</td>
            <td>Trade with paper money</td>
        </tr>
        <tr>
            <td>Real</td>
            <td>Trade with real money</td>
        </tr>
    </tbody>
</table>
<li>Enter your eToro API key and user key for the environment you selected.</li>" . file_get_contents(DOCS_RESOURCES."/brokerages/create-credentials/etoro.html");
$postDeploy = "";
include(DOCS_RESOURCES."/live-trading/deploy-live-algorithm.php");
?>
