<?
$assetClass = 'Crypto';
$singularAssetClass = 'Crypto pair';
$pluralAssetClass = 'Crypto pairs';
$historicalDataLink = "/docs/v2/research-environment/datasets/crypto#04-Get-Historical-Data";
$primarySymbolPy = 'btcusd';
$primarySymbolC = 'btcusd';
$primaryTicker = 'BTCUSD';
$secondarySymbol = 'ethusd';

$dataFrameImages = array();

$dataFrameImages[0] = <<<'HTML'
<div class="python dataframe-wrapper" style="font-size: 80%; white-space: nowrap">
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
      <th rowspan="5" valign="top">ETHUSD XJ</th>
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
      <th rowspan="5" valign="top">BTCUSD XJ</th>
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
<div class="python dataframe-wrapper" style="font-size: 80%; white-space: nowrap">
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
<div class="python dataframe-wrapper" style="font-size: 80%; white-space: nowrap">
<table class="dataframe python" border="0">
  <thead>
    <tr style="text-align: right;">
      <th>symbol</th>
      <th>BTCUSD XJ</th>
      <th>ETHUSD XJ</th>
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

$cSharpDataFrameImages = array();

$cSharpDataFrameImages[0] = <<<'HTML'
<div class="csharp dataframe-wrapper" style="font-size: 80%; white-space: nowrap">
<table class="dataframe csharp" border="0">
  <thead>
    <tr style="text-align: right;">
      <th>Time</th>
      <th>BTCUSD Open</th>
      <th>BTCUSD High</th>
      <th>BTCUSD Low</th>
      <th>BTCUSD Close</th>
    </tr>
  </thead>
  <tbody>
    <tr><td>2/10/2023 5:01:00 AM</td><td>21791.85</td><td>21791.85</td><td>21783.39</td><td>21783.93</td></tr>
    <tr><td>2/10/2023 5:02:00 AM</td><td>21783.93</td><td>21783.93</td><td>21765.78</td><td>21769.73</td></tr>
    <tr><td>2/10/2023 5:03:00 AM</td><td>21769.67</td><td>21777.55</td><td>21768.06</td><td>21769.62</td></tr>
    <tr><td>2/10/2023 5:04:00 AM</td><td>21771.06</td><td>21773.38</td><td>21763.68</td><td>21763.69</td></tr>
    <tr><td>2/10/2023 5:05:00 AM</td><td>21763.69</td><td>21763.8</td><td>21753.87</td><td>21761.36</td></tr>
    <tr><td>2/10/2023 5:06:00 AM</td><td>21761.36</td><td>21789.91</td><td>21761.36</td><td>21789.91</td></tr>
    <tr><td>2/10/2023 5:07:00 AM</td><td>21792.36</td><td>21802.86</td><td>21790.5</td><td>21802.86</td></tr>
    <tr><td>2/10/2023 5:08:00 AM</td><td>21802.8</td><td>21821.66</td><td>21802.8</td><td>21818.04</td></tr>
    <tr><td>2/10/2023 5:09:00 AM</td><td>21818.44</td><td>21820.83</td><td>21808.2</td><td>21811.36</td></tr>
    <tr><td>2/10/2023 5:10:00 AM</td><td>21811.38</td><td>21820.26</td><td>21811.1</td><td>21813.76</td></tr>
    <tr><td>2/10/2023 5:11:00 AM</td><td>21813.74</td><td>21819.58</td><td>21810.37</td><td>21819.02</td></tr>
    <tr><td>2/10/2023 5:12:00 AM</td><td>21818.96</td><td>21823.92</td><td>21812.25</td><td>21821.27</td></tr>
    <tr><td>2/10/2023 5:13:00 AM</td><td>21821.27</td><td>21823.02</td><td>21821.26</td><td>21822.85</td></tr>
    <tr><td>2/10/2023 5:14:00 AM</td><td>21822.39</td><td>21825.04</td><td>21821.77</td><td>21823.84</td></tr>
    <tr><td>2/10/2023 5:15:00 AM</td><td>21823.84</td><td>21827.22</td><td>21820.01</td><td>21820.01</td></tr>
    <tr><td>2/10/2023 5:16:00 AM</td><td>21820</td><td>21823.91</td><td>21809.27</td><td>21823.74</td></tr>
    <tr><td>2/10/2023 5:17:00 AM</td><td>21823.73</td><td>21827.63</td><td>21821.8</td><td>21823.84</td></tr>
    <tr><td>2/10/2023 5:18:00 AM</td><td>21823.82</td><td>21823.85</td><td>21818.57</td><td>21821.39</td></tr>
    <tr><td>2/10/2023 5:19:00 AM</td><td>21821.15</td><td>21821.84</td><td>21816.59</td><td>21821.79</td></tr>
    <tr><td>2/10/2023 5:20:00 AM</td><td>21821.8</td><td>21821.8</td><td>21809.43</td><td>21809.65</td></tr>
    <tr><td>2/10/2023 5:21:00 AM</td><td>21809.65</td><td>21809.65</td><td>21798.33</td><td>21798.33</td></tr>
    <tr><td>2/10/2023 5:22:00 AM</td><td>21797.43</td><td>21797.43</td><td>21786.36</td><td>21786.36</td></tr>
    <tr><td>2/10/2023 5:23:00 AM</td><td>21787.88</td><td>21794.82</td><td>21783.05</td><td>21793.13</td></tr>
    <tr><td>2/10/2023 5:24:00 AM</td><td>21792.34</td><td>21794.34</td><td>21783.89</td><td>21788.26</td></tr>
    <tr><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td></tr>
    <tr><td>2/20/2023 4:57:00 AM</td><td>24301.39</td><td>24310.88</td><td>24274.43</td><td>24281.31</td></tr>
    <tr><td>2/20/2023 4:58:00 AM</td><td>24301.39</td><td>24310.88</td><td>24274.43</td><td>24281.31</td></tr>
    <tr><td>2/20/2023 4:59:00 AM</td><td>24301.39</td><td>24310.88</td><td>24274.43</td><td>24281.31</td></tr>
    <tr><td>2/20/2023 5:00:00 AM</td><td>24301.39</td><td>24310.88</td><td>24274.43</td><td>24281.31</td></tr>
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
    <tr><td>0</td><td>21783.93</td></tr>
    <tr><td>1</td><td>21769.73</td></tr>
    <tr><td>2</td><td>21769.62</td></tr>
    <tr><td>3</td><td>21763.69</td></tr>
    <tr><td>4</td><td>21761.36</td></tr>
    <tr><td>5</td><td>21789.91</td></tr>
    <tr><td>6</td><td>21802.86</td></tr>
    <tr><td>7</td><td>21818.04</td></tr>
    <tr><td>8</td><td>21811.36</td></tr>
    <tr><td>9</td><td>21813.76</td></tr>
    <tr><td>10</td><td>21819.02</td></tr>
    <tr><td>11</td><td>21821.27</td></tr>
    <tr><td>12</td><td>21822.85</td></tr>
    <tr><td>13</td><td>21823.84</td></tr>
    <tr><td>14</td><td>21820.01</td></tr>
    <tr><td>15</td><td>21823.74</td></tr>
    <tr><td>16</td><td>21823.84</td></tr>
    <tr><td>17</td><td>21821.39</td></tr>
    <tr><td>18</td><td>21821.79</td></tr>
    <tr><td>19</td><td>21809.65</td></tr>
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
