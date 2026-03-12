<?
$assetClass = 'CFD';
$singularAssetClass = 'CFD contract';
$pluralAssetClass = 'CFD contracts';
$historicalDataLink = "/docs/v2/research-environment/datasets/cfd#04-Get-Historical-Data";
$primarySymbolPy = 'spx';
$primarySymbolC = 'spx';
$primaryTicker = 'SPX500USD';
$secondarySymbol = 'usb';

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
      <th rowspan="5" valign="top">SPX500USD 8I</th>
      <th>2021-01-04 00:01:00</th>
      <td>3700.150</td>
      <td>3700.750</td>
      <td>3699.850</td>
      <td>3700.350</td>
      <td>3699.550</td>
      <td>3700.150</td>
      <td>3699.250</td>
      <td>3699.750</td>
      <td>3699.850</td>
      <td>3700.450</td>
      <td>3699.550</td>
      <td>3700.050</td>
    </tr>
    <tr>
      <th>2021-01-04 00:02:00</th>
      <td>3701.250</td>
      <td>3701.650</td>
      <td>3700.050</td>
      <td>3700.150</td>
      <td>3700.650</td>
      <td>3701.050</td>
      <td>3699.450</td>
      <td>3699.550</td>
      <td>3700.950</td>
      <td>3701.350</td>
      <td>3699.750</td>
      <td>3699.850</td>
    </tr>
    <tr>
      <th>2021-01-04 00:03:00</th>
      <td>3702.450</td>
      <td>3703.150</td>
      <td>3701.050</td>
      <td>3701.250</td>
      <td>3701.850</td>
      <td>3702.550</td>
      <td>3700.450</td>
      <td>3700.650</td>
      <td>3702.150</td>
      <td>3702.850</td>
      <td>3700.750</td>
      <td>3700.950</td>
    </tr>
    <tr>
      <th>2021-01-04 00:04:00</th>
      <td>3703.850</td>
      <td>3704.250</td>
      <td>3702.250</td>
      <td>3702.450</td>
      <td>3703.250</td>
      <td>3703.650</td>
      <td>3701.650</td>
      <td>3701.850</td>
      <td>3703.550</td>
      <td>3703.950</td>
      <td>3701.950</td>
      <td>3702.150</td>
    </tr>
    <tr>
      <th>2021-01-04 00:05:00</th>
      <td>3704.650</td>
      <td>3705.050</td>
      <td>3703.650</td>
      <td>3703.850</td>
      <td>3704.050</td>
      <td>3704.450</td>
      <td>3703.050</td>
      <td>3703.250</td>
      <td>3704.350</td>
      <td>3704.750</td>
      <td>3703.350</td>
      <td>3703.550</td>
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
      <th rowspan="5" valign="top">USB30YUSD 8I</th>
      <th>2021-01-29 23:56:00</th>
      <td>167.450</td>
      <td>167.520</td>
      <td>167.380</td>
      <td>167.410</td>
      <td>166.950</td>
      <td>167.020</td>
      <td>166.880</td>
      <td>166.910</td>
      <td>167.200</td>
      <td>167.270</td>
      <td>167.130</td>
      <td>167.160</td>
    </tr>
    <tr>
      <th>2021-01-29 23:57:00</th>
      <td>167.380</td>
      <td>167.480</td>
      <td>167.320</td>
      <td>167.450</td>
      <td>166.880</td>
      <td>166.980</td>
      <td>166.820</td>
      <td>166.950</td>
      <td>167.130</td>
      <td>167.230</td>
      <td>167.070</td>
      <td>167.200</td>
    </tr>
    <tr>
      <th>2021-01-29 23:58:00</th>
      <td>167.310</td>
      <td>167.420</td>
      <td>167.250</td>
      <td>167.380</td>
      <td>166.810</td>
      <td>166.920</td>
      <td>166.750</td>
      <td>166.880</td>
      <td>167.060</td>
      <td>167.170</td>
      <td>167.000</td>
      <td>167.130</td>
    </tr>
    <tr>
      <th>2021-01-29 23:59:00</th>
      <td>167.250</td>
      <td>167.350</td>
      <td>167.190</td>
      <td>167.310</td>
      <td>166.750</td>
      <td>166.850</td>
      <td>166.690</td>
      <td>166.810</td>
      <td>167.000</td>
      <td>167.100</td>
      <td>166.940</td>
      <td>167.060</td>
    </tr>
    <tr>
      <th>2021-01-30 00:00:00</th>
      <td>167.190</td>
      <td>167.290</td>
      <td>167.130</td>
      <td>167.250</td>
      <td>166.690</td>
      <td>166.790</td>
      <td>166.630</td>
      <td>166.750</td>
      <td>166.940</td>
      <td>167.040</td>
      <td>166.880</td>
      <td>167.000</td>
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
      <th>2021-01-04 00:01:00</th>
      <td>3700.150</td>
      <td>3700.750</td>
      <td>3699.850</td>
      <td>3700.350</td>
      <td>3699.550</td>
      <td>3700.150</td>
      <td>3699.250</td>
      <td>3699.750</td>
      <td>3699.850</td>
      <td>3700.450</td>
      <td>3699.550</td>
      <td>3700.050</td>
    </tr>
    <tr>
      <th>2021-01-04 00:02:00</th>
      <td>3701.250</td>
      <td>3701.650</td>
      <td>3700.050</td>
      <td>3700.150</td>
      <td>3700.650</td>
      <td>3701.050</td>
      <td>3699.450</td>
      <td>3699.550</td>
      <td>3700.950</td>
      <td>3701.350</td>
      <td>3699.750</td>
      <td>3699.850</td>
    </tr>
    <tr>
      <th>2021-01-04 00:03:00</th>
      <td>3702.450</td>
      <td>3703.150</td>
      <td>3701.050</td>
      <td>3701.250</td>
      <td>3701.850</td>
      <td>3702.550</td>
      <td>3700.450</td>
      <td>3700.650</td>
      <td>3702.150</td>
      <td>3702.850</td>
      <td>3700.750</td>
      <td>3700.950</td>
    </tr>
    <tr>
      <th>2021-01-04 00:04:00</th>
      <td>3703.850</td>
      <td>3704.250</td>
      <td>3702.250</td>
      <td>3702.450</td>
      <td>3703.250</td>
      <td>3703.650</td>
      <td>3701.650</td>
      <td>3701.850</td>
      <td>3703.550</td>
      <td>3703.950</td>
      <td>3701.950</td>
      <td>3702.150</td>
    </tr>
    <tr>
      <th>2021-01-04 00:05:00</th>
      <td>3704.650</td>
      <td>3705.050</td>
      <td>3703.650</td>
      <td>3703.850</td>
      <td>3704.050</td>
      <td>3704.450</td>
      <td>3703.050</td>
      <td>3703.250</td>
      <td>3704.350</td>
      <td>3704.750</td>
      <td>3703.350</td>
      <td>3703.550</td>
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
      <th>2021-01-29 23:56:00</th>
      <td>3700.550</td>
      <td>3700.950</td>
      <td>3700.150</td>
      <td>3700.250</td>
      <td>3699.950</td>
      <td>3700.350</td>
      <td>3699.550</td>
      <td>3699.650</td>
      <td>3700.250</td>
      <td>3700.650</td>
      <td>3699.850</td>
      <td>3699.950</td>
    </tr>
    <tr>
      <th>2021-01-29 23:57:00</th>
      <td>3699.850</td>
      <td>3700.650</td>
      <td>3699.450</td>
      <td>3700.550</td>
      <td>3699.250</td>
      <td>3700.050</td>
      <td>3698.850</td>
      <td>3699.950</td>
      <td>3699.550</td>
      <td>3700.350</td>
      <td>3699.150</td>
      <td>3700.250</td>
    </tr>
    <tr>
      <th>2021-01-29 23:58:00</th>
      <td>3698.750</td>
      <td>3699.950</td>
      <td>3698.350</td>
      <td>3699.850</td>
      <td>3698.150</td>
      <td>3699.350</td>
      <td>3697.750</td>
      <td>3699.250</td>
      <td>3698.450</td>
      <td>3699.650</td>
      <td>3698.050</td>
      <td>3699.550</td>
    </tr>
    <tr>
      <th>2021-01-29 23:59:00</th>
      <td>3697.950</td>
      <td>3698.850</td>
      <td>3697.550</td>
      <td>3698.750</td>
      <td>3697.350</td>
      <td>3698.250</td>
      <td>3696.950</td>
      <td>3698.150</td>
      <td>3697.650</td>
      <td>3698.550</td>
      <td>3697.250</td>
      <td>3698.450</td>
    </tr>
    <tr>
      <th>2021-01-30 00:00:00</th>
      <td>3697.250</td>
      <td>3698.050</td>
      <td>3696.850</td>
      <td>3697.950</td>
      <td>3696.650</td>
      <td>3697.450</td>
      <td>3696.250</td>
      <td>3697.350</td>
      <td>3696.950</td>
      <td>3697.750</td>
      <td>3696.550</td>
      <td>3697.650</td>
    </tr>
  </tbody>
</table>
</div>
HTML;

$dataFrameImages[2] = <<<'HTML'
<div class="python section-example-container">
    <pre>time
2021-01-04 00:01:00    3699.850
2021-01-04 00:02:00    3700.950
2021-01-04 00:03:00    3702.150
2021-01-04 00:04:00    3703.550
2021-01-04 00:05:00    3704.350
                         ...
2021-01-29 23:56:00    3700.250
2021-01-29 23:57:00    3699.550
2021-01-29 23:58:00    3698.450
2021-01-29 23:59:00    3697.650
2021-01-30 00:00:00    3696.950
Name: close, dtype: float64</pre>
</div>
HTML;

$dataFrameImages[3] = <<<'HTML'
<div class="python dataframe-wrapper">
<table class="dataframe python" border="0">
  <thead>
    <tr style="text-align: right;">
      <th>symbol</th>
      <th>SPX500USD 8I</th>
      <th>USB30YUSD 8I</th>
    </tr>
    <tr>
      <th>time</th>
      <th></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th>2021-01-04 00:01:00</th>
      <td>3699.850</td>
      <td>167.200</td>
    </tr>
    <tr>
      <th>2021-01-04 00:02:00</th>
      <td>3700.950</td>
      <td>167.250</td>
    </tr>
    <tr>
      <th>2021-01-04 00:03:00</th>
      <td>3702.150</td>
      <td>167.310</td>
    </tr>
    <tr>
      <th>2021-01-04 00:04:00</th>
      <td>3703.550</td>
      <td>167.380</td>
    </tr>
    <tr>
      <th>2021-01-04 00:05:00</th>
      <td>3704.350</td>
      <td>167.420</td>
    </tr>
    <tr>
      <th>...</th>
      <td>...</td>
      <td>...</td>
    </tr>
    <tr>
      <th>2021-01-29 23:56:00</th>
      <td>3700.250</td>
      <td>167.200</td>
    </tr>
    <tr>
      <th>2021-01-29 23:57:00</th>
      <td>3699.550</td>
      <td>167.130</td>
    </tr>
    <tr>
      <th>2021-01-29 23:58:00</th>
      <td>3698.450</td>
      <td>167.060</td>
    </tr>
    <tr>
      <th>2021-01-29 23:59:00</th>
      <td>3697.650</td>
      <td>167.000</td>
    </tr>
    <tr>
      <th>2021-01-30 00:00:00</th>
      <td>3696.950</td>
      <td>166.940</td>
    </tr>
  </tbody>
</table>
</div>
HTML;

$cSharpDataFrameImages = array(
    'https://cdn.quantconnect.com/i/tu/cfd-research-data-c-1.png',
    'https://cdn.quantconnect.com/i/tu/cfd-research-data-c-2.png'
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
