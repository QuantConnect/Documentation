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
<div class="python dataframe-wrapper" style="font-size: 80%; white-space: nowrap">
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
<div class="python dataframe-wrapper" style="font-size: 80%; white-space: nowrap">
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
<div class="python dataframe-wrapper" style="font-size: 80%; white-space: nowrap">
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

$cSharpDataFrameImages = array();

$cSharpDataFrameImages[0] = <<<'HTML'
<div class="csharp dataframe-wrapper" style="font-size: 80%; white-space: nowrap">
<table class="dataframe csharp" border="0">
  <thead>
    <tr style="text-align: right;">
      <th>Time</th>
      <th>SPX Open</th>
      <th>SPX High</th>
      <th>SPX Low</th>
      <th>SPX Close</th>
    </tr>
  </thead>
  <tbody>
    <tr><td>2/10/2023 8:31:00 AM</td><td>4068.35</td><td>4076.04</td><td>4067.9</td><td>4076.04</td></tr>
    <tr><td>2/10/2023 8:32:00 AM</td><td>4076.29</td><td>4076.29</td><td>4073.78</td><td>4075.17</td></tr>
    <tr><td>2/10/2023 8:33:00 AM</td><td>4074.5</td><td>4074.9</td><td>4072.48</td><td>4072.48</td></tr>
    <tr><td>2/10/2023 8:34:00 AM</td><td>4072.46</td><td>4077.11</td><td>4072.46</td><td>4076.29</td></tr>
    <tr><td>2/10/2023 8:35:00 AM</td><td>4076.04</td><td>4077.54</td><td>4074.76</td><td>4075.77</td></tr>
    <tr><td>2/10/2023 8:36:00 AM</td><td>4075.92</td><td>4079.38</td><td>4075.12</td><td>4079.38</td></tr>
    <tr><td>2/10/2023 8:37:00 AM</td><td>4079.56</td><td>4079.56</td><td>4075.5</td><td>4075.5</td></tr>
    <tr><td>2/10/2023 8:38:00 AM</td><td>4075.63</td><td>4077.41</td><td>4074.97</td><td>4074.97</td></tr>
    <tr><td>2/10/2023 8:39:00 AM</td><td>4074.91</td><td>4074.91</td><td>4071.65</td><td>4071.95</td></tr>
    <tr><td>2/10/2023 8:40:00 AM</td><td>4071.9</td><td>4074.03</td><td>4071.9</td><td>4072.66</td></tr>
    <tr><td>2/10/2023 8:41:00 AM</td><td>4072.71</td><td>4072.74</td><td>4067.69</td><td>4067.71</td></tr>
    <tr><td>2/10/2023 8:42:00 AM</td><td>4067.45</td><td>4069.55</td><td>4066.85</td><td>4068.36</td></tr>
    <tr><td>2/10/2023 8:43:00 AM</td><td>4068.5</td><td>4068.66</td><td>4064.8</td><td>4066.14</td></tr>
    <tr><td>2/10/2023 8:44:00 AM</td><td>4066.19</td><td>4068.55</td><td>4065.6</td><td>4066.75</td></tr>
    <tr><td>2/10/2023 8:45:00 AM</td><td>4066.8</td><td>4069.39</td><td>4066.8</td><td>4069.18</td></tr>
    <tr><td>2/10/2023 8:46:00 AM</td><td>4069.12</td><td>4076.4</td><td>4066.91</td><td>4074.94</td></tr>
    <tr><td>2/10/2023 8:47:00 AM</td><td>4074.89</td><td>4075.26</td><td>4072.03</td><td>4073.24</td></tr>
    <tr><td>2/10/2023 8:48:00 AM</td><td>4073.25</td><td>4073.25</td><td>4069.3</td><td>4071.43</td></tr>
    <tr><td>2/10/2023 8:49:00 AM</td><td>4071.44</td><td>4074.08</td><td>4070.49</td><td>4073.47</td></tr>
    <tr><td>2/10/2023 8:50:00 AM</td><td>4073.71</td><td>4076.88</td><td>4073.7</td><td>4076.3</td></tr>
    <tr><td>2/10/2023 8:51:00 AM</td><td>4076.4</td><td>4079.73</td><td>4073.26</td><td>4079.73</td></tr>
    <tr><td>2/10/2023 8:52:00 AM</td><td>4079.39</td><td>4079.68</td><td>4077.7</td><td>4078.78</td></tr>
    <tr><td>2/10/2023 8:53:00 AM</td><td>4078.93</td><td>4079.28</td><td>4074.79</td><td>4074.79</td></tr>
    <tr><td>2/10/2023 8:54:00 AM</td><td>4074.77</td><td>4075.3</td><td>4072.99</td><td>4075.06</td></tr>
    <tr><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td></tr>
    <tr><td>2/17/2023 2:57:00 PM</td><td>4079.18</td><td>4079.7</td><td>4077.98</td><td>4077.98</td></tr>
    <tr><td>2/17/2023 2:58:00 PM</td><td>4078.02</td><td>4078.04</td><td>4076.34</td><td>4076.77</td></tr>
    <tr><td>2/17/2023 2:59:00 PM</td><td>4078.56</td><td>4078.56</td><td>4076.18</td><td>4077.87</td></tr>
    <tr><td>2/17/2023 3:00:00 PM</td><td>4077.74</td><td>4079.25</td><td>4077.13</td><td>4078.98</td></tr>
  </tbody>
</table>
</div>
HTML;

$cSharpDataFrameImages[1] = <<<'HTML'
<div class="csharp dataframe-wrapper" style="font-size: 80%; white-space: nowrap">
<table class="dataframe csharp" border="0">
  <thead>
    <tr style="text-align: right;">
      <th><i>index</i></th>
      <th>value</th>
    </tr>
  </thead>
  <tbody>
    <tr><td>0</td><td>4076.04</td></tr>
    <tr><td>1</td><td>4075.17</td></tr>
    <tr><td>2</td><td>4072.48</td></tr>
    <tr><td>3</td><td>4076.29</td></tr>
    <tr><td>4</td><td>4075.77</td></tr>
    <tr><td>5</td><td>4079.38</td></tr>
    <tr><td>6</td><td>4075.5</td></tr>
    <tr><td>7</td><td>4074.97</td></tr>
    <tr><td>8</td><td>4071.95</td></tr>
    <tr><td>9</td><td>4072.66</td></tr>
    <tr><td>10</td><td>4067.71</td></tr>
    <tr><td>11</td><td>4068.36</td></tr>
    <tr><td>12</td><td>4066.14</td></tr>
    <tr><td>13</td><td>4066.75</td></tr>
    <tr><td>14</td><td>4069.18</td></tr>
    <tr><td>15</td><td>4074.94</td></tr>
    <tr><td>16</td><td>4073.24</td></tr>
    <tr><td>17</td><td>4071.43</td></tr>
    <tr><td>18</td><td>4073.47</td></tr>
    <tr><td>19</td><td>4076.3</td></tr>
    <tr><td colspan="2" style="text-align: center;">... (more)</td></tr>
  </tbody>
</table>
</div>
HTML;
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
