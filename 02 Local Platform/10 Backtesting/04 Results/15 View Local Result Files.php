<p>Follow these steps to view the results files of local backtests:</p>

<ol>
	<li><a href='/docs/v2/local-platform/backtesting/getting-started#02-Run-Backtests'>Run a local backtest</a>.</li>
	<li>Navigate to <a href='/docs/v2/local-platform/projects/files#03-View-Files'>the files in your project</a>.</li>
	<li>Open one of <span class='public-file-name'>Project / backtests / <span class='placeholder-text'>&lt;unixTimestamp&gt;</span> directories in your project.</li>
	<p>For example, <span class='public-file-name'>Project / backtests / 168669292409</span>.</p>
</ol>

<p>The following table describes the initial contents of the backtest result directories:</p>

<table class="qc-table table" id='backtesting-nodes-table'>
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
            	<span class='public-file-name'>&lt;algorithmId&gt;-alpha-results.json</span>
            	<br>Ex: <span class='public-file-name'>1967791529-alpha-results.json</span>
            </td>
            <td>A file containing all of the backtest insights. This file only exists if you emit insights during the backtest.</td>
        </tr>
        <tr>
            <td>
            	<span class='public-file-name'>&lt;algorithmId&gt;-log.txt</span>
            	<br>Ex: <span class='public-file-name'>1967791529-log.txt</span>
            </td>
            <td>A file containing all of the backtest logs.</td>
        </tr>
        <tr>
            <td>
            	<span class='public-file-name'>&lt;algorithmId&gt;-order-events.json</span>
            	<br>Ex: <span class='public-file-name'>1967791529-order-events.json</span>
            </td>
            <td>A file containing all of the backtest <a href='/docs/v2/writing-algorithms/trading-and-orders/order-events'>order events</a>.</td>
        </tr>
        <tr>
            <td>
            	<span class='public-file-name'>&lt;algorithmId&gt;.json</span>
            	<br>Ex: <span class='public-file-name'>1967791529.json</span>
            </td>
            <td>
            	A file containing the following data:
            	<ul>
				    <li>Runtime statistics</li>
				    <li>Charts</li>
				    <li>The data in the <span class='tab-name'>Overview</span> tab</li>
				    <li>The data in the <span class='tab-name'>Orders</span> tab</li>
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

<table class="qc-table table" id='backtesting-nodes-table'>
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
            <td>A file containing all of the data from the Order table when the table rows are collapsed.</td>
            <td>See <a href='/docs/v2/local-platform/backtesting/results#10-Orders'>Reports</a></td>
        </tr>
        <tr>
            <td><span class='public-directory-name'>report.html</span> and <span class='public-directory-name'>report.pdf</span></td>
            <td>A file containing the <a href='/docs/v2/cloud-platform/backtesting/report'>backtest report</a></td>
            <td>See <a href='/docs/v2/local-platform/backtesting/results#09-Reports'>Reports</a></td>
        </tr>
    </tbody>
</table>