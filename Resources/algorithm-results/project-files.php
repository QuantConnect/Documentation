<p>
  The <?=$pageName?> results page displays the project files used to <?=($pageName == "live") ? "deploy the algorithm" : "run the backtest" ?>. To view the files, click the <span class='tab-name'>Code</span> tab. By default, the <span class='public-file-name'>main.py</span> or <span class='public-file-name'>Main.cs</span> file displays. To view other files in the project, click the file name and then select a different file from the drop-down menu.
</p>

<img class='docs-image' alt="Algorithm code snippets" src='<?=($pageName == "live") ? "https://cdn.quantconnect.com/i/tu/live-project-files.png" : "https://cdn.quantconnect.com/i/tu/backtest-results-project-files.png" ?>'>

<? if ($localPlatform) { ?>
<p>If you ran a local backtest, the project files are also available in the following locations:</p>
<ul>
    <li>The <span class='public-file-name'>Project / backtests / &lt;unixTimestamp&gt; / code</span> directory from your <a href='/docs/v2/local-platform/backtesting/results#15-View-Local-Result-Files'>local result files</a> in VS Code.</li>
    <li>The <span class='public-file-name'>&lt;projectName&gt; / backtests / &lt;unixTimestamp&gt; / code</span> directory in your <a href='/docs/v2/local-platform/development-environment/organization-workspaces'>organization workspace</a>.</li>
</ul>
<? } ?>