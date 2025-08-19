<?
$brokerageName = "tastytrade";
$statusPageURL = null;
include(DOCS_RESOURCES."/brokerages/security-and-stability.php");
?>
<p>tastytrade only supports authenticating one account at a time per user. If you have an algorithm running with tastytrade and then deploy a second one, the first algorithm stops running.</p>
