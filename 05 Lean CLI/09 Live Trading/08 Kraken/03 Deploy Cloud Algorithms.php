<?php
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-cloud-algorithms.php");
$brokerageDetails = "
<li>Enter your API key and API secret. You can gather these credentials from the <a href='https://www.kraken.com/u/security/api'>API Management Settings</a> page on the Kraken website.
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
API key: 
Secret key: </pre>
</div>
</li>

<li>Enter your verification tier. For more information about verification tiers, see <a href='https://support.kraken.com/hc/en-us/articles/360001395743-Verification-levels-explained' target='_blank' rel='nofollow'>Verification levels explained</a> on the Kraken website.
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
Verification Tier:</pre>
</div>

<p>The following table shows the verification tiers of the Kraken brokerage and the corresponding number to input into the CLI:</p>
<table class='table qc-table' id='kraken-verification-tier-table'>
    <thead>
        <tr>
            <th>Number</th>
            <th>Verification Tier</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td>Starter</td>
        </tr>
        <tr>
            <td>2</td>
            <td>Intermediate</td>
        </tr>
        <tr>
            <td>3</td>
            <td>Pro</td>
        </tr>
    </tbody>
</table>
<style>
#kraken-verification-tier-table td:first-child, 
#kraken-verification-tier-table th:first-child {
    text-align: right;
}
</style>

</li>
";
$getDeployCloudAlgorithmsText("Kraken", true, $brokerageDetails);
?>
