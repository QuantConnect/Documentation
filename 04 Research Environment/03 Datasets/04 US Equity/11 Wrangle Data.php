<?
$assetClass = 'Equity';
$singularAssetClass = 'Equity';
$pluralAssetClass = 'Equities';
$historicalDataLink = "/docs/v2/research-environment/datasets/us-equity#04-Get-Historical-Data";
$primarySymbolPy = 'spy';
$primarySymbolC = 'spy';
$primaryTicker = 'SPY';
$secondarySymbol = 'tlt';

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
      <th>asksize</th>
      <th>bidclose</th>
      <th>bidhigh</th>
      <th>bidlow</th>
      <th>bidopen</th>
      <th>bidsize</th>
      <th>close</th>
      <th>high</th>
      <th>low</th>
      <th>open</th>
      <th>volume</th>
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
      <th></th>
      <th></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th rowspan="5" valign="top">TLT SGNKIKYGE9NP</th>
      <th>2021-01-04 09:31:00</th>
      <td>152.732434</td>
      <td>152.820211</td>
      <td>152.722681</td>
      <td>152.820211</td>
      <td>3589.0</td>
      <td>152.712928</td>
      <td>152.810458</td>
      <td>152.712928</td>
      <td>152.810458</td>
      <td>718.0</td>
      <td>152.732434</td>
      <td>152.810458</td>
      <td>152.722681</td>
      <td>152.800705</td>
      <td>102856.0</td>
    </tr>
    <tr>
      <th>2021-01-04 09:32:00</th>
      <td>152.703175</td>
      <td>152.771446</td>
      <td>152.683669</td>
      <td>152.732434</td>
      <td>3691.0</td>
      <td>152.693422</td>
      <td>152.761693</td>
      <td>152.673916</td>
      <td>152.712928</td>
      <td>205.0</td>
      <td>152.693422</td>
      <td>152.771446</td>
      <td>152.683669</td>
      <td>152.732434</td>
      <td>39834.0</td>
    </tr>
    <tr>
      <th>2021-01-04 09:33:00</th>
      <td>152.703175</td>
      <td>152.751940</td>
      <td>152.673916</td>
      <td>152.703175</td>
      <td>718.0</td>
      <td>152.693422</td>
      <td>152.732434</td>
      <td>152.664163</td>
      <td>152.693422</td>
      <td>103.0</td>
      <td>152.693422</td>
      <td>152.732434</td>
      <td>152.673916</td>
      <td>152.703175</td>
      <td>52457.0</td>
    </tr>
    <tr>
      <th>2021-01-04 09:34:00</th>
      <td>152.732434</td>
      <td>152.751940</td>
      <td>152.693422</td>
      <td>152.703175</td>
      <td>820.0</td>
      <td>152.712928</td>
      <td>152.742187</td>
      <td>152.673916</td>
      <td>152.693422</td>
      <td>2563.0</td>
      <td>152.712928</td>
      <td>152.742187</td>
      <td>152.683669</td>
      <td>152.693422</td>
      <td>64328.0</td>
    </tr>
    <tr>
      <th>2021-01-04 09:35:00</th>
      <td>152.800705</td>
      <td>152.800705</td>
      <td>152.712928</td>
      <td>152.732434</td>
      <td>615.0</td>
      <td>152.781199</td>
      <td>152.790952</td>
      <td>152.703175</td>
      <td>152.712928</td>
      <td>2461.0</td>
      <td>152.781199</td>
      <td>152.781199</td>
      <td>152.712928</td>
      <td>152.722681</td>
      <td>38442.0</td>
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
      <td>...</td>
      <td>...</td>
      <td>...</td>
    </tr>
    <tr>
      <th rowspan="5" valign="top">SPY R735QTJ8XC9X</th>
      <th>2021-01-29 15:56:00</th>
      <td>364.542435</td>
      <td>364.630595</td>
      <td>364.160408</td>
      <td>364.170203</td>
      <td>919.0</td>
      <td>364.522844</td>
      <td>364.620800</td>
      <td>364.140817</td>
      <td>364.150612</td>
      <td>2246.0</td>
      <td>364.532640</td>
      <td>364.620800</td>
      <td>364.160408</td>
      <td>364.165306</td>
      <td>1460591.0</td>
    </tr>
    <tr>
      <th>2021-01-29 15:57:00</th>
      <td>364.640391</td>
      <td>364.914667</td>
      <td>364.464071</td>
      <td>364.542435</td>
      <td>715.0</td>
      <td>364.630595</td>
      <td>364.904872</td>
      <td>364.444479</td>
      <td>364.522844</td>
      <td>408.0</td>
      <td>364.630595</td>
      <td>364.904872</td>
      <td>364.464071</td>
      <td>364.522844</td>
      <td>1432354.0</td>
    </tr>
    <tr>
      <th>2021-01-29 15:58:00</th>
      <td>363.993883</td>
      <td>364.728551</td>
      <td>363.866540</td>
      <td>364.640391</td>
      <td>102.0</td>
      <td>363.984087</td>
      <td>364.718756</td>
      <td>363.856745</td>
      <td>364.630595</td>
      <td>1531.0</td>
      <td>363.954701</td>
      <td>364.718756</td>
      <td>363.866540</td>
      <td>364.630595</td>
      <td>698148.0</td>
    </tr>
    <tr>
      <th>2021-01-29 15:59:00</th>
      <td>363.200441</td>
      <td>363.993883</td>
      <td>363.151463</td>
      <td>363.993883</td>
      <td>306.0</td>
      <td>363.190646</td>
      <td>363.984087</td>
      <td>363.141668</td>
      <td>363.984087</td>
      <td>408.0</td>
      <td>363.190646</td>
      <td>363.974292</td>
      <td>363.141668</td>
      <td>363.974292</td>
      <td>2216430.0</td>
    </tr>
    <tr>
      <th>2021-01-29 16:00:00</th>
      <td>362.622502</td>
      <td>363.562877</td>
      <td>362.387408</td>
      <td>363.200441</td>
      <td>7452.0</td>
      <td>362.612706</td>
      <td>363.553082</td>
      <td>362.377613</td>
      <td>363.190646</td>
      <td>95553.0</td>
      <td>362.504955</td>
      <td>363.543286</td>
      <td>362.377613</td>
      <td>363.190646</td>
      <td>10077306.0</td>
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
      <th>asksize</th>
      <th>bidclose</th>
      <th>bidhigh</th>
      <th>bidlow</th>
      <th>bidopen</th>
      <th>bidsize</th>
      <th>close</th>
      <th>high</th>
      <th>low</th>
      <th>open</th>
      <th>volume</th>
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
      <th></th>
      <th></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th>2021-01-04 09:31:00</th>
      <td>367.500700</td>
      <td>367.774976</td>
      <td>367.451722</td>
      <td>367.628042</td>
      <td>102.0</td>
      <td>367.481109</td>
      <td>367.765180</td>
      <td>367.441926</td>
      <td>367.628042</td>
      <td>510.0</td>
      <td>367.481109</td>
      <td>367.774976</td>
      <td>367.451722</td>
      <td>367.628042</td>
      <td>929595.0</td>
    </tr>
    <tr>
      <th>2021-01-04 09:32:00</th>
      <td>367.392948</td>
      <td>367.559473</td>
      <td>367.383153</td>
      <td>367.500700</td>
      <td>1021.0</td>
      <td>367.373357</td>
      <td>367.539882</td>
      <td>367.363562</td>
      <td>367.481109</td>
      <td>204.0</td>
      <td>367.383153</td>
      <td>367.539882</td>
      <td>367.383153</td>
      <td>367.481109</td>
      <td>302353.0</td>
    </tr>
    <tr>
      <th>2021-01-04 09:33:00</th>
      <td>367.167650</td>
      <td>367.402744</td>
      <td>367.148059</td>
      <td>367.392948</td>
      <td>510.0</td>
      <td>367.148059</td>
      <td>367.383153</td>
      <td>367.138263</td>
      <td>367.373357</td>
      <td>102.0</td>
      <td>367.148059</td>
      <td>367.392948</td>
      <td>367.138263</td>
      <td>367.383153</td>
      <td>228899.0</td>
    </tr>
    <tr>
      <th>2021-01-04 09:34:00</th>
      <td>366.991330</td>
      <td>367.187241</td>
      <td>366.883578</td>
      <td>367.167650</td>
      <td>715.0</td>
      <td>366.971739</td>
      <td>367.177446</td>
      <td>366.873783</td>
      <td>367.148059</td>
      <td>102.0</td>
      <td>366.971739</td>
      <td>367.167650</td>
      <td>366.883578</td>
      <td>367.148059</td>
      <td>331341.0</td>
    </tr>
    <tr>
      <th>2021-01-04 09:35:00</th>
      <td>366.893374</td>
      <td>367.040308</td>
      <td>366.854192</td>
      <td>366.991330</td>
      <td>510.0</td>
      <td>366.873783</td>
      <td>367.030512</td>
      <td>366.844396</td>
      <td>366.971739</td>
      <td>613.0</td>
      <td>366.883578</td>
      <td>367.040308</td>
      <td>366.844396</td>
      <td>367.010921</td>
      <td>306624.0</td>
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
      <td>...</td>
      <td>...</td>
      <td>...</td>
    </tr>
    <tr>
      <th>2021-01-29 15:56:00</th>
      <td>364.542435</td>
      <td>364.630595</td>
      <td>364.160408</td>
      <td>364.170203</td>
      <td>919.0</td>
      <td>364.522844</td>
      <td>364.620800</td>
      <td>364.140817</td>
      <td>364.150612</td>
      <td>2246.0</td>
      <td>364.532640</td>
      <td>364.620800</td>
      <td>364.160408</td>
      <td>364.165306</td>
      <td>1460591.0</td>
    </tr>
    <tr>
      <th>2021-01-29 15:57:00</th>
      <td>364.640391</td>
      <td>364.914667</td>
      <td>364.464071</td>
      <td>364.542435</td>
      <td>715.0</td>
      <td>364.630595</td>
      <td>364.904872</td>
      <td>364.444479</td>
      <td>364.522844</td>
      <td>408.0</td>
      <td>364.630595</td>
      <td>364.904872</td>
      <td>364.464071</td>
      <td>364.522844</td>
      <td>1432354.0</td>
    </tr>
    <tr>
      <th>2021-01-29 15:58:00</th>
      <td>363.993883</td>
      <td>364.728551</td>
      <td>363.866540</td>
      <td>364.640391</td>
      <td>102.0</td>
      <td>363.984087</td>
      <td>364.718756</td>
      <td>363.856745</td>
      <td>364.630595</td>
      <td>1531.0</td>
      <td>363.954701</td>
      <td>364.718756</td>
      <td>363.866540</td>
      <td>364.630595</td>
      <td>698148.0</td>
    </tr>
    <tr>
      <th>2021-01-29 15:59:00</th>
      <td>363.200441</td>
      <td>363.993883</td>
      <td>363.151463</td>
      <td>363.993883</td>
      <td>306.0</td>
      <td>363.190646</td>
      <td>363.984087</td>
      <td>363.141668</td>
      <td>363.984087</td>
      <td>408.0</td>
      <td>363.190646</td>
      <td>363.974292</td>
      <td>363.141668</td>
      <td>363.974292</td>
      <td>2216430.0</td>
    </tr>
    <tr>
      <th>2021-01-29 16:00:00</th>
      <td>362.622502</td>
      <td>363.562877</td>
      <td>362.387408</td>
      <td>363.200441</td>
      <td>7452.0</td>
      <td>362.612706</td>
      <td>363.553082</td>
      <td>362.377613</td>
      <td>363.190646</td>
      <td>95553.0</td>
      <td>362.504955</td>
      <td>363.543286</td>
      <td>362.377613</td>
      <td>363.190646</td>
      <td>10077306.0</td>
    </tr>
  </tbody>
</table>
</div>
HTML;

$dataFrameImages[2] = <<<'HTML'
<div class="python section-example-container">
    <pre>time
2021-01-04 09:31:00    367.481109
2021-01-04 09:32:00    367.383153
2021-01-04 09:33:00    367.148059
2021-01-04 09:34:00    366.971739
2021-01-04 09:35:00    366.883578
                          ...
2021-01-29 15:56:00    364.532640
2021-01-29 15:57:00    364.630595
2021-01-29 15:58:00    363.954701
2021-01-29 15:59:00    363.190646
2021-01-29 16:00:00    362.504955
Name: close, dtype: float64</pre>
</div>
HTML;

$dataFrameImages[3] = <<<'HTML'
<div class="python dataframe-wrapper">
<table class="dataframe python" border="0">
  <thead>
    <tr style="text-align: right;">
      <th>symbol</th>
      <th>SPY R735QTJ8XC9X</th>
      <th>TLT SGNKIKYGE9NP</th>
    </tr>
    <tr>
      <th>time</th>
      <th></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th>2021-01-04 09:31:00</th>
      <td>367.481109</td>
      <td>152.732434</td>
    </tr>
    <tr>
      <th>2021-01-04 09:32:00</th>
      <td>367.383153</td>
      <td>152.693422</td>
    </tr>
    <tr>
      <th>2021-01-04 09:33:00</th>
      <td>367.148059</td>
      <td>152.693422</td>
    </tr>
    <tr>
      <th>2021-01-04 09:34:00</th>
      <td>366.971739</td>
      <td>152.712928</td>
    </tr>
    <tr>
      <th>2021-01-04 09:35:00</th>
      <td>366.883578</td>
      <td>152.781199</td>
    </tr>
    <tr>
      <th>...</th>
      <td>...</td>
      <td>...</td>
    </tr>
    <tr>
      <th>2021-01-29 15:56:00</th>
      <td>364.532640</td>
      <td>148.177770</td>
    </tr>
    <tr>
      <th>2021-01-29 15:57:00</th>
      <td>364.630595</td>
      <td>148.148511</td>
    </tr>
    <tr>
      <th>2021-01-29 15:58:00</th>
      <td>363.954701</td>
      <td>148.294806</td>
    </tr>
    <tr>
      <th>2021-01-29 15:59:00</th>
      <td>363.190646</td>
      <td>148.285053</td>
    </tr>
    <tr>
      <th>2021-01-29 16:00:00</th>
      <td>362.504955</td>
      <td>148.246041</td>
    </tr>
  </tbody>
</table>
</div>
HTML;

$equityTickerDataFrameHtml = <<<'HTML'
<div class="python dataframe-wrapper">
<table class="dataframe python" border="0">
  <thead>
    <tr style="text-align: right;">
      <th>symbol</th>
      <th>SPY</th>
      <th>TLT</th>
    </tr>
    <tr>
      <th>time</th>
      <th></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th>2021-01-04 09:31:00</th>
      <td>367.481109</td>
      <td>152.732434</td>
    </tr>
    <tr>
      <th>2021-01-04 09:32:00</th>
      <td>367.383153</td>
      <td>152.693422</td>
    </tr>
    <tr>
      <th>2021-01-04 09:33:00</th>
      <td>367.148059</td>
      <td>152.693422</td>
    </tr>
    <tr>
      <th>2021-01-04 09:34:00</th>
      <td>366.971739</td>
      <td>152.712928</td>
    </tr>
    <tr>
      <th>2021-01-04 09:35:00</th>
      <td>366.883578</td>
      <td>152.781199</td>
    </tr>
    <tr>
      <th>...</th>
      <td>...</td>
      <td>...</td>
    </tr>
    <tr>
      <th>2021-01-29 15:56:00</th>
      <td>364.532640</td>
      <td>148.177770</td>
    </tr>
    <tr>
      <th>2021-01-29 15:57:00</th>
      <td>364.630595</td>
      <td>148.148511</td>
    </tr>
    <tr>
      <th>2021-01-29 15:58:00</th>
      <td>363.954701</td>
      <td>148.294806</td>
    </tr>
    <tr>
      <th>2021-01-29 15:59:00</th>
      <td>363.190646</td>
      <td>148.285053</td>
    </tr>
    <tr>
      <th>2021-01-29 16:00:00</th>
      <td>362.504955</td>
      <td>148.246041</td>
    </tr>
  </tbody>
</table>
</div>
HTML;

$cSharpDataFrameImages = array(
    'https://cdn.quantconnect.com/i/tu/us-equity-research-data-c-1.jpg',
    'https://cdn.quantconnect.com/i/tu/us-equity-research-data-c-2.jpg'
);
$dataFrameColumnName = 'close';
$columnNameEnglish = 'close';
$supportsTrades = true;
$supportsQuotes = true;
$supportsTicks = true;
$supportsAltData = false;
$supportsOpenInterest = false;
$supportsOptionHistory = false;
$supportsFutureHistory = false;
$contractExpiryDate = '';
include(DOCS_RESOURCES."/datasets/wrangle-data.php");
?>
