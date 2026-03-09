<?
$brokerageName = "Charles Schwab";
$statusPageURL = null;
include(DOCS_RESOURCES."/brokerages/security-and-stability.php");
?>
<p>Our integration supports authenticating multiple Charles Schwab user accounts, one for each project, with one trading account. If you deploy a second algorithm for the same user, the first algorithm stops. For example, if you authenticate as <span class='field-name'>JohnDoe</span> with trading accounts <span class='field-name'>XXXXX549</span> and <span class='field-name'>XXXXX725</span>, you can deploy to only one of those. To deploy a second algorithm, you must authenticate as a different user, for example <span class='field-name'>RichardRoe</span>, and select trading accounts associated with it.</p>