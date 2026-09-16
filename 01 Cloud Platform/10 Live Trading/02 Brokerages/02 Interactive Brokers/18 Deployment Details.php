<?
$brokerageName = "Interactive Brokers";
$deploymentDetails = [
    "ib-account" => "The IB account that receives the orders.",
    "ib-user-name" => "The user name that LEAN uses to log in to IB.",
    "ib-trading-mode" => "The trading mode of the account, which is <code>live</code> or <code>paper</code>.",
    "ib-financial-advisors-group-filter" => "The Financial Advisor group that LEAN trades. It's empty if the deployment trades the whole account.",
    "ib-fix-account" => "The IB account that receives the orders. LEAN shares this detail instead of the preceding ones when you deploy with the FIX connection.",
    "ib-fix-user-name" => "The user name of the FIX session."
];
include(DOCS_RESOURCES."/live-trading/deployment-details.php");
?>
