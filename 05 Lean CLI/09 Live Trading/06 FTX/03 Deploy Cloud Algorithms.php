<?php
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-cloud-algorithms.php");
$brokerageDetails = "
<li>Enter the FTX exchange to use.
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
FTX Exchange [FTX|FTXUS]:</pre>
</div>
</li>

<li>Enter your API key and API secret. You can create a new API key on your Profile page on the <a href='https://ftx.com/profile' target='_blank' rel='nofollow'>FTX</a> or <a href='https://ftx.us/profile' target='_blank' rel='nofollow'>FTX US</a> website.
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
API key: 
Secret key: </pre>
</div>
</li>

<li>Enter your account tier. You can get your account tier from your Profile page on the <a href='https://ftx.com/profile' target='_blank'>FTX</a> or <a href='https://ftx.us/profile' target='_blank'>FTX US</a> website. If your account tier changes after you deploy the algorithm, stop the algorithm and then redeploy it to correct the account tier.
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
Account Tier: </pre>
</div>
<p>The following table shows the account tiers of the FTX and FTX US brokerages and the corresponding number to input into the CLI:</p>
<table class='table qc-table' id='ftx-account-tier-table'>
    <thead>
        <tr>
            <th>Number</th>
            <th>FTX Tier</th>
            <th>FTX US Tier</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td>Tier 1</td>
            <td>Tier 1</td>
        </tr>
        <tr>
            <td>2</td>
            <td>Tier 2</td>
            <td>Tier 2</td>
        </tr>
        <tr>
            <td>3</td>
            <td>Tier 3</td>
            <td>Tier 3</td>
        </tr>
        <tr>
            <td>4</td>
            <td>Tier 4</td>
            <td>Tier 4</td>
        </tr>
        <tr>
            <td>5</td>
            <td>Tier 5</td>
            <td>Tier 5</td>
        </tr>
        <tr>
            <td>6</td>
            <td>Tier 6</td>
            <td>Tier 6</td>
        </tr>
        <tr>
            <td>7</td>
            <td>VIP 1</td>
            <td>Tier 7</td>
        </tr>
        <tr>
            <td>8</td>
            <td>VIP 2</td>
            <td>Tier 8</td>
        </tr>
        <tr>
            <td>9</td>
            <td>VIP 3</td>
            <td>Tier 9</td>
        </tr>
        <tr>
            <td>10</td>
            <td>MM 1</td>
            <td>VIP 1</td>
        </tr>
        <tr>
            <td>11</td>
            <td>MM 2</td>
            <td>VIP 2</td>
        </tr>
        <tr>
            <td>12</td>
            <td>MM 3</td>
            <td>MM 1</td>
        </tr>
        <tr>
            <td>13</td>
            <td></td>
            <td>MM 2</td>
        </tr>
        <tr>
            <td>14</td>
            <td></td>
            <td>MM 3</td>
        </tr>
    </tbody>
</table>

<style>
#ftx-account-tier-table td:first-child, 
#ftx-account-tier-table th:first-child {
    text-align: right;
}
</style>

</li>
";
$getDeployCloudAlgorithmsText("FTX", true, $brokerageDetails);
?>
