<?
$brokerageName = "<a rel='nofollow' target='_blank' href='https://qnt.co/ssc-eze'>SS&C Eze</a>";
$dataProviderName=$brokerageName;
$brokerageDetails = "<li>Enter the trading domain, region, username, password, and trading account in BBCD (BANK;BRANCH;CUSTOMER;DEPOSIT) format.
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
Provided by brokerage: trading domain: LIGHTSPEEDDELAY
Provided by brokerage: locale/market region: Live and Delayed
Username:
Account password:
Enter your trading account (BBCD format): LATEST;TEST;01;ACQUIREDMEDIA
</pre>
</div>
</li>";

$dataProviderDetails = "";
$isSupported=true;
$supportsCashHoldings=true;
$supportsPositionHoldings=true;
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-cloud-algorithms.php");
?>