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

$cSharpDataFrameImages = array(
    'https://cdn.quantconnect.com/i/tu/alt-data-research-data-c-1.png',
    'https://cdn.quantconnect.com/i/tu/alt-data-research-data-c-2.png'
);
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
