<?
$symbolC = "var index = AddIndex(\"SPX\");
        var symbol = OptionChain(index.Symbol).OrderBy(c => c.OpenInterest).Last().Symbol;";
$symbolPy = "index = self.add_index('SPX')
        symbol = sorted(self.option_chain(index.symbol), key=lambda c: c.open_interest)[-1].symbol";
$assetClass = "IndexOption";
$dataTypeLink = "/docs/v2/writing-algorithms/securities/asset-classes/index-options/handling-data#03-Quotes";
$dataType = "QuoteBar";
$supportsQuoteSize = true;
$dataFrame = "<div class='dataframe-wrapper'>
<table class='dataframe python'>
  <thead>
    <tr style='text-align: right;'>
      <th></th>
      <th></th>
      <th></th>
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
      <th>expiry</th>
      <th>strike</th>
      <th>type</th>
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
      <th rowspan='5' valign='top'>2024-12-20</th>
      <th rowspan='5' valign='top'>4000.0</th>
      <th rowspan='5' valign='top'>1</th>
      <th rowspan='5' valign='top'>SPX 32NKZCP89T8EM|SPX 31</th>
      <th>2024-12-18 15:11:00</th>
      <td>1.45</td>
      <td>9.8</td>
      <td>1.15</td>
      <td>1.15</td>
      <td>11.0</td>
      <td>0.90</td>
      <td>0.90</td>
      <td>0.3</td>
      <td>0.90</td>
      <td>87.0</td>
      <td>1.175</td>
      <td>5.350</td>
      <td>0.725</td>
      <td>1.025</td>
    </tr>
    <tr>
      <th>2024-12-18 15:12:00</th>
      <td>1.40</td>
      <td>9.6</td>
      <td>1.40</td>
      <td>1.45</td>
      <td>10.0</td>
      <td>0.85</td>
      <td>0.95</td>
      <td>0.7</td>
      <td>0.90</td>
      <td>63.0</td>
      <td>1.125</td>
      <td>5.275</td>
      <td>1.050</td>
      <td>1.175</td>
    </tr>
    <tr>
      <th>2024-12-18 15:13:00</th>
      <td>1.40</td>
      <td>4.9</td>
      <td>1.40</td>
      <td>1.40</td>
      <td>122.0</td>
      <td>0.80</td>
      <td>0.90</td>
      <td>0.3</td>
      <td>0.85</td>
      <td>104.0</td>
      <td>1.100</td>
      <td>2.900</td>
      <td>0.850</td>
      <td>1.125</td>
    </tr>
    <tr>
      <th>2024-12-18 15:14:00</th>
      <td>1.25</td>
      <td>4.9</td>
      <td>1.25</td>
      <td>1.40</td>
      <td>59.0</td>
      <td>0.60</td>
      <td>0.80</td>
      <td>0.3</td>
      <td>0.80</td>
      <td>95.0</td>
      <td>0.925</td>
      <td>2.850</td>
      <td>0.775</td>
      <td>1.100</td>
    </tr>
    <tr>
      <th>2024-12-18 15:15:00</th>
      <td>1.05</td>
      <td>4.9</td>
      <td>1.00</td>
      <td>1.25</td>
      <td>48.0</td>
      <td>0.60</td>
      <td>0.85</td>
      <td>0.3</td>
      <td>0.60</td>
      <td>10.0</td>
      <td>0.825</td>
      <td>2.875</td>
      <td>0.650</td>
      <td>0.925</td>
    </tr>
  </tbody>
</table>
</div>";

$series = "expiry      strike  type  symbol                    time               
2024-12-20  4000.0  1     SPX 32NKZCP89T8EM|SPX 31  2024-12-18 15:11:00    0.55
                                                    2024-12-18 15:12:00    0.55
                                                    2024-12-18 15:13:00    0.60
                                                    2024-12-18 15:14:00    0.65
                                                    2024-12-18 15:15:00    0.45
dtype: float64";

include(DOCS_RESOURCES."/history/quotebars.php");
?>

<p>Request minute, hour, or daily resolution data. Otherwise, the history request won't return any data.</p>
