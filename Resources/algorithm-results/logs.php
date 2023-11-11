<?
$logLink = $cloudPlatform ? "/docs/v2/cloud-platform/projects/debugging#04-Logging-Statements" : "/docs/v2/writing-algorithms/logging#02-Log-Messages";
?>

<p>The backtest results page displays the <a href="<?=$logLink?>">logs</a> of your backtest and you can <? echo $cloudPlatform ? "download them to your local machine" : "view them on your local machine";?>. The timestamps of the statements in the log file are based in your <a href='/docs/v2/writing-algorithms/initialization#12-Set-Time-Zone'>algorithm time zone</a>.</p>

<h4>View in the GUI</h4>
<p>To see the log file that was created throughout a backtest, open the backtest result page and then click the <span class="tab-name">Logs</span> tab.</p>

<p>To filter the logs, enter a search string in the <span class='field-name'>Filter logs</span> field.</p>
<img src='https://cdn.quantconnect.com/i/tu/cloud-backtest-log-filter.gif' class='docs-image'>

<h4>Download Log Files</h4>
<p>To download the log file that was created throughout a backtest, follow these steps:</p>
<ol>
    <li>Open the backtest result page.</li>
    <li>Click the <span class="tab-name">Logs</span> tab.</li>
    <li>Click <span class="button-name">Download Logs</span>.</li>
</ol>

<? if ($localPlatform) { ?>
<p>If you ran a local backtest, the log file is automatically saved on your local machine when the backtest completes.</p>

<h4>Access Local Log Files</h4>
<p>To view the log file of a local backtest, open the <span class='public-file-name'><a href='/docs/v2/local-platform/development-environment/organization-workspaces'>&lt;organizationWorkspace&gt;</a> / &lt;projectName&gt; / backtests / &lt;unixTimestamp&gt; / &lt;algorithmId&gt;-log.txt</span> file.</p>
<? } ?>
