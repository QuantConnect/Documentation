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

$cSharpDataFrameImages = array();

$cSharpDataFrameImages[0] = <<<'HTML'
<div class="csharp dataframe-wrapper">
<table class="dataframe csharp" border="0">
  <thead>
    <tr style="text-align: right;">
      <th>Time</th>
      <th>SPY Open</th>
      <th>SPY High</th>
      <th>SPY Low</th>
      <th>SPY Close</th>
      <th>SPY Volume</th>
      <th>AAPL Open</th>
      <th>AAPL High</th>
      <th>AAPL Low</th>
      <th>AAPL Close</th>
      <th>AAPL Volume</th>
    </tr>
  </thead>
  <tbody>
    <tr><td>2/10/2023 9:31:00 AM</td><td>405.8500</td><td>406.6100</td><td>405.8500</td><td>406.6100</td><td>584209</td><td>149.2700</td><td>150.2700</td><td>149.1100</td><td>150.1600</td><td>1120940</td></tr>
    <tr><td>2/10/2023 9:32:00 AM</td><td>406.5700</td><td>406.5700</td><td>406.2600</td><td>406.3800</td><td>336328</td><td>150.1500</td><td>150.4000</td><td>149.9800</td><td>150.3100</td><td>289512</td></tr>
    <tr><td>2/10/2023 9:33:00 AM</td><td>406.3700</td><td>406.4800</td><td>405.1500</td><td>406.2200</td><td>367061</td><td>150.3000</td><td>150.3600</td><td>149.9500</td><td>149.9500</td><td>236794</td></tr>
    <tr><td>2/10/2023 9:34:00 AM</td><td>406.2300</td><td>406.6000</td><td>406.1950</td><td>406.5400</td><td>265490</td><td>149.9500</td><td>150.3400</td><td>149.9100</td><td>150.0450</td><td>214259</td></tr>
    <tr><td>2/10/2023 9:35:00 AM</td><td>406.5500</td><td>406.5500</td><td>406.1700</td><td>406.5200</td><td>339007</td><td>150.0400</td><td>150.2300</td><td>149.9400</td><td>150.0550</td><td>178793</td></tr>
    <tr><td>2/10/2023 9:36:00 AM</td><td>406.5200</td><td>406.8800</td><td>406.4100</td><td>406.8800</td><td>281397</td><td>150.0600</td><td>150.4000</td><td>149.9600</td><td>150.3300</td><td>224963</td></tr>
    <tr><td>2/10/2023 9:37:00 AM</td><td>406.8600</td><td>406.8900</td><td>406.4300</td><td>406.4900</td><td>364970</td><td>150.3500</td><td>150.4300</td><td>150.1000</td><td>150.1700</td><td>252067</td></tr>
    <tr><td>2/10/2023 9:38:00 AM</td><td>406.4900</td><td>406.6900</td><td>406.3600</td><td>406.4100</td><td>198506</td><td>150.1700</td><td>150.3000</td><td>150.1200</td><td>150.2000</td><td>140729</td></tr>
    <tr><td>2/10/2023 9:39:00 AM</td><td>406.3800</td><td>406.3800</td><td>406.1300</td><td>406.1400</td><td>203263</td><td>150.2000</td><td>150.2200</td><td>149.9400</td><td>150.0250</td><td>258542</td></tr>
    <tr><td>2/10/2023 9:40:00 AM</td><td>406.1600</td><td>406.3500</td><td>406.1300</td><td>406.2000</td><td>243860</td><td>150.0300</td><td>150.2100</td><td>149.9200</td><td>150.1400</td><td>174336</td></tr>
    <tr><td>2/10/2023 9:41:00 AM</td><td>406.2000</td><td>406.2700</td><td>405.7100</td><td>405.7100</td><td>386480</td><td>150.1400</td><td>150.1900</td><td>149.6400</td><td>149.6900</td><td>344849</td></tr>
    <tr><td>2/10/2023 9:42:00 AM</td><td>405.6700</td><td>405.9200</td><td>405.6000</td><td>405.8500</td><td>175312</td><td>149.6900</td><td>150.0800</td><td>149.6400</td><td>150.0500</td><td>248067</td></tr>
    <tr><td>2/10/2023 9:43:00 AM</td><td>405.8500</td><td>405.8700</td><td>405.5000</td><td>405.6500</td><td>347196</td><td>150.0600</td><td>150.0600</td><td>149.7000</td><td>149.7700</td><td>244737</td></tr>
    <tr><td>2/10/2023 9:44:00 AM</td><td>405.6500</td><td>405.8200</td><td>405.4500</td><td>405.6100</td><td>232852</td><td>149.7800</td><td>149.9100</td><td>149.6000</td><td>149.9100</td><td>226673</td></tr>
    <tr><td>2/10/2023 9:45:00 AM</td><td>405.6000</td><td>405.9300</td><td>405.5600</td><td>405.8500</td><td>163956</td><td>149.9100</td><td>150.0400</td><td>149.8800</td><td>149.9400</td><td>174226</td></tr>
    <tr><td>2/10/2023 9:46:00 AM</td><td>405.8300</td><td>406.6400</td><td>405.6200</td><td>406.4200</td><td>372593</td><td>149.9400</td><td>150.3550</td><td>149.8100</td><td>150.3100</td><td>318755</td></tr>
    <tr><td>2/10/2023 9:47:00 AM</td><td>406.4300</td><td>406.4900</td><td>406.1000</td><td>406.2700</td><td>160858</td><td>150.3200</td><td>150.5300</td><td>150.2500</td><td>150.3900</td><td>220010</td></tr>
    <tr><td>2/10/2023 9:48:00 AM</td><td>406.2800</td><td>406.2800</td><td>405.8800</td><td>406.1800</td><td>229179</td><td>150.4000</td><td>150.4000</td><td>149.9700</td><td>150.0200</td><td>231972</td></tr>
    <tr><td>2/10/2023 9:49:00 AM</td><td>406.1200</td><td>406.4000</td><td>405.9600</td><td>406.3600</td><td>213963</td><td>150.1800</td><td>150.3500</td><td>149.9800</td><td>150.3050</td><td>126318</td></tr>
    <tr><td>2/10/2023 9:50:00 AM</td><td>406.3800</td><td>406.7100</td><td>406.2400</td><td>406.4600</td><td>199056</td><td>150.3000</td><td>150.4700</td><td>150.2300</td><td>150.4250</td><td>177425</td></tr>
    <tr><td>2/10/2023 9:51:00 AM</td><td>406.6500</td><td>406.9700</td><td>406.2100</td><td>406.8800</td><td>251044</td><td>150.4250</td><td>150.4600</td><td>149.9500</td><td>150.4400</td><td>193480</td></tr>
    <tr><td>2/10/2023 9:52:00 AM</td><td>406.8900</td><td>406.9000</td><td>406.6500</td><td>406.8300</td><td>258783</td><td>150.4400</td><td>150.5600</td><td>150.3000</td><td>150.4300</td><td>170621</td></tr>
    <tr><td>2/10/2023 9:53:00 AM</td><td>406.8600</td><td>406.9100</td><td>406.4000</td><td>406.4900</td><td>187522</td><td>150.4400</td><td>150.5000</td><td>149.8800</td><td>150.0100</td><td>168484</td></tr>
    <tr><td>2/10/2023 9:54:00 AM</td><td>406.4600</td><td>406.5000</td><td>406.2000</td><td>406.4900</td><td>145931</td><td>149.8900</td><td>150.0300</td><td>149.8800</td><td>150.0100</td><td>177716</td></tr>
    <tr><td colspan="11" style="text-align: center;">...</td></tr>
    <tr><td>2/17/2023 3:57:00 PM</td><td>407.2150</td><td>407.3100</td><td>407.1000</td><td>407.1200</td><td>428836</td><td>152.4900</td><td>152.5400</td><td>152.4000</td><td>152.4300</td><td>352904</td></tr>
    <tr><td>2/17/2023 3:58:00 PM</td><td>407.1100</td><td>407.1200</td><td>406.9300</td><td>406.9600</td><td>507197</td><td>152.4300</td><td>152.4500</td><td>152.3300</td><td>152.3550</td><td>303069</td></tr>
    <tr><td>2/17/2023 3:59:00 PM</td><td>406.9900</td><td>407.1900</td><td>406.9300</td><td>407.0850</td><td>681816</td><td>152.3600</td><td>152.5100</td><td>152.3000</td><td>152.4300</td><td>443195</td></tr>
    <tr><td>2/17/2023 4:00:00 PM</td><td>407.0900</td><td>407.2700</td><td>407.0150</td><td>407.2600</td><td>2756142</td><td>152.4250</td><td>152.5500</td><td>152.3800</td><td>152.5500</td><td>6197916</td></tr>
  </tbody>
</table>
</div>
HTML;

$cSharpDataFrameImages[1] = <<<'HTML'
<div class="csharp dataframe-wrapper">
<table class="dataframe csharp" border="0">
  <thead>
    <tr style="text-align: right;">
      <th><i>index</i></th>
      <th>value</th>
    </tr>
  </thead>
  <tbody>
    <tr><td>0</td><td>406.6100</td></tr>
    <tr><td>1</td><td>406.3800</td></tr>
    <tr><td>2</td><td>406.2200</td></tr>
    <tr><td>3</td><td>406.5400</td></tr>
    <tr><td>4</td><td>406.5200</td></tr>
    <tr><td>5</td><td>406.8800</td></tr>
    <tr><td>6</td><td>406.4900</td></tr>
    <tr><td>7</td><td>406.4100</td></tr>
    <tr><td>8</td><td>406.1400</td></tr>
    <tr><td>9</td><td>406.2000</td></tr>
    <tr><td>10</td><td>405.7100</td></tr>
    <tr><td>11</td><td>405.8500</td></tr>
    <tr><td>12</td><td>405.6500</td></tr>
    <tr><td>13</td><td>405.6100</td></tr>
    <tr><td>14</td><td>405.8500</td></tr>
    <tr><td>15</td><td>406.4200</td></tr>
    <tr><td>16</td><td>406.2700</td></tr>
    <tr><td>17</td><td>406.1800</td></tr>
    <tr><td>18</td><td>406.3600</td></tr>
    <tr><td>19</td><td>406.6000</td></tr>
    <tr><td colspan="2" style="text-align: center;">... (more)</td></tr>
  </tbody>
</table>
</div>
HTML;
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
