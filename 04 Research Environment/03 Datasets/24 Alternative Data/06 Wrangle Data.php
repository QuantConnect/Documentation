<?
$assetClass = 'dataset';
$singularAssetClass = 'ticker';
$pluralAssetClass = 'tickers';
$historicalDataLink = "/docs/v2/research-environment/datasets/alternative-data#04-Get-Historical-Data";
$primarySymbolPy = 'vix';
$primarySymbolC = 'vix';
$primaryTicker = 'VIX';
$secondarySymbol = 'v3m';

$dataFrameImages = array();

$dataFrameImages[0] = <<<'HTML'
<div class="python dataframe-wrapper">
<table class="dataframe python" border="0">
  <thead>
    <tr style="text-align: right;">
      <th></th>
      <th></th>
      <th>close</th>
      <th>datasourceid</th>
      <th>high</th>
      <th>low</th>
      <th>open</th>
      <th>value</th>
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
    </tr>
  </thead>
  <tbody>
    <tr>
      <th rowspan="5" valign="top">VIX3M.CBOE 2S</th>
      <th>2021-01-05</th>
      <td>28.26</td>
      <td>2001</td>
      <td>29.23</td>
      <td>25.32</td>
      <td>25.32</td>
      <td>28.26</td>
    </tr>
    <tr>
      <th>2021-01-06</th>
      <td>27.25</td>
      <td>2001</td>
      <td>28.73</td>
      <td>26.68</td>
      <td>28.64</td>
      <td>27.25</td>
    </tr>
    <tr>
      <th>2021-01-07</th>
      <td>26.79</td>
      <td>2001</td>
      <td>27.67</td>
      <td>25.15</td>
      <td>26.73</td>
      <td>26.79</td>
    </tr>
    <tr>
      <th>2021-01-08</th>
      <td>25.02</td>
      <td>2001</td>
      <td>25.65</td>
      <td>25.02</td>
      <td>25.46</td>
      <td>25.02</td>
    </tr>
    <tr>
      <th>2021-01-09</th>
      <td>24.90</td>
      <td>2001</td>
      <td>25.98</td>
      <td>24.66</td>
      <td>24.98</td>
      <td>24.90</td>
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
    </tr>
    <tr>
      <th rowspan="5" valign="top">VIX.CBOE 2S</th>
      <th>2021-02-23</th>
      <td>23.45</td>
      <td>2001</td>
      <td>25.09</td>
      <td>21.96</td>
      <td>24.46</td>
      <td>23.45</td>
    </tr>
    <tr>
      <th>2021-02-24</th>
      <td>23.11</td>
      <td>2001</td>
      <td>27.01</td>
      <td>22.50</td>
      <td>22.82</td>
      <td>23.11</td>
    </tr>
    <tr>
      <th>2021-02-25</th>
      <td>21.34</td>
      <td>2001</td>
      <td>25.04</td>
      <td>21.31</td>
      <td>23.76</td>
      <td>21.34</td>
    </tr>
    <tr>
      <th>2021-02-26</th>
      <td>28.89</td>
      <td>2001</td>
      <td>31.16</td>
      <td>21.52</td>
      <td>21.73</td>
      <td>28.89</td>
    </tr>
    <tr>
      <th>2021-02-27</th>
      <td>27.95</td>
      <td>2001</td>
      <td>30.82</td>
      <td>25.23</td>
      <td>28.73</td>
      <td>27.95</td>
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
      <th>datasourceid</th>
      <th>high</th>
      <th>low</th>
      <th>open</th>
      <th>value</th>
    </tr>
    <tr>
      <th>time</th>
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
      <th>2021-01-05</th>
      <td>22.75</td>
      <td>2001</td>
      <td>25.09</td>
      <td>22.47</td>
      <td>23.31</td>
      <td>22.75</td>
    </tr>
    <tr>
      <th>2021-01-06</th>
      <td>24.09</td>
      <td>2001</td>
      <td>27.63</td>
      <td>22.43</td>
      <td>22.64</td>
      <td>24.09</td>
    </tr>
    <tr>
      <th>2021-01-07</th>
      <td>22.95</td>
      <td>2001</td>
      <td>24.47</td>
      <td>22.27</td>
      <td>23.57</td>
      <td>22.95</td>
    </tr>
    <tr>
      <th>2021-01-08</th>
      <td>21.57</td>
      <td>2001</td>
      <td>23.05</td>
      <td>21.47</td>
      <td>22.93</td>
      <td>21.57</td>
    </tr>
    <tr>
      <th>2021-01-09</th>
      <td>21.27</td>
      <td>2001</td>
      <td>22.73</td>
      <td>21.16</td>
      <td>21.65</td>
      <td>21.27</td>
    </tr>
    <tr>
      <th>...</th>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
    </tr>
    <tr>
      <th>2021-02-23</th>
      <td>23.45</td>
      <td>2001</td>
      <td>25.09</td>
      <td>21.96</td>
      <td>24.46</td>
      <td>23.45</td>
    </tr>
    <tr>
      <th>2021-02-24</th>
      <td>23.11</td>
      <td>2001</td>
      <td>27.01</td>
      <td>22.50</td>
      <td>22.82</td>
      <td>23.11</td>
    </tr>
    <tr>
      <th>2021-02-25</th>
      <td>21.34</td>
      <td>2001</td>
      <td>25.04</td>
      <td>21.31</td>
      <td>23.76</td>
      <td>21.34</td>
    </tr>
    <tr>
      <th>2021-02-26</th>
      <td>28.89</td>
      <td>2001</td>
      <td>31.16</td>
      <td>21.52</td>
      <td>21.73</td>
      <td>28.89</td>
    </tr>
    <tr>
      <th>2021-02-27</th>
      <td>27.95</td>
      <td>2001</td>
      <td>30.82</td>
      <td>25.23</td>
      <td>28.73</td>
      <td>27.95</td>
    </tr>
  </tbody>
</table>
</div>
HTML;

$dataFrameImages[2] = <<<'HTML'
<div class="python section-example-container">
    <pre>time
2021-01-05    22.75
2021-01-06    24.09
2021-01-07    22.95
2021-01-08    21.57
2021-01-09    21.27
              ...
2021-02-23    23.45
2021-02-24    23.11
2021-02-25    21.34
2021-02-26    28.89
2021-02-27    27.95
Name: close, dtype: float64</pre>
</div>
HTML;

$dataFrameImages[3] = <<<'HTML'
<div class="python dataframe-wrapper">
<table class="dataframe python" border="0">
  <thead>
    <tr style="text-align: right;">
      <th>symbol</th>
      <th>VIX.CBOE 2S</th>
      <th>VIX3M.CBOE 2S</th>
    </tr>
    <tr>
      <th>time</th>
      <th></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th>2021-01-05</th>
      <td>22.75</td>
      <td>28.26</td>
    </tr>
    <tr>
      <th>2021-01-06</th>
      <td>24.09</td>
      <td>27.25</td>
    </tr>
    <tr>
      <th>2021-01-07</th>
      <td>22.95</td>
      <td>26.79</td>
    </tr>
    <tr>
      <th>2021-01-08</th>
      <td>21.57</td>
      <td>25.02</td>
    </tr>
    <tr>
      <th>2021-01-09</th>
      <td>21.27</td>
      <td>24.90</td>
    </tr>
    <tr>
      <th>...</th>
      <td>...</td>
      <td>...</td>
    </tr>
    <tr>
      <th>2021-02-23</th>
      <td>23.45</td>
      <td>24.56</td>
    </tr>
    <tr>
      <th>2021-02-24</th>
      <td>23.11</td>
      <td>24.23</td>
    </tr>
    <tr>
      <th>2021-02-25</th>
      <td>21.34</td>
      <td>23.87</td>
    </tr>
    <tr>
      <th>2021-02-26</th>
      <td>28.89</td>
      <td>28.45</td>
    </tr>
    <tr>
      <th>2021-02-27</th>
      <td>27.95</td>
      <td>27.30</td>
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
      <th>VIX Open</th>
      <th>VIX High</th>
      <th>VIX Low</th>
      <th>VIX Close</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>2/1/2023 12:00:00 AM</td>
      <td>20.120000</td>
      <td>20.700000</td>
      <td>19.130000</td>
      <td>19.400000</td>
    </tr>
    <tr>
      <td>2/2/2023 12:00:00 AM</td>
      <td>19.620000</td>
      <td>20.040000</td>
      <td>17.700000</td>
      <td>17.870000</td>
    </tr>
    <tr>
      <td>2/3/2023 12:00:00 AM</td>
      <td>17.740000</td>
      <td>19.250000</td>
      <td>17.060000</td>
      <td>18.730000</td>
    </tr>
    <tr>
      <td>2/4/2023 12:00:00 AM</td>
      <td>18.570000</td>
      <td>19.300000</td>
      <td>17.930000</td>
      <td>18.330000</td>
    </tr>
    <tr>
      <td>2/7/2023 12:00:00 AM</td>
      <td>19.230000</td>
      <td>19.810000</td>
      <td>19.210000</td>
      <td>19.430000</td>
    </tr>
    <tr>
      <td>2/8/2023 12:00:00 AM</td>
      <td>19.540000</td>
      <td>19.990000</td>
      <td>18.430000</td>
      <td>18.660000</td>
    </tr>
    <tr>
      <td>2/9/2023 12:00:00 AM</td>
      <td>18.880000</td>
      <td>20.120000</td>
      <td>18.550000</td>
      <td>19.630000</td>
    </tr>
    <tr>
      <td>2/10/2023 12:00:00 AM</td>
      <td>19.240000</td>
      <td>21.080000</td>
      <td>19.020000</td>
      <td>20.710000</td>
    </tr>
    <tr>
      <td>2/11/2023 12:00:00 AM</td>
      <td>20.740000</td>
      <td>21.940000</td>
      <td>20.440000</td>
      <td>20.530000</td>
    </tr>
    <tr>
      <td>2/14/2023 12:00:00 AM</td>
      <td>21.660000</td>
      <td>21.690000</td>
      <td>20.330000</td>
      <td>20.340000</td>
    </tr>
    <tr>
      <td>2/15/2023 12:00:00 AM</td>
      <td>20.720000</td>
      <td>20.750000</td>
      <td>18.480000</td>
      <td>18.910000</td>
    </tr>
    <tr>
      <td>2/16/2023 12:00:00 AM</td>
      <td>19.370000</td>
      <td>19.410000</td>
      <td>18.110000</td>
      <td>18.230000</td>
    </tr>
    <tr>
      <td>2/17/2023 12:00:00 AM</td>
      <td>18.260000</td>
      <td>20.270000</td>
      <td>18.110000</td>
      <td>20.170000</td>
    </tr>
    <tr>
      <td>2/18/2023 12:00:00 AM</td>
      <td>20.940000</td>
      <td>21.300000</td>
      <td>19.820000</td>
      <td>20.020000</td>
    </tr>
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
    <tr>
      <td>0</td>
      <td>19.400000</td>
    </tr>
    <tr>
      <td>1</td>
      <td>17.870000</td>
    </tr>
    <tr>
      <td>2</td>
      <td>18.730000</td>
    </tr>
    <tr>
      <td>3</td>
      <td>18.330000</td>
    </tr>
    <tr>
      <td>4</td>
      <td>19.430000</td>
    </tr>
    <tr>
      <td>5</td>
      <td>18.660000</td>
    </tr>
    <tr>
      <td>6</td>
      <td>19.630000</td>
    </tr>
    <tr>
      <td>7</td>
      <td>20.710000</td>
    </tr>
    <tr>
      <td>8</td>
      <td>20.530000</td>
    </tr>
    <tr>
      <td>9</td>
      <td>20.340000</td>
    </tr>
    <tr>
      <td>10</td>
      <td>18.910000</td>
    </tr>
    <tr>
      <td>11</td>
      <td>18.230000</td>
    </tr>
    <tr>
      <td>12</td>
      <td>20.170000</td>
    </tr>
    <tr>
      <td>13</td>
      <td>20.020000</td>
    </tr>
  </tbody>
</table>
</div>
HTML;
$dataFrameColumnName = 'close';
$columnNameEnglish = 'close';
$supportsTrades = false;
$supportsQuotes = false;
$supportsTicks = false;
$supportsAltData = true;
$supportsOpenInterest = false;
$supportsOptionHistory = false;
$supportsFutureHistory = false;
$contractExpiryDate = '';
include(DOCS_RESOURCES."/datasets/wrangle-data.php");
?>
