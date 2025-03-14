<?
$symbolC = "var equity = AddEquity(\"SPY\", dataNormalizationMode: DataNormalizationMode.Raw);
        var symbol = OptionChain(equity.Symbol).OrderBy(c => c.OpenInterest).Last().Symbol;";
$symbolPy = "equity = self.add_equity('SPY', data_normalization_mode=DataNormalizationMode.RAW)
        symbol = sorted(self.option_chain(equity.symbol), key=lambda c: c.open_interest)[-1].symbol";
$assetClass = "EquityOptions";
$dataTypeLink = "/docs/v2/writing-algorithms/securities/asset-classes/equity-options/handling-data#03-Quotes";
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
      <th rowspan='5' valign='top'>2025-01-17</th>
      <th rowspan='5' valign='top'>440.0</th>
      <th rowspan='5' valign='top'>1</th>
      <th rowspan='5' valign='top'>SPY 32OCGBPPW6DTY|SPY R735QTJ8XC9X</th>
      <th>2024-12-18 15:56:00</th>
      <td>0.76</td>
      <td>0.85</td>
      <td>0.71</td>
      <td>0.75</td>
      <td>72.0</td>
      <td>0.74</td>
      <td>0.74</td>
      <td>0.17</td>
      <td>0.60</td>
      <td>72.0</td>
      <td>0.75</td>
      <td>0.795</td>
      <td>0.440</td>
      <td>0.675</td>
    </tr>
    <tr>
      <th>2024-12-18 15:57:00</th>
      <td>0.79</td>
      <td>0.80</td>
      <td>0.76</td>
      <td>0.76</td>
      <td>143.0</td>
      <td>0.77</td>
      <td>0.78</td>
      <td>0.73</td>
      <td>0.74</td>
      <td>60.0</td>
      <td>0.78</td>
      <td>0.790</td>
      <td>0.745</td>
      <td>0.750</td>
    </tr>
    <tr>
      <th>2024-12-18 15:58:00</th>
      <td>0.80</td>
      <td>0.80</td>
      <td>0.78</td>
      <td>0.79</td>
      <td>169.0</td>
      <td>0.78</td>
      <td>0.78</td>
      <td>0.75</td>
      <td>0.77</td>
      <td>60.0</td>
      <td>0.79</td>
      <td>0.790</td>
      <td>0.765</td>
      <td>0.780</td>
    </tr>
    <tr>
      <th>2024-12-18 15:59:00</th>
      <td>0.82</td>
      <td>0.83</td>
      <td>0.79</td>
      <td>0.80</td>
      <td>72.0</td>
      <td>0.80</td>
      <td>0.80</td>
      <td>0.76</td>
      <td>0.78</td>
      <td>72.0</td>
      <td>0.81</td>
      <td>0.815</td>
      <td>0.775</td>
      <td>0.790</td>
    </tr>
    <tr>
      <th>2024-12-18 16:00:00</th>
      <td>0.91</td>
      <td>0.91</td>
      <td>0.82</td>
      <td>0.82</td>
      <td>346.0</td>
      <td>0.87</td>
      <td>0.89</td>
      <td>0.80</td>
      <td>0.80</td>
      <td>72.0</td>
      <td>0.89</td>
      <td>0.900</td>
      <td>0.810</td>
      <td>0.810</td>
    </tr>
  </tbody>
</table>
</div>";

$series = "expiry      strike  type  symbol                              time               
2025-01-17  440.0   1     SPY 32OCGBPPW6DTY|SPY R735QTJ8XC9X  2024-12-18 15:56:00    0.02
                                                              2024-12-18 15:57:00    0.02
                                                              2024-12-18 15:58:00    0.02
                                                              2024-12-18 15:59:00    0.02
                                                              2024-12-18 16:00:00    0.04
dtype: float64";

include(DOCS_RESOURCES."/history/quotebars.php");
?>

<p>Request minute or hour resolution data. Otherwise, the history request won't return any data.</p>
