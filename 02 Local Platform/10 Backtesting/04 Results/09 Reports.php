<? include(DOCS_RESOURCES."/backtesting/results/create-report.php"); ?>

<p>If you create a report for a local backtest, the report is stored in the <span class='public-file-name'><a href='/docs/v2/local-platform/development-environment/organization-workspaces'>&lt;organizationWorkspace&gt;</a> / &lt;projectName&gt; / backtests / &lt;unixTimestamp&gt;</span> directory as <span class='public-file-name'>report.html</span> and <span class='public-file-name'>report.pdf</span>.</p>

<? 
$addHTMLFileInstructions = "<a href='/docs/v2/local-platform/projects/files#04-Add-Files'>add a <span class='public-file-name'>report.html</span> file to your project</a>";
$addCSSFileInstructions = "<a href='/docs/v2/local-platform/projects/files#04-Add-Files'>add a <span class='public-file-name'>report.css</span> file to your project</a>";
$crisisEventLink = "/docs/v2/cloud-platform/backtesting/report#08-Crisis-Events";
include(DOCS_RESOURCES."/backtesting/results/customize-report.php"); 
?>

<h4>Custom Report Example</h4>

<p>
  To view an example of <span class='public-file-name'>report.html</span> and <span class='public-file-name'>report.css</span> files that customize the backtest reports of a project, see the files in <a href='https://www.quantconnect.cloud/backtest/92562921df687db3fc5f28f48826405c/?theme=darkly' target='_blank'>this project</a>.
  The HTML and CSS files in the project produce a report that has a red banner at the top.
</p>

