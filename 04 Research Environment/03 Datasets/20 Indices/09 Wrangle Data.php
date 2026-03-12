<?
$assetClass = 'Index';
$singularAssetClass = 'Index';
$pluralAssetClass = 'Indices';
$historicalDataLink = "/docs/v2/research-environment/datasets/indices#04-Get-Historical-Data";
$primarySymbolPy = 'spx';
$primarySymbolC = 'spx';
$primaryTicker = 'SPX';
$secondarySymbol = 'vix';

$dataFrameImages = array();

$dataFrameImages[0] = <<<'HTML'
<div class="python dataframe-wrapper">
<table class="dataframe python" border="0">
  <thead>
    <tr style="text-align: right;">
      <th></th>
      <th></th>
      <th>close</th>
      <th>high</th>
      <th>low</th>
      <th>open</th>
    </tr>
    <tr>
      <th>symbol</th>
      <th>time</th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th rowspan="5" valign="top">VIX 31</th>
      <th>2021-01-04 02:16:00</th>
      <td>23.02</td>
      <td>23.04</td>
      <td>23.02</td>
      <td>23.04</td>
    </tr>
    <tr>
      <th>2021-01-04 02:17:00</th>
      <td>23.04</td>
      <td>23.05</td>
      <td>23.02</td>
      <td>23.02</td>
    </tr>
    <tr>
      <th>2021-01-04 02:18:00</th>
      <td>23.07</td>
      <td>23.08</td>
      <td>23.05</td>
      <td>23.05</td>
    </tr>
    <tr>
      <th>2021-01-04 02:19:00</th>
      <td>23.03</td>
      <td>23.05</td>
      <td>23.03</td>
      <td>23.05</td>
    </tr>
    <tr>
      <th>2021-01-04 02:20:00</th>
      <td>23.05</td>
      <td>23.05</td>
      <td>23.04</td>
      <td>23.04</td>
    </tr>
    <tr>
      <th>...</th>
      <th>...</th>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
    </tr>
    <tr>
      <th rowspan="5" valign="top">SPX 31</th>
      <th>2021-01-29 15:16:00</th>
      <td>3714.24</td>
      <td>3714.24</td>
      <td>3714.24</td>
      <td>3714.24</td>
    </tr>
    <tr>
      <th>2021-01-29 15:17:00</th>
      <td>3714.24</td>
      <td>3714.24</td>
      <td>3714.24</td>
      <td>3714.24</td>
    </tr>
    <tr>
      <th>2021-01-29 15:18:00</th>
      <td>3714.24</td>
      <td>3714.24</td>
      <td>3714.24</td>
      <td>3714.24</td>
    </tr>
    <tr>
      <th>2021-01-29 15:19:00</th>
      <td>3714.24</td>
      <td>3714.24</td>
      <td>3714.24</td>
      <td>3714.24</td>
    </tr>
    <tr>
      <th>2021-01-29 15:20:00</th>
      <td>3714.24</td>
      <td>3714.24</td>
      <td>3714.24</td>
      <td>3714.24</td>
    </tr>
  </tbody>
</table>
</div>
HTML;

$dataFrameImages[1] = <<<'HTML'
<div class="python dataframe-wrapper">
<table class="dataframe python" border="0">
  <thead>
    <tr style="text-align: right;">
      <th></th>
      <th>close</th>
      <th>high</th>
      <th>low</th>
      <th>open</th>
    </tr>
    <tr>
      <th>time</th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th>2021-01-04 02:16:00</th>
      <td>3714.24</td>
      <td>3714.24</td>
      <td>3714.24</td>
      <td>3714.24</td>
    </tr>
    <tr>
      <th>2021-01-04 02:17:00</th>
      <td>3714.24</td>
      <td>3714.24</td>
      <td>3714.24</td>
      <td>3714.24</td>
    </tr>
    <tr>
      <th>2021-01-04 02:18:00</th>
      <td>3714.24</td>
      <td>3714.24</td>
      <td>3714.24</td>
      <td>3714.24</td>
    </tr>
    <tr>
      <th>2021-01-04 02:19:00</th>
      <td>3714.24</td>
      <td>3714.24</td>
      <td>3714.24</td>
      <td>3714.24</td>
    </tr>
    <tr>
      <th>2021-01-04 02:20:00</th>
      <td>3714.24</td>
      <td>3714.24</td>
      <td>3714.24</td>
      <td>3714.24</td>
    </tr>
    <tr>
      <th>...</th>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
    </tr>
    <tr>
      <th>2021-01-29 15:16:00</th>
      <td>3714.24</td>
      <td>3714.24</td>
      <td>3714.24</td>
      <td>3714.24</td>
    </tr>
    <tr>
      <th>2021-01-29 15:17:00</th>
      <td>3714.24</td>
      <td>3714.24</td>
      <td>3714.24</td>
      <td>3714.24</td>
    </tr>
    <tr>
      <th>2021-01-29 15:18:00</th>
      <td>3714.24</td>
      <td>3714.24</td>
      <td>3714.24</td>
      <td>3714.24</td>
    </tr>
    <tr>
      <th>2021-01-29 15:19:00</th>
      <td>3714.24</td>
      <td>3714.24</td>
      <td>3714.24</td>
      <td>3714.24</td>
    </tr>
    <tr>
      <th>2021-01-29 15:20:00</th>
      <td>3714.24</td>
      <td>3714.24</td>
      <td>3714.24</td>
      <td>3714.24</td>
    </tr>
  </tbody>
</table>
</div>
HTML;

$dataFrameImages[2] = <<<'HTML'
<div class="python section-example-container">
    <pre>time
2021-01-04 02:16:00    3714.24
2021-01-04 02:17:00    3714.24
2021-01-04 02:18:00    3714.24
2021-01-04 02:19:00    3714.24
2021-01-04 02:20:00    3714.24
                         ...
2021-01-29 15:16:00    3714.24
2021-01-29 15:17:00    3714.24
2021-01-29 15:18:00    3714.24
2021-01-29 15:19:00    3714.24
2021-01-29 15:20:00    3714.24
Name: close, dtype: float64</pre>
</div>
HTML;

$dataFrameImages[3] = <<<'HTML'
<div class="python dataframe-wrapper">
<table class="dataframe python" border="0">
  <thead>
    <tr style="text-align: right;">
      <th>symbol</th>
      <th>SPX 31</th>
      <th>VIX 31</th>
    </tr>
    <tr>
      <th>time</th>
      <th></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th>2021-01-04 02:16:00</th>
      <td>3714.24</td>
      <td>23.02</td>
    </tr>
    <tr>
      <th>2021-01-04 02:17:00</th>
      <td>3714.24</td>
      <td>23.04</td>
    </tr>
    <tr>
      <th>2021-01-04 02:18:00</th>
      <td>3714.24</td>
      <td>23.07</td>
    </tr>
    <tr>
      <th>2021-01-04 02:19:00</th>
      <td>3714.24</td>
      <td>23.03</td>
    </tr>
    <tr>
      <th>2021-01-04 02:20:00</th>
      <td>3714.24</td>
      <td>23.05</td>
    </tr>
    <tr>
      <th>...</th>
      <td>...</td>
      <td>...</td>
    </tr>
    <tr>
      <th>2021-01-29 15:16:00</th>
      <td>3714.24</td>
      <td>NaN</td>
    </tr>
    <tr>
      <th>2021-01-29 15:17:00</th>
      <td>3714.24</td>
      <td>NaN</td>
    </tr>
    <tr>
      <th>2021-01-29 15:18:00</th>
      <td>3714.24</td>
      <td>NaN</td>
    </tr>
    <tr>
      <th>2021-01-29 15:19:00</th>
      <td>3714.24</td>
      <td>NaN</td>
    </tr>
    <tr>
      <th>2021-01-29 15:20:00</th>
      <td>3714.24</td>
      <td>NaN</td>
    </tr>
  </tbody>
</table>
</div>
HTML;

$cSharpDataFrameImages = array(
    'https://cdn.quantconnect.com/i/tu/index-research-data-c-1.png',
    'https://cdn.quantconnect.com/i/tu/index-research-data-c-2.png'
);
$dataFrameColumnName = 'close';
$columnNameEnglish = 'close';
$supportsTrades = true;
$supportsQuotes = false;
$supportsTicks = true;
$supportsAltData = false;
$supportsOpenInterest = false;
$supportsOptionHistory = false;
$supportsFutureHistory = false;
$contractExpiryDate = '';
include(DOCS_RESOURCES."/datasets/wrangle-data.php");
?>
