<? include(DOCS_RESOURCES."/backtesting/results/create-report.php"); ?>

<p>If you create a report for a local backtest, the report is stored in the <span class='public-file-name'><a href='/docs/v2/local-platform/development-environment/organization-workspaces'>&lt;organizationWorkspace&gt;</a> / &lt;projectName&gt; / backtests / &lt;unixTimestamp&gt;</span> directory as <span class='public-file-name'>report.html</span> and <span class='public-file-name'>report.pdf</span>.</p>

<? 
$addHTMLFileInstructions = "<a href='/docs/v2/local-platform/projects/files#04-Add-Files'>add a <span class='public-file-name'>report.html</span> file to your project</a>";
$addCSSFileInstructions = "<a href='/docs/v2/local-platform/projects/files#04-Add-Files'>add a <span class='public-file-name'>report.css</span> file to your project</a>";
include(DOCS_RESOURCES."/backtesting/results/customize-report.php"); 

include(DOCS_RESOURCES."/backtesting/results/customize-report.php"); 
?>
