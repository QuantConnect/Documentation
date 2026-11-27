<?
$brokerageName = "Trading Technologies";
$deploymentDetails = [
    "tt-user-name" => "The TT user name that LEAN uses to log in.",
    "tt-account-name" => "The TT account that receives the orders.",
    "tt-rest-environment" => "The TT REST environment that LEAN uses to fetch positions and instrument details.",
    "tt-order-routing-sender-comp-id" => "The sender comp ID of the FIX order routing session."
];
include(DOCS_RESOURCES."/live-trading/deployment-details.php");
?>
