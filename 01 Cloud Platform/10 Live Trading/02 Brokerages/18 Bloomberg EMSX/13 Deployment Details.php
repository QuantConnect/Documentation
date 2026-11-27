<?
$brokerageName = "Quant Bridge";
$deploymentDetails = [
    "quant-bridge-server-auth-id" => "The Server Auth Id, which is the unique user identifier (UUID) of the Bloomberg Anywhere user.",
    "quant-bridge-emsx-broker" => "The EMSX broker that receives the orders.",
    "quant-bridge-emsx-account" => "The EMSX account to which LEAN routes orders.",
    "quant-bridge-emsx-team" => "The EMSX team account that receives events of your team's orders. It's empty if you didn't set one."
];
include(DOCS_RESOURCES."/live-trading/deployment-details.php");
?>
