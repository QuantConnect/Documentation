<?
$symbolC = "var symbol = AddEquity(\"SPY\").Symbol;";
$symbolPy = "symbol = self.add_equity('SPY').symbol";
$assetClass = "USEquity";
$dataTypeLink = "/docs/v2/writing-algorithms/securities/asset-classes/us-equity/handling-data#04-Quotes";
$dataType = "QuoteBar";
$supportsQuoteSize = true;
$dataFrame = "<div class='dataframe-wrapper'>
  <table class='dataframe python'>
    <thead>
      <tr style='text-align: right;'>
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
      </tr>
    </thead>
    <tbody>
      <tr>
        <th rowspan='5' valign='top'>SPY</th>
        <th>2024-12-18 15:56:00</th>
        <td>588.65</td>
        <td>590.44</td>
        <td>588.60</td>
        <td>590.42</td>
        <td>10700.0</td>
        <td>588.61</td>
        <td>590.40</td>
        <td>588.56</td>
        <td>590.38</td>
        <td>700.0</td>
        <td>588.630</td>
        <td>590.420</td>
        <td>588.580</td>
        <td>590.400</td>
      </tr>
      <tr>
        <th>2024-12-18 15:57:00</th>
        <td>588.37</td>
        <td>588.92</td>
        <td>588.25</td>
        <td>588.65</td>
        <td>100.0</td>
        <td>588.34</td>
        <td>588.89</td>
        <td>588.24</td>
        <td>588.61</td>
        <td>100.0</td>
        <td>588.355</td>
        <td>588.905</td>
        <td>588.245</td>
        <td>588.630</td>
      </tr>
      <tr>
        <th>2024-12-18 15:58:00</th>
        <td>588.13</td>
        <td>588.50</td>
        <td>588.08</td>
        <td>588.37</td>
        <td>100.0</td>
        <td>588.11</td>
        <td>588.47</td>
        <td>588.06</td>
        <td>588.34</td>
        <td>500.0</td>
        <td>588.120</td>
        <td>588.485</td>
        <td>588.070</td>
        <td>588.355</td>
      </tr>
      <tr>
        <th>2024-12-18 15:59:00</th>
        <td>587.93</td>
        <td>588.34</td>
        <td>587.70</td>
        <td>588.13</td>
        <td>8000.0</td>
        <td>587.92</td>
        <td>588.31</td>
        <td>587.68</td>
        <td>588.11</td>
        <td>100.0</td>
        <td>587.925</td>
        <td>588.325</td>
        <td>587.690</td>
        <td>588.120</td>
      </tr>
      <tr>
        <th>2024-12-18 16:00:00</th>
        <td>586.33</td>
        <td>587.95</td>
        <td>585.89</td>
        <td>587.93</td>
        <td>200.0</td>
        <td>586.30</td>
        <td>587.93</td>
        <td>585.88</td>
        <td>587.92</td>
        <td>74700.0</td>
        <td>586.315</td>
        <td>587.940</td>
        <td>585.885</td>
        <td>587.925</td>
      </tr>
    </tbody>
  </table>
</div>";

$series = "symbol  time               
SPY     2024-12-18 15:56:00    0.04
        2024-12-18 15:57:00    0.03
        2024-12-18 15:58:00    0.02
        2024-12-18 15:59:00    0.01
        2024-12-18 16:00:00    0.03
dtype: float64";

include(DOCS_RESOURCES."/history/quotebars.php");
?>

<p>Request second or minute resolution data. Otherwise, the history request won't return any data.</p>
