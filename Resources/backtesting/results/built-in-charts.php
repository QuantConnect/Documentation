<p>The backtest results page displays a set of built-in charts to help you analyze the performance of your algorithm. The following table describes the charts displayed on the page:<br></p>

<table class="qc-table table">
  <thead>
    <tr>
      <th style="width: 25%">Chart<br></th>
      <th style="width: 75%">Description</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>Strategy Equity</td>
      <td>A time series of equity and periodic returns.</td>
    </tr>
    <tr>
      <td>Capacity</td>
      <td>A time series of <a href="/docs/v2/writing-algorithms/key-concepts/glossary#08-capacity">strategy capacity</a> snapshots.</td>
    </tr>
    <tr>
      <td>Drawdown</td>
      <td>A time series of equity peak-to-trough value.</td>
    </tr>
    <tr>
      <td>Benchmark</td>
      <td>A time series of the benchmark closing price (SPY, by default).</td>
    </tr>
    <tr>
      <td>Exposure</td>
      <td>A time series of long and short exposure ratios.</td>
    </tr>
    <tr>
      <td>Assets Sales Volume</td>
      <td>A chart showing the proportion of total volume for each traded security. </td>
    </tr>
    <tr>
      <td>Portfolio Turnover</td>
      <td>A time series of the portfolio turnover rate.</td>
    </tr>
    <tr>
      <td>Portfolio Margin</td>
      <td>A stacked area chart of the portfolio margin usage. For more information about this chart, see <a href='/docs/v2/cloud-platform/backtesting/results/portfolio-margin-plots'>Portfolio Margin Plots</a>.</td>
    </tr>
    <? if ($cloudPlatform) { ?>
    <tr>
      <td>Asset Plot</td>
      <td>A time series of an asset's price with order event annotations. These charts are available for all paid organziation tiers. For more information about these charts, see <a href='/docs/v2/cloud-platform/backtesting/results#05-Asset-Plots'>Asset Plots</a>. </td>
    </tr>
    <? } ?>
  </tbody>
</table>

