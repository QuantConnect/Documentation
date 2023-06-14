<p>The <?=$pageName?> results page displays the insights of your algorithm and you can <?=$localPlatform ? "view the raw insight data on" : "download them to"?> your local machine.</p>

<h4>View in the GUI</h4>
<p>To see the insights your algorithm emit, open the <?=$pageName?> result page and then click the <span class='tab-name'>Insights</span> tab. If there are more than 10 insights, use the pagination tools at the bottom of the Insights Summary table to see all of the insights. The timestamps in the Insights Summary table are based in Eastern Time (ET).</p>

<h4><?=$localPlatform ? "Open Raw" : "Download"?> JSON</h4>
<p>To view the insights in JSON format, open the <?=$pageName?> result page, click the <span class='tab-name'>Insights</span> tab, and then click <span class='button-name'>Download Insights</span>. The timestamps in the CSV file are based in Coordinated Universal Time (UTC).</p>

<? if ($localPlatform) { ?>
<p>If you run a local backtest, the JSON file is also available in the following locations:</p>

<ul>
    <li>The <span class='public-file-name'>Project / backtests / &lt;unixTimestamp&gt; / &lt;algorithmId&gt;-alpha-insights.json</span> file from your <a href='/docs/v2/local-platform/backtesting/results#15-View-Local-Result-Files'>local result files</a> in Local Platform</li>
    <li>The <span class='public-file-name'>&lt;projectName&gt; / backtests / &lt;unixTimestamp&gt; / &lt;algorithmId&gt;-alpha-insights.txt</span> file from your <a href='/docs/v2/local-platform/development-environment/organization-workspaces'>organization workspace</a></li>
</ul>
<? } ?>
