<?
$assetClass = 'Forex';
$singularAssetClass = 'Forex pair';
$pluralAssetClass = 'Forex pairs';
$historicalDataLink = "/docs/v2/research-environment/datasets/forex#04-Get-Historical-Data";
$primarySymbolPy = 'eurusd';
$primarySymbolC = 'eurusd';
$primaryTicker = 'EURUSD';
$secondarySymbol = 'gbpusd';

$dataFrameImages = array();

$dataFrameImages[0] = <<<'HTML'
<div class="python dataframe-wrapper">
<table class="dataframe python" border="0">
  <thead>
    <tr style="text-align: right;">
      <th></th>
      <th></th>
      <th>askclose</th>
      <th>askhigh</th>
      <th>asklow</th>
      <th>askopen</th>
      <th>bidclose</th>
      <th>bidhigh</th>
      <th>bidlow</th>
      <th>bidopen</th>
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
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th rowspan="5" valign="top">EURUSD 8G</th>
      <th>2021-01-03 17:01:00</th>
      <td>1.22423</td>
      <td>1.22430</td>
      <td>1.22414</td>
      <td>1.22415</td>
      <td>1.22388</td>
      <td>1.22388</td>
      <td>1.22372</td>
      <td>1.22384</td>
      <td>1.224055</td>
      <td>1.224090</td>
      <td>1.223930</td>
      <td>1.223995</td>
    </tr>
    <tr>
      <th>2021-01-03 17:02:00</th>
      <td>1.22452</td>
      <td>1.22460</td>
      <td>1.22414</td>
      <td>1.22423</td>
      <td>1.22389</td>
      <td>1.22394</td>
      <td>1.22377</td>
      <td>1.22388</td>
      <td>1.224205</td>
      <td>1.224270</td>
      <td>1.223955</td>
      <td>1.224055</td>
    </tr>
    <tr>
      <th>2021-01-03 17:03:00</th>
      <td>1.22435</td>
      <td>1.22453</td>
      <td>1.22435</td>
      <td>1.22452</td>
      <td>1.22366</td>
      <td>1.22389</td>
      <td>1.22366</td>
      <td>1.22389</td>
      <td>1.224005</td>
      <td>1.224210</td>
      <td>1.224005</td>
      <td>1.224205</td>
    </tr>
    <tr>
      <th>2021-01-03 17:04:00</th>
      <td>1.22425</td>
      <td>1.22435</td>
      <td>1.22425</td>
      <td>1.22435</td>
      <td>1.22351</td>
      <td>1.22366</td>
      <td>1.22351</td>
      <td>1.22366</td>
      <td>1.223880</td>
      <td>1.224005</td>
      <td>1.223880</td>
      <td>1.224005</td>
    </tr>
    <tr>
      <th>2021-01-03 17:05:00</th>
      <td>1.22413</td>
      <td>1.22425</td>
      <td>1.22398</td>
      <td>1.22425</td>
      <td>1.22313</td>
      <td>1.22366</td>
      <td>1.22298</td>
      <td>1.22351</td>
      <td>1.223630</td>
      <td>1.223955</td>
      <td>1.223480</td>
      <td>1.223880</td>
    </tr>
    <tr>
      <th>...</th>
      <th>...</th>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
    </tr>
    <tr>
      <th rowspan="5" valign="top">GBPUSD 8G</th>
      <th>2021-01-31 23:56:00</th>
      <td>1.37335</td>
      <td>1.37342</td>
      <td>1.37335</td>
      <td>1.37335</td>
      <td>1.37314</td>
      <td>1.37323</td>
      <td>1.37314</td>
      <td>1.37316</td>
      <td>1.373245</td>
      <td>1.373325</td>
      <td>1.373245</td>
      <td>1.373255</td>
    </tr>
    <tr>
      <th>2021-01-31 23:57:00</th>
      <td>1.37327</td>
      <td>1.37335</td>
      <td>1.37327</td>
      <td>1.37335</td>
      <td>1.37306</td>
      <td>1.37314</td>
      <td>1.37306</td>
      <td>1.37314</td>
      <td>1.373165</td>
      <td>1.373245</td>
      <td>1.373165</td>
      <td>1.373245</td>
    </tr>
    <tr>
      <th>2021-01-31 23:58:00</th>
      <td>1.37338</td>
      <td>1.37341</td>
      <td>1.37333</td>
      <td>1.37327</td>
      <td>1.37319</td>
      <td>1.37319</td>
      <td>1.37306</td>
      <td>1.37306</td>
      <td>1.373285</td>
      <td>1.373285</td>
      <td>1.373160</td>
      <td>1.373165</td>
    </tr>
    <tr>
      <th>2021-01-31 23:59:00</th>
      <td>1.37333</td>
      <td>1.37341</td>
      <td>1.37333</td>
      <td>1.37338</td>
      <td>1.37317</td>
      <td>1.37321</td>
      <td>1.37316</td>
      <td>1.37319</td>
      <td>1.373250</td>
      <td>1.373310</td>
      <td>1.373245</td>
      <td>1.373285</td>
    </tr>
    <tr>
      <th>2021-02-01 00:00:00</th>
      <td>1.37334</td>
      <td>1.37337</td>
      <td>1.37332</td>
      <td>1.37333</td>
      <td>1.37316</td>
      <td>1.37319</td>
      <td>1.37313</td>
      <td>1.37317</td>
      <td>1.373250</td>
      <td>1.373280</td>
      <td>1.373225</td>
      <td>1.373250</td>
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
      <th>askclose</th>
      <th>askhigh</th>
      <th>asklow</th>
      <th>askopen</th>
      <th>bidclose</th>
      <th>bidhigh</th>
      <th>bidlow</th>
      <th>bidopen</th>
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
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th>2021-01-03 17:01:00</th>
      <td>1.22423</td>
      <td>1.22430</td>
      <td>1.22414</td>
      <td>1.22415</td>
      <td>1.22388</td>
      <td>1.22388</td>
      <td>1.22372</td>
      <td>1.22384</td>
      <td>1.224055</td>
      <td>1.224090</td>
      <td>1.223930</td>
      <td>1.223995</td>
    </tr>
    <tr>
      <th>2021-01-03 17:02:00</th>
      <td>1.22452</td>
      <td>1.22460</td>
      <td>1.22414</td>
      <td>1.22423</td>
      <td>1.22389</td>
      <td>1.22394</td>
      <td>1.22377</td>
      <td>1.22388</td>
      <td>1.224205</td>
      <td>1.224270</td>
      <td>1.223955</td>
      <td>1.224055</td>
    </tr>
    <tr>
      <th>2021-01-03 17:03:00</th>
      <td>1.22435</td>
      <td>1.22453</td>
      <td>1.22435</td>
      <td>1.22452</td>
      <td>1.22366</td>
      <td>1.22389</td>
      <td>1.22366</td>
      <td>1.22389</td>
      <td>1.224005</td>
      <td>1.224210</td>
      <td>1.224005</td>
      <td>1.224205</td>
    </tr>
    <tr>
      <th>2021-01-03 17:04:00</th>
      <td>1.22425</td>
      <td>1.22435</td>
      <td>1.22425</td>
      <td>1.22435</td>
      <td>1.22351</td>
      <td>1.22366</td>
      <td>1.22351</td>
      <td>1.22366</td>
      <td>1.223880</td>
      <td>1.224005</td>
      <td>1.223880</td>
      <td>1.224005</td>
    </tr>
    <tr>
      <th>2021-01-03 17:05:00</th>
      <td>1.22413</td>
      <td>1.22425</td>
      <td>1.22398</td>
      <td>1.22425</td>
      <td>1.22313</td>
      <td>1.22366</td>
      <td>1.22298</td>
      <td>1.22351</td>
      <td>1.223630</td>
      <td>1.223955</td>
      <td>1.223480</td>
      <td>1.223880</td>
    </tr>
    <tr>
      <th>...</th>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
    </tr>
    <tr>
      <th>2021-01-31 23:56:00</th>
      <td>1.20928</td>
      <td>1.20935</td>
      <td>1.20921</td>
      <td>1.20925</td>
      <td>1.20857</td>
      <td>1.20864</td>
      <td>1.20850</td>
      <td>1.20854</td>
      <td>1.208925</td>
      <td>1.208995</td>
      <td>1.208855</td>
      <td>1.208895</td>
    </tr>
    <tr>
      <th>2021-01-31 23:57:00</th>
      <td>1.20930</td>
      <td>1.20936</td>
      <td>1.20924</td>
      <td>1.20928</td>
      <td>1.20856</td>
      <td>1.20862</td>
      <td>1.20852</td>
      <td>1.20857</td>
      <td>1.208930</td>
      <td>1.208990</td>
      <td>1.208880</td>
      <td>1.208925</td>
    </tr>
    <tr>
      <th>2021-01-31 23:58:00</th>
      <td>1.20924</td>
      <td>1.20932</td>
      <td>1.20920</td>
      <td>1.20930</td>
      <td>1.20857</td>
      <td>1.20860</td>
      <td>1.20849</td>
      <td>1.20856</td>
      <td>1.208905</td>
      <td>1.208960</td>
      <td>1.208845</td>
      <td>1.208930</td>
    </tr>
    <tr>
      <th>2021-01-31 23:59:00</th>
      <td>1.20927</td>
      <td>1.20930</td>
      <td>1.20922</td>
      <td>1.20924</td>
      <td>1.20856</td>
      <td>1.20860</td>
      <td>1.20853</td>
      <td>1.20857</td>
      <td>1.208915</td>
      <td>1.208950</td>
      <td>1.208875</td>
      <td>1.208905</td>
    </tr>
    <tr>
      <th>2021-02-01 00:00:00</th>
      <td>1.20926</td>
      <td>1.20929</td>
      <td>1.20923</td>
      <td>1.20927</td>
      <td>1.20858</td>
      <td>1.20860</td>
      <td>1.20855</td>
      <td>1.20856</td>
      <td>1.208920</td>
      <td>1.208945</td>
      <td>1.208890</td>
      <td>1.208915</td>
    </tr>
  </tbody>
</table>
</div>
HTML;

$dataFrameImages[2] = <<<'HTML'
<div class="python section-example-container">
    <pre>time
2021-01-03 17:01:00    1.224055
2021-01-03 17:02:00    1.224205
2021-01-03 17:03:00    1.224005
2021-01-03 17:04:00    1.223880
2021-01-03 17:05:00    1.223630
                          ...
2021-01-31 23:56:00    1.208925
2021-01-31 23:57:00    1.208930
2021-01-31 23:58:00    1.208905
2021-01-31 23:59:00    1.208915
2021-02-01 00:00:00    1.208920
Name: close, dtype: float64</pre>
</div>
HTML;

$dataFrameImages[3] = <<<'HTML'
<div class="python dataframe-wrapper">
<table class="dataframe python" border="0">
  <thead>
    <tr style="text-align: right;">
      <th>symbol</th>
      <th>EURUSD 8G</th>
      <th>GBPUSD 8G</th>
    </tr>
    <tr>
      <th>time</th>
      <th></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th>2021-01-03 17:01:00</th>
      <td>1.224055</td>
      <td>NaN</td>
    </tr>
    <tr>
      <th>2021-01-03 17:02:00</th>
      <td>1.224205</td>
      <td>NaN</td>
    </tr>
    <tr>
      <th>2021-01-03 17:03:00</th>
      <td>1.224005</td>
      <td>NaN</td>
    </tr>
    <tr>
      <th>2021-01-03 17:04:00</th>
      <td>1.223880</td>
      <td>NaN</td>
    </tr>
    <tr>
      <th>2021-01-03 17:05:00</th>
      <td>1.223630</td>
      <td>NaN</td>
    </tr>
    <tr>
      <th>...</th>
      <td>...</td>
      <td>...</td>
    </tr>
    <tr>
      <th>2021-01-31 23:56:00</th>
      <td>1.208925</td>
      <td>1.373245</td>
    </tr>
    <tr>
      <th>2021-01-31 23:57:00</th>
      <td>1.208930</td>
      <td>1.373165</td>
    </tr>
    <tr>
      <th>2021-01-31 23:58:00</th>
      <td>1.208905</td>
      <td>1.373285</td>
    </tr>
    <tr>
      <th>2021-01-31 23:59:00</th>
      <td>1.208915</td>
      <td>1.373250</td>
    </tr>
    <tr>
      <th>2021-02-01 00:00:00</th>
      <td>1.208920</td>
      <td>1.373250</td>
    </tr>
  </tbody>
</table>
</div>
HTML;

$cSharpDataFrameImages = array(
    'https://cdn.quantconnect.com/i/tu/forex-research-data-c-1.png',
    'https://cdn.quantconnect.com/i/tu/forex-research-data-c-2.png'
);
$dataFrameColumnName = 'close';
$columnNameEnglish = 'close';
$supportsTrades = false;
$supportsQuotes = true;
$supportsTicks = true;
$supportsAltData = false;
$supportsOpenInterest = false;
$supportsOptionHistory = false;
$supportsFutureHistory = false;
$contractExpiryDate = '';
include(DOCS_RESOURCES."/datasets/wrangle-data.php");
?>
