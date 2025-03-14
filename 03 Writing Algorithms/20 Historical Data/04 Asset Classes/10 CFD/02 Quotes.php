<?
$symbolC = "var symbol = AddCfd(\"XAUUSD\").Symbol;";
$symbolPy = "symbol = self.add_cfd('XAUUSD').symbol";
$assetClass = "CFD";
$dataTypeLink = "/docs/v2/writing-algorithms/securities/asset-classes/cfd/handling-data#02-Quotes";
$dataType = "QuoteBar";
$supportsQuoteSize = false;
$dataFrame = "<div class='dataframe-wrapper'>
<table class=dataframe python'>
  <thead>
    <tr style='text-align: right;'>
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
      <th rowspan='5' valign='top'>XAUUSD</th>
      <th>2024-12-18 23:56:00</th>
      <td>2607.96</td>
      <td>2608.53</td>
      <td>2607.91</td>
      <td>2608.53</td>
      <td>2607.49</td>
      <td>2607.99</td>
      <td>2607.43</td>
      <td>2607.99</td>
      <td>2607.725</td>
      <td>2608.26</td>
      <td>2607.670</td>
      <td>2608.260</td>
    </tr>
    <tr>
      <th>2024-12-18 23:57:00</th>
      <td>2608.31</td>
      <td>2608.36</td>
      <td>2607.92</td>
      <td>2607.96</td>
      <td>2607.74</td>
      <td>2607.80</td>
      <td>2607.45</td>
      <td>2607.49</td>
      <td>2608.025</td>
      <td>2608.08</td>
      <td>2607.685</td>
      <td>2607.725</td>
    </tr>
    <tr>
      <th>2024-12-18 23:58:00</th>
      <td>2608.47</td>
      <td>2608.55</td>
      <td>2608.28</td>
      <td>2608.31</td>
      <td>2607.89</td>
      <td>2608.01</td>
      <td>2607.71</td>
      <td>2607.74</td>
      <td>2608.180</td>
      <td>2608.28</td>
      <td>2607.995</td>
      <td>2608.025</td>
    </tr>
    <tr>
      <th>2024-12-18 23:59:00</th>
      <td>2609.48</td>
      <td>2609.70</td>
      <td>2608.43</td>
      <td>2608.47</td>
      <td>2609.05</td>
      <td>2609.12</td>
      <td>2607.89</td>
      <td>2607.89</td>
      <td>2609.265</td>
      <td>2609.41</td>
      <td>2608.160</td>
      <td>2608.180</td>
    </tr>
    <tr>
      <th>2024-12-19 00:00:00</th>
      <td>2609.48</td>
      <td>2610.17</td>
      <td>2609.48</td>
      <td>2609.48</td>
      <td>2609.10</td>
      <td>2609.65</td>
      <td>2609.03</td>
      <td>2609.05</td>
      <td>2609.290</td>
      <td>2609.91</td>
      <td>2609.255</td>
      <td>2609.265</td>
    </tr>
  </tbody>
</table>
</div>";

$series = "symbol  time               
XAUUSD  2024-12-18 23:56:00    0.47
        2024-12-18 23:57:00    0.57
        2024-12-18 23:58:00    0.58
        2024-12-18 23:59:00    0.43
        2024-12-19 00:00:00    0.38
dtype: float64";

include(DOCS_RESOURCES."/history/quotebars.php");
?>
