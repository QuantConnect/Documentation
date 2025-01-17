<p>If you need to adjust your algorithm's project files or <a href=<?=$editParameterLink?>>parameter values</a>, stop your algorithm, make your changes, and then redeploy your algorithm. You can't adjust your algorithm's code or parameter values while your algorithm executes.</p>
<? 
if ($cloudPlatform)
    include(DOCS_RESOURCES."/algorithm-results/clear-history.php"); 
include(DOCS_RESOURCES."/live-trading/parameters-live-update.php"); ?>