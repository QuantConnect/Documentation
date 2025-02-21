<?
$dataTypeLink = "/docs/v2/writing-algorithms/securities/asset-classes/crypto/handling-data#03-Trades";
$dataType = "TradeBar";
?>

<p class='csharp'>
  To get historical <a href='<?=$dataTypeLink?>'>trade data</a>, call the <code>History&lt;<?=$dataType?>&gt;</code> method with an asset's <code>Symbol</code>.
</p>

<p class='python'>
  To get historical <a href='<?=$dataTypeLink?>'>trade data</a>, call the <code>history</code> method with the <code><?=$dataType?></code> type and an asset's <code>Symbol</code>.
  This method returns a DataFrame with columns for the open, high, low, close, and volume.
</p>

<div class="section-example-container">
    <pre class="csharp">public class CryptoTradeBarHistoryAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        SetStartDate(2024, 12, 19);
        // Get the Symbol of an asset.
        var symbol = AddCrypto("BTCUSD").Symbol;
        // Get the 5 trailing daily <?=$dataType?> objects of the asset. 
        var history = History&lt;<?=$dataType?>&gt;(symbol, 5, Resolution.Daily);
        // Iterate through each TradeBar and calculate its dollar volume.
        foreach (var bar in history)
        {
            var t = bar.EndTime;
            var dollarVolume = bar.Close * bar.Volume;
        }
    }
}</pre>
    <pre class="python">class CryptoTradeBarHistoryAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 12, 19)
        # Get the Symbol of an asset.
        symbol = self.add_crypto('BTCUSD').symbol
        # Get the 5 trailing daily <?=$dataType?> objects of the asset in DataFrame format. 
        history = self.history(<?=$dataType?>, symbol, 5, Resolution.DAILY)</pre>
</div>

<table border="1" class="dataframe python">
  <thead>
    <tr style="text-align: right;">
      <th></th>
      <th></th>
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
    </tr>
  </thead>
  <tbody>
    <tr>
      <th rowspan="5" valign="top">BTCUSD</th>
      <th>2024-12-15</th>
      <td>101399.99</td>
      <td>102650.00</td>
      <td>100600.00</td>
      <td>101423.26</td>
      <td>4054.541500</td>
    </tr>
    <tr>
      <th>2024-12-16</th>
      <td>104439.88</td>
      <td>105100.00</td>
      <td>101221.34</td>
      <td>101400.00</td>
      <td>7216.743790</td>
    </tr>
    <tr>
      <th>2024-12-17</th>
      <td>106099.81</td>
      <td>107857.79</td>
      <td>103289.21</td>
      <td>104445.15</td>
      <td>22263.157625</td>
    </tr>
    <tr>
      <th>2024-12-18</th>
      <td>106150.00</td>
      <td>108388.88</td>
      <td>105337.97</td>
      <td>106099.98</td>
      <td>11729.293641</td>
    </tr>
    <tr>
      <th>2024-12-19</th>
      <td>100150.73</td>
      <td>106528.13</td>
      <td>99939.82</td>
      <td>106147.77</td>
      <td>21659.470502</td>
    </tr>
  </tbody>
</table>

<div class="python section-example-container">
    <pre class="python"># Calculate the daily returns.
daily_returns = history.close.pct_change().iloc[1:]</pre>
</div>

<div class="python section-example-container">
    <pre>symbol  time      
BTCUSD  2024-12-16    0.029979
        2024-12-17    0.015894
        2024-12-18    0.000473
        2024-12-19   -0.056517
Name: close, dtype: float64</pre>
</div>


<p class='python'>
  If you intend to use the data in the DataFrame to create <code><?=$dataType?></code> objects, request that the history request returns the data type you need. 
  Otherwise, LEAN consumes unnecessary computational resources populating the DataFrame.  
  To get a list of <code><?=$dataType?></code> objects instead of a DataFrame, call the <code>history[<?=$dataType?>]</code> method.
</p>

<div class="python section-example-container">
    <pre class="python"># Get the 5 trailing daily <?=$dataType?> objects of an asset in <?=$dataType?> format. 
history = self.history[<?=$dataType?>](symbol, 5, Resolution.DAILY)
# Iterate through the TradeBar objects and access their volumes.
for trade_bar in history:
    volume = trade_bar.volume</pre>
</div>
