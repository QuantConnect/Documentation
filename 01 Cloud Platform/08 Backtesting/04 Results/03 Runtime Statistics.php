<p>The banner at the top of the backtest results page displays the runtime statistics of your backtest.</p>

<img class='docs-image' src="https://cdn.quantconnect.com/i/tu/runtime-statistics-live-1.png" alt="Backtest runtime statistics">

<? include(DOCS_RESOURCES."/algorithm-results/runtime-statistics-table.php"); ?>

<p>To add your own runtime statistics, see <a href='/docs/v2/writing-algorithms/statistics/runtime-statistics#03-Add-Statistics'>Add Statistics</a>. Custom statistics appear in the banner, and you can <a href='/docs/v2/cloud-platform/backtesting/results#17-Download-Results'>download</a> them with the rest of the results. LEAN keeps at most 50 runtime statistics, truncates names and values to 200 characters, and ignores encoded values such as base64 or hexadecimal strings. When it ignores a statistic, it sends one debug message to the algorithm.</p>
