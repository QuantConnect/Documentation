<p>To view the results files of local backtests, <a href='/docs/v2/local-platform/backtesting/getting-started#02-Run-Backtests'>run a local backtest</a> and then open the <span class='public-file-name'><a href='/docs/v2/local-platform/development-environment/organization-workspaces'>&lt;organizationWorkspace&gt;</a> / &lt;projectName&gt; / backtests / &lt;unixTimestamp&gt;</span> directory. The following table describes the initial contents of the backtest result directories:</p>

<table class="qc-table table">
    <thead>
        <tr>
            <th>File/Directory</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><span class='public-directory-name'>code /</span></td>
            <td>A directory containing a copy of the files that were in the project when you ran the backtest.</td>
        </tr>
        <tr>
            <td>
            	<span class='public-file-name'>&lt;backtestId&gt;-alpha-results.json</span>
            	<br>Ex: <span class='public-file-name'>1967791529-alpha-results.json</span>
            </td>
            <td>A file containing all of the <a href='/docs/v2/local-platform/backtesting/results#11-Insights'>backtest insights</a>. This file only exists if you emit insights during the backtest.</td>
        </tr>
        <tr>
            <td>
            	<span class='public-file-name'>&lt;backtestId&gt;-log.txt</span>
            	<br>Ex: <span class='public-file-name'>1967791529-log.txt</span>
            </td>
            <td>A file containing all of the <a href='/docs/v2/local-platform/backtesting/results#12-Logs'>backtest logs</a>.</td>
        </tr>
        <tr>
            <td>
            	<span class='public-file-name'>&lt;backtestId&gt;-order-events.json</span>
            	<br>Ex: <span class='public-file-name'>1967791529-order-events.json</span>
            </td>
            <td>A file containing all of the backtest <a href='/docs/v2/writing-algorithms/trading-and-orders/order-events'>order events</a>.</td>
        </tr>
        <tr>
            <td>
            	<span class='public-file-name'>&lt;backtestId&gt;.json</span>
            	<br>Ex: <span class='public-file-name'>1967791529.json</span>
            </td>
            <td>
            	A file containing the following data:
            	<ul>
                    <li><a href='/docs/v2/local-platform/backtesting/results#03-Runtime-Statistics'>Runtime statistics</a></li>
                    <li><a href='/docs/v2/local-platform/backtesting/results#04-Built-in-Charts'>Charts</a></li>
                    <li>The data in the <a href='/docs/v2/local-platform/backtesting/results#08-Key-Statistics'><span class='tab-name'>Overview</span></a> tab</li>
                    <li>The data in the <a href='/docs/v2/local-platform/backtesting/results#10-Orders'><span class='tab-name'>Orders</span></a> tab</li>
                    <li>The algorithm configuration settings</li>
                </ul>
            </td>
        </tr>
        <tr>
            <td><span class='public-file-name'>config</span></td>
            <td>A file containing some configuration settings, including the backtest Id, Docker container name, and backtest name.</td>
        </tr>
        <tr>
            <td>
            	<span class='public-file-name'>data-monitor-report-&lt;backtestDate&gt;&lt;unixTimestamp&gt;.json</span>
            	<br>Ex: <span class='public-file-name'>data-monitor-report-20230614155459950.json</span>
            </td>
            <td>A file containing statistics on the algorithm's data requests.</td>
        </tr>
        <tr>
            <td>
            	<span class='public-file-name'>failed-data-requests-&lt;backtestDate&gt;&lt;unixTimestamp&gt;.txt</span>
            	<br>Ex: <span class='public-file-name'>failed-data-requests-20230614155451004.txt</span>
            </td>
            <td>A file containing all the local data paths that LEAN failed to load during the backtest.</td>
        </tr>
        <tr>
            <td><span class='public-file-name'>log.txt</span></td>
            <td>A file containing the syslog.</td>
        </tr>
        <tr>
            <td>
            	<span class='public-file-name'>succeeded-data-requests-&lt;backtestDate&gt;&lt;unixTimestamp&gt;.txt</span>
            	<br>Ex: <span class='public-file-name'>succeeded-data-requests-20230614155451004.txt</span>
            </td>
            <td>A file containing all the local data paths that LEAN successfully loaded during the backtest.</td>
        </tr>
    </tbody>
</table>

<p>The backtest result directories can contain the following additional files if you request them:</p>

<table class="qc-table table">
    <thead>
        <tr>
            <th>File</th>
            <th>Description</th>
            <th>Request Procedure</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><span class='public-directory-name'>orders.csv</span></td>
            <td>A file containing all of the data from the <a href='/docs/v2/local-platform/backtesting/results#10-Orders'>Orders table</a> when the table rows are collapsed.</td>
            <td>See <a href='/docs/v2/local-platform/backtesting/results#10-Orders'>Orders</a></td>
        </tr>
        <tr>
            <td><span class='public-directory-name'>report.html</span> and <span class='public-directory-name'>report.pdf</span></td>
            <td>A file containing the <a href='/docs/v2/cloud-platform/backtesting/report'>backtest report</a></td>
            <td>See <a href='/docs/v2/local-platform/backtesting/results#09-Reports'>Reports</a></td>
        </tr>
    </tbody>
</table>
