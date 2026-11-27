<?
$brokerageName = "Bloomberg Fix";
$deploymentDetails = [
    "bloomberg-fix-sender-sub-id" => "The Sender Sub ID that identifies you in the FIX session, which is FIX tag 50.",
    "bloomberg-fix-on-behalf-of-comp-id" => "The On Behalf Of Comp ID that identifies your trading firm, which is FIX tag 115.",
    "bloomberg-fix-deliver-to-comp-id" => "The Deliver To Comp ID of the prime brokerage that receives the orders, which is FIX tag 128."
];
include(DOCS_RESOURCES."/live-trading/deployment-details.php");
?>
