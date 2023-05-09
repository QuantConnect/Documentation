<p>
    The Lean configuration contains settings for locally running the LEAN engine.
    This configuration is created in the <span class="public-file-name">lean.json</span> file when you <?= $leanCli ? "run <code>lean init</code> in an empty directory" : "pull or create an <a href='/docs/v2/local-platform/development-environment/organization-workspaces'>organization workspace</a>"?>.
    The configuration is stored as JSON, with support for both single-line and multiline comments.
</p>

<p>
    The Lean configuration file is based on the <a class="public-file-name" href="https://github.com/QuantConnect/Lean/blob/master/Launcher/config.json" target="_blank">Launcher / config.json</a> file from the Lean GitHub repository.
    When you <?= $leanCli ? "run <code>lean init</code>" : "pull or create an organization workspace"?>, the latest version of this file is downloaded and stored in your organization workspace.
    Before the file is stored, some properties are automatically removed because the <?= $leanCli ? "CLI" : "Local Platform"?> automatically sets them.
</p>
<p>The <?= $leanCli ? "CLI commands" : "Local Platform"?> can update most of the values of the <span class="public-file-name">lean.json</span> file. The following table shows the configuration settings that you need to manually adjust in the <span class="public-file-name">lean.json</span> file if you want to change their values:</p>

<table class="qc-table table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th nowrap>Default</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td nowrap><code>show-missing-data-logs</code></td>
            <td>Log missing data files. This is useful for debugging.</td>
            <td>true</td>
        </tr>
        <tr>
            <td nowrap><code>maximum-warmup-history-days-look-back</code></td>
            <td>The maximum number of days of data the history provider will provide during <a href='https://www.quantconnect.com/docs/v2/writing-algorithms/historical-data/warm-up-periods'>warm-up</a> in live trading. The history provider expects older data to be on disk.</td>
            <td>5</td>
        </tr>
        <tr>
            <td nowrap><code>maximum-data-points-per-chart-series</code></td>
            <td>The maximum number of data points you can add to a chart series in backtests.</td>
            <td>4000</td>
        </tr>
    </tbody>
</table>
