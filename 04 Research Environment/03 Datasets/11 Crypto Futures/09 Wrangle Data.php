<?
$assetClass = 'Crypto Future';
$singularAssetClass = 'Crypto Futures contract';
$pluralAssetClass = 'Crypto Futures contracts';
$historicalDataLink = "/docs/v2/research-environment/datasets/crypto-futures#04-Get-Historical-Data";
$primarySymbolPy = 'btcusd';
$primarySymbolC = 'btcusd';
$primaryTicker = 'BTCUSD';
$secondarySymbol = 'ethusd';

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
      <th rowspan="5" valign="top">ETHUSD 2PM</th>
      <th>2021-01-01 05:01:00</th>
      <td>744.17</td>
      <td>744.27</td>
      <td>743.41</td>
      <td>743.58</td>
      <td>0.250185</td>
      <td>743.94</td>
      <td>744.11</td>
      <td>743.28</td>
      <td>743.47</td>
      <td>1.800000</td>
      <td>744.18</td>
      <td>744.24</td>
      <td>743.40</td>
      <td>743.58</td>
      <td>133.964910</td>
    </tr>
    <tr>
      <th>2021-01-01 05:02:00</th>
      <td>743.81</td>
      <td>744.55</td>
      <td>743.81</td>
      <td>744.17</td>
      <td>0.888586</td>
      <td>743.80</td>
      <td>744.25</td>
      <td>743.77</td>
      <td>743.94</td>
      <td>0.267552</td>
      <td>743.80</td>
      <td>744.51</td>
      <td>743.80</td>
      <td>744.17</td>
      <td>111.379343</td>
    </tr>
    <tr>
      <th>2021-01-01 05:03:00</th>
      <td>743.02</td>
      <td>744.02</td>
      <td>743.02</td>
      <td>743.81</td>
      <td>24.000000</td>
      <td>743.01</td>
      <td>743.82</td>
      <td>743.01</td>
      <td>743.80</td>
      <td>0.200230</td>
      <td>743.02</td>
      <td>743.82</td>
      <td>743.01</td>
      <td>743.81</td>
      <td>108.750352</td>
    </tr>
    <tr>
      <th>2021-01-01 05:04:00</th>
      <td>741.66</td>
      <td>743.02</td>
      <td>741.66</td>
      <td>743.02</td>
      <td>1.800000</td>
      <td>741.50</td>
      <td>743.01</td>
      <td>741.50</td>
      <td>743.01</td>
      <td>0.157696</td>
      <td>741.50</td>
      <td>743.01</td>
      <td>741.50</td>
      <td>743.01</td>
      <td>280.399706</td>
    </tr>
    <tr>
      <th>2021-01-01 05:05:00</th>
      <td>742.71</td>
      <td>743.02</td>
      <td>741.62</td>
      <td>741.66</td>
      <td>2.510539</td>
      <td>742.46</td>
      <td>743.01</td>
      <td>741.50</td>
      <td>741.50</td>
      <td>2.000000</td>
      <td>742.69</td>
      <td>743.02</td>
      <td>741.62</td>
      <td>741.63</td>
      <td>120.165379</td>
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
      <th rowspan="5" valign="top">BTCUSD 2PM</th>
      <th>2021-02-01 04:56:00</th>
      <td>33626.70</td>
      <td>33667.29</td>
      <td>33593.23</td>
      <td>33612.77</td>
      <td>1.900000</td>
      <td>33626.69</td>
      <td>33660.54</td>
      <td>33588.24</td>
      <td>33606.35</td>
      <td>0.020804</td>
      <td>33626.70</td>
      <td>33664.27</td>
      <td>33589.22</td>
      <td>33612.42</td>
      <td>11.218419</td>
    </tr>
    <tr>
      <th>2021-02-01 04:57:00</th>
      <td>33569.52</td>
      <td>33626.70</td>
      <td>33559.43</td>
      <td>33626.70</td>
      <td>0.012492</td>
      <td>33565.49</td>
      <td>33626.69</td>
      <td>33558.72</td>
      <td>33626.69</td>
      <td>0.100000</td>
      <td>33569.52</td>
      <td>33626.70</td>
      <td>33559.44</td>
      <td>33626.70</td>
      <td>1.547286</td>
    </tr>
    <tr>
      <th>2021-02-01 04:58:00</th>
      <td>33578.82</td>
      <td>33602.37</td>
      <td>33564.16</td>
      <td>33569.52</td>
      <td>1.580643</td>
      <td>33578.81</td>
      <td>33593.02</td>
      <td>33563.81</td>
      <td>33565.49</td>
      <td>0.115128</td>
      <td>33578.82</td>
      <td>33602.37</td>
      <td>33565.47</td>
      <td>33569.52</td>
      <td>4.658471</td>
    </tr>
    <tr>
      <th>2021-02-01 04:59:00</th>
      <td>33569.08</td>
      <td>33650.37</td>
      <td>33564.22</td>
      <td>33578.82</td>
      <td>0.500000</td>
      <td>33564.82</td>
      <td>33641.04</td>
      <td>33563.81</td>
      <td>33578.81</td>
      <td>0.043120</td>
      <td>33568.86</td>
      <td>33650.37</td>
      <td>33563.82</td>
      <td>33578.82</td>
      <td>7.315090</td>
    </tr>
    <tr>
      <th>2021-02-01 05:00:00</th>
      <td>33581.76</td>
      <td>33602.80</td>
      <td>33563.91</td>
      <td>33569.08</td>
      <td>0.013800</td>
      <td>33579.89</td>
      <td>33597.63</td>
      <td>33563.81</td>
      <td>33564.82</td>
      <td>0.050000</td>
      <td>33581.76</td>
      <td>33600.07</td>
      <td>33563.90</td>
      <td>33565.84</td>
      <td>7.636029</td>
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
      <th>2021-01-01 05:01:00</th>
      <td>29409.07</td>
      <td>29409.07</td>
      <td>29367.25</td>
      <td>29388.93</td>
      <td>0.371000</td>
      <td>29400.00</td>
      <td>29400.05</td>
      <td>29365.25</td>
      <td>29388.00</td>
      <td>0.024050</td>
      <td>29409.07</td>
      <td>29409.07</td>
      <td>29367.25</td>
      <td>29388.93</td>
      <td>14.852419</td>
    </tr>
    <tr>
      <th>2021-01-01 05:02:00</th>
      <td>29408.07</td>
      <td>29420.00</td>
      <td>29370.49</td>
      <td>29409.07</td>
      <td>0.012560</td>
      <td>29400.00</td>
      <td>29420.00</td>
      <td>29369.00</td>
      <td>29400.00</td>
      <td>0.510000</td>
      <td>29408.00</td>
      <td>29420.00</td>
      <td>29370.00</td>
      <td>29409.07</td>
      <td>11.396783</td>
    </tr>
    <tr>
      <th>2021-01-01 05:03:00</th>
      <td>29370.00</td>
      <td>29412.00</td>
      <td>29345.43</td>
      <td>29408.07</td>
      <td>0.100000</td>
      <td>29360.00</td>
      <td>29403.00</td>
      <td>29340.00</td>
      <td>29400.00</td>
      <td>0.880000</td>
      <td>29365.00</td>
      <td>29412.00</td>
      <td>29345.00</td>
      <td>29408.07</td>
      <td>18.285416</td>
    </tr>
    <tr>
      <th>2021-01-01 05:04:00</th>
      <td>29350.51</td>
      <td>29380.00</td>
      <td>29325.00</td>
      <td>29370.00</td>
      <td>0.500000</td>
      <td>29340.01</td>
      <td>29370.00</td>
      <td>29321.00</td>
      <td>29360.00</td>
      <td>0.150000</td>
      <td>29345.26</td>
      <td>29375.00</td>
      <td>29323.00</td>
      <td>29365.00</td>
      <td>22.106951</td>
    </tr>
    <tr>
      <th>2021-01-01 05:05:00</th>
      <td>29390.00</td>
      <td>29395.00</td>
      <td>29345.00</td>
      <td>29350.51</td>
      <td>1.200000</td>
      <td>29380.00</td>
      <td>29385.00</td>
      <td>29335.00</td>
      <td>29340.01</td>
      <td>0.300000</td>
      <td>29385.00</td>
      <td>29395.00</td>
      <td>29340.00</td>
      <td>29345.26</td>
      <td>15.473208</td>
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
      <th>2021-02-01 04:56:00</th>
      <td>33626.70</td>
      <td>33667.29</td>
      <td>33593.23</td>
      <td>33612.77</td>
      <td>1.900000</td>
      <td>33626.69</td>
      <td>33660.54</td>
      <td>33588.24</td>
      <td>33606.35</td>
      <td>0.020804</td>
      <td>33626.70</td>
      <td>33664.27</td>
      <td>33589.22</td>
      <td>33612.42</td>
      <td>11.218419</td>
    </tr>
    <tr>
      <th>2021-02-01 04:57:00</th>
      <td>33569.52</td>
      <td>33626.70</td>
      <td>33559.43</td>
      <td>33626.70</td>
      <td>0.012492</td>
      <td>33565.49</td>
      <td>33626.69</td>
      <td>33558.72</td>
      <td>33626.69</td>
      <td>0.100000</td>
      <td>33569.52</td>
      <td>33626.70</td>
      <td>33559.44</td>
      <td>33626.70</td>
      <td>1.547286</td>
    </tr>
    <tr>
      <th>2021-02-01 04:58:00</th>
      <td>33578.82</td>
      <td>33602.37</td>
      <td>33564.16</td>
      <td>33569.52</td>
      <td>1.580643</td>
      <td>33578.81</td>
      <td>33593.02</td>
      <td>33563.81</td>
      <td>33565.49</td>
      <td>0.115128</td>
      <td>33578.82</td>
      <td>33602.37</td>
      <td>33565.47</td>
      <td>33569.52</td>
      <td>4.658471</td>
    </tr>
    <tr>
      <th>2021-02-01 04:59:00</th>
      <td>33569.08</td>
      <td>33650.37</td>
      <td>33564.22</td>
      <td>33578.82</td>
      <td>0.500000</td>
      <td>33564.82</td>
      <td>33641.04</td>
      <td>33563.81</td>
      <td>33578.81</td>
      <td>0.043120</td>
      <td>33568.86</td>
      <td>33650.37</td>
      <td>33563.82</td>
      <td>33578.82</td>
      <td>7.315090</td>
    </tr>
    <tr>
      <th>2021-02-01 05:00:00</th>
      <td>33581.76</td>
      <td>33602.80</td>
      <td>33563.91</td>
      <td>33569.08</td>
      <td>0.013800</td>
      <td>33579.89</td>
      <td>33597.63</td>
      <td>33563.81</td>
      <td>33564.82</td>
      <td>0.050000</td>
      <td>33581.76</td>
      <td>33600.07</td>
      <td>33563.90</td>
      <td>33565.84</td>
      <td>7.636029</td>
    </tr>
  </tbody>
</table>
</div>
HTML;

$dataFrameImages[2] = <<<'HTML'
<div class="python section-example-container">
    <pre>time
2021-01-01 05:01:00    29409.07
2021-01-01 05:02:00    29408.00
2021-01-01 05:03:00    29365.00
2021-01-01 05:04:00    29345.26
2021-01-01 05:05:00    29385.00
                         ...
2021-02-01 04:56:00    33626.70
2021-02-01 04:57:00    33569.52
2021-02-01 04:58:00    33578.82
2021-02-01 04:59:00    33568.86
2021-02-01 05:00:00    33581.76
Name: close, dtype: float64</pre>
</div>
HTML;

$dataFrameImages[3] = <<<'HTML'
<div class="python dataframe-wrapper">
<table class="dataframe python" border="0">
  <thead>
    <tr style="text-align: right;">
      <th>symbol</th>
      <th>BTCUSD 2PM</th>
      <th>ETHUSD 2PM</th>
    </tr>
    <tr>
      <th>time</th>
      <th></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th>2021-01-01 05:01:00</th>
      <td>29409.07</td>
      <td>744.18</td>
    </tr>
    <tr>
      <th>2021-01-01 05:02:00</th>
      <td>29408.00</td>
      <td>743.80</td>
    </tr>
    <tr>
      <th>2021-01-01 05:03:00</th>
      <td>29365.00</td>
      <td>743.02</td>
    </tr>
    <tr>
      <th>2021-01-01 05:04:00</th>
      <td>29345.26</td>
      <td>741.50</td>
    </tr>
    <tr>
      <th>2021-01-01 05:05:00</th>
      <td>29385.00</td>
      <td>742.69</td>
    </tr>
    <tr>
      <th>...</th>
      <td>...</td>
      <td>...</td>
    </tr>
    <tr>
      <th>2021-02-01 04:56:00</th>
      <td>33626.70</td>
      <td>1371.55</td>
    </tr>
    <tr>
      <th>2021-02-01 04:57:00</th>
      <td>33569.52</td>
      <td>1370.82</td>
    </tr>
    <tr>
      <th>2021-02-01 04:58:00</th>
      <td>33578.82</td>
      <td>1371.20</td>
    </tr>
    <tr>
      <th>2021-02-01 04:59:00</th>
      <td>33568.86</td>
      <td>1370.95</td>
    </tr>
    <tr>
      <th>2021-02-01 05:00:00</th>
      <td>33581.76</td>
      <td>1371.40</td>
    </tr>
  </tbody>
</table>
</div>
HTML;

$cSharpDataFrameImages = array(
    'https://cdn.quantconnect.com/i/tu/crypto-future-research-data-c-1.png',
    'https://cdn.quantconnect.com/i/tu/crypto-future-research-data-c-2.png'
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
