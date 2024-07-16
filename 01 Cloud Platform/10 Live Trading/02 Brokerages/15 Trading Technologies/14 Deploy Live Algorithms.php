<?
$brokerageName = "Trading Technologies";
$cashState = true;
$holdingsState = false;
$secondBullet = "";
$authentication = "<li>Enter your TT user name, account name, routing sender, session password, app key, and app secret.</li>
<p>Our TT integration routes orders via the TT FIX 4.4 Connection. <a rel='nofollow' target='_blank' href='https://www.tradingtechnologies.com/contact/'>Contact your TT representative</a> to set the exchange where you would like your orders sent. Your account details are not saved on QuantConnect.<br></p>
<p>Our integration fetches your positions using the REST endpoint, so the app key and app secret are your REST App credentials.</p>
<li>Click the <span class='field-name'>Environment</span> field and then click one of the environments from the drop-down menu.</li>

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
            <td>Trade in the production environment</td>
        </tr>
        <tr>
            <td>UAT</td>
            <td>Trade in the <a rel='nofollow' target='_blank' href='https://library.tradingtechnologies.com/trade/ttp-uat-trading-environment.html'>User Acceptance Testing</a> environment</td>
        </tr>
    </tbody>
</table>";
$postDeploy = "";
include(DOCS_RESOURCES."/live-trading/deploy-live-algorithm.php");
?>