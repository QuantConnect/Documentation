<p>The backtest results page displays many key statistics to help you analyze the performance of your algorithm.</p> 

<h4>Overall Statistics</h4>
<p>The <span class="tab-name">Overview</span> tab on the backtest results page displays tables for Overall Statistics and Rolling Statistics. The Overall Statistics table displays the following statistics:<br></p>

<ul style="columns: 2">
    <li><a href="/docs/v2/writing-algorithms/key-concepts/glossary#21-Probabilistic-Sharpe-ratio">Probabilistic Sharpe Ratio (PSR)</a></li>
    <li>Total Trades</li>
    <li><a href="/docs/v2/writing-algorithms/key-concepts/glossary#05-average-loss">Average Loss</a></li>
    <li><a href="/docs/v2/writing-algorithms/key-concepts/glossary#11-drawdown">Drawdown</a></li>
    <li><a href="/docs/v2/writing-algorithms/key-concepts/glossary#19-net-profit">Net Profit</a></li>
    <li><a href="/docs/v2/writing-algorithms/key-concepts/glossary#17-loss-rate">Loss Rate</a></li>
    <li><a href="/docs/v2/writing-algorithms/key-concepts/glossary#22-profit-loss-ratio">Profit-Loss Ratio</a></li>
    <li><a href="/docs/v2/writing-algorithms/key-concepts/glossary#07-beta">Beta</a></li>
    <li><a href="/docs/v2/writing-algorithms/key-concepts/glossary#04-annual-variance">Annual Variance</a></li>
    <li><a href="/docs/v2/writing-algorithms/key-concepts/glossary#28-tracking-error">Tracking Error</a></li>
    <li><a href="/docs/v2/writing-algorithms/key-concepts/glossary#25-total-fees">Total Fees</a></li>
    <li><a href="/docs/v2/writing-algorithms/key-concepts/glossary#18-lowest-capacity-asset">Lowest Capacity Asset</a></li>

    <li><a href="/docs/v2/writing-algorithms/key-concepts/glossary#24-Sharpe-ratio">Sharpe Ratio</a></li>
    <li><a href="/docs/v2/writing-algorithms/key-concepts/glossary#06-average-win">Average Win</a></li>
    <li><a href="/docs/v2/writing-algorithms/key-concepts/glossary#09-compounding-annual-return">Compounding Annual Return</a></li>
    <li><a href="/docs/v2/writing-algorithms/key-concepts/glossary#13-expectancy">Expectancy</a></li>
    <li><a href="/docs/v2/writing-algorithms/key-concepts/glossary#32-win-rate">Win Rate</a></li>
    <li><a href="/docs/v2/writing-algorithms/key-concepts/glossary#02-alpha">Alpha</a></li>
    <li><a href="/docs/v2/writing-algorithms/key-concepts/glossary#03-annual-standard-deviation">Annual Standard Deviation</a></li>
    <li><a href="/docs/v2/writing-algorithms/key-concepts/glossary#15-information-ratio">Information Ratio</a></li>
    <li><a href="/docs/v2/writing-algorithms/key-concepts/glossary#29-Treynor-ratio">Treynor Ratio</a></li>
    <li><a href="/docs/v2/writing-algorithms/key-concepts/glossary#08-capacity">Estimated Strategy Capacity</a></li>
</ul>

<p>Some of the preceding statistics are sampled throughout the backtest to produce a time series of rolling statistics. The time series are displayed in the Rolling Statistics table.</p>

<? if ($localPlatform) { ?>
<p>To view the data from the Overall Statistics and Rolling Statistics tables in JSON format, see <a href='/docs/v2/local-platform/backtesting/results#15-View-Result-Files'>View Result Files</a>.</p>
<? } else { ?>
<p>To download the data from the Overall Statistics and Rolling Statistics tables, see <a href='/docs/v2/cloud-platform/backtesting/results#15-Download-Results'>Download Results</a>.</p>
<? } ?>


<h4>Ranking</h4>
<? if ($cloudPlatform) { ?>
<p>The backtest results page displays a Ranking section that shows the PSR and rank (percentile) of your algorithm.</p>

<img class="docs-image" src="https://cdn.quantconnect.com/i/tu/algorithm-ranking.png" alt="Backtest ranking">

<p>The rank of your algorithm is calculated as</p>

$$
CDF\left(\frac{PSR_{algo} - \overline{PSR}}{\sigma_{PSR}}\right)
$$

<p>where $CDF$ is the normal cumulative distribution function and $PSR_{algo}$ is your algorithm's PSR. $\overline{PSR}$ and $\sigma_{PSR}$ are the mean PSR and the standard deviation of PSR values, respectively, calculated from all of the backtests that have the following attributes:</p>
<ul>
    <li>Occurred in the last 30 days</li>
    <li>Had more than 90 tradable days</li>
    <li>Had a PSR value in the interval (0, 100)</li>
</ul>
<? } else { ?>
<p>If you run a cloud backtest, backtest results page displays a Ranking section that shows the PSR and rank (percentile) of your algorithm. For more information about this section, see <a href='/docs/v2/cloud-platform/backtesting/results#08-Key-Statistics'>Key Statistics</a>.</p>
<? } ?>

<h4>Research Guide</h4>
<p>For information about the Research Guide, see <a href="/docs/v2/cloud-platform/backtesting/research-guide">Research Guide</a>.
</p>

<script type="text/x-mathjax-config">
    MathJax.Hub.Config({tex2jax: {inlineMath: [['$','$'], ['\\(','\\)']]}});
</script>
<script type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=TeX-AMS-MML_HTMLorMML">
</script>
