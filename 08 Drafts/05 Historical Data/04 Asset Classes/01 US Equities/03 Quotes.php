<?
$assetClass = "US Equities";
$resolutionLink = "/docs/v2/writing-algorithms/securities/asset-classes/us-equity/requesting-data#03-Resolutions";
$dataTypeLink = "/docs/v2/writing-algorithms/securities/asset-classes/us-equity/handling-data#04-Quotes";
$dataType = "QuoteBar";
?>

<p class='csharp'>
  To get historical <a href='<?=$dataTypeLink?>'>quote data</a>, call the <code>History&lt;<?=$dataType?>&gt;</code> method with an asset's <code>Symbol</code>.
</p>

<p class='python'>
  To get historical <a href='<?=$dataTypeLink?>'>quote data</a>, call the <code>history</code> method with the <code><?=$dataType?></code> type and an asset's <code>Symbol</code>.
  This method returns a DataFrame with columns for the open, high, low, close, and size of the bid and ask quotes.
  The columns that don't start with "bid" or "ask" are the mean of the quote prices on both sides of the market.
</p>

<div class="section-example-container">
    <pre class="csharp">public class USEquityQuoteBarAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        SetStartDate(2024, 12, 19);
        // Get the Symbol of an asset.
        var symbol = AddEquity("SPY").Symbol;
        // Get the 5 trailing minute <?=$dataType?> objects of the asset. 
        var history = History&lt;<?=$dataType?>&gt;(symbol, 5, Resolution.Minute);
        // Iterate through the QuoteBar objects and calculate the spread.
        foreach (var bar in history)
        {
            var t = bar.EndTime;
            var spread = bar.Ask.Close - bar.Bid.Close;
        }
    }
}</pre>
    <pre class="python">class USEquityQuoteBarAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 12, 19)
        # Get the Symbol of an asset.
        symbol = self.add_equity('SPY').symbol
        # Get the 5 trailing minute <?=$dataType?> objects of the asset in DataFrame format. 
        history = self.history(<?=$dataType?>, symbol, 5, Resolution.MINUTE)</pre>
</div>

<table border="1" class="dataframe python">
  <thead>
    <tr style="text-align: right;">
      <th></th>
      <th></th>
      <th>askclose</th>
      <th>askhigh</th>
      <th>asklow</th>
      <th>askopen</th>
      <th>asksize</th>
      <th>...</th>
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
    </tr>
  </thead>
  <tbody>
    <tr>
      <th rowspan="5" valign="top">SPY</th>
      <th>2024-12-18 15:56:00</th>
      <td>588.65</td>
      <td>590.44</td>
      <td>588.60</td>
      <td>590.42</td>
      <td>10700.0</td>
      <td>...</td>
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
      <td>...</td>
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
      <td>...</td>
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
      <td>...</td>
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
      <td>...</td>
      <td>74700.0</td>
      <td>586.315</td>
      <td>587.940</td>
      <td>585.885</td>
      <td>587.925</td>
    </tr>
  </tbody>
</table>


<div class="python section-example-container">
    <pre class="python"># Calculate the spread at each minute.
spread = history.askclose - history.bidclose</pre>
</div>

<div class="python section-example-container">
    <pre>symbol  time               
SPY     2024-12-18 15:56:00    0.039866
        2024-12-18 15:57:00    0.029899
        2024-12-18 15:58:00    0.019933
        2024-12-18 15:59:00    0.009966
        2024-12-18 16:00:00    0.029899
dtype: float64</pre>
</div>

<p class='python'>
  If you intend to use the data in the DataFrame to create <code><?=$dataType?></code> objects, request that the history request returns the data type you need. 
  Otherwise, LEAN consumes unnecessary computational resources populating the DataFrame.  
  To get a list of <code><?=$dataType?></code> objects instead of a DataFrame, call the <code>history[<?=$dataType?>]</code> method.
</p>

<div class="python section-example-container">
    <pre class="python"># Get the 5 trailing minute <?=$dataType?> objects of an asset in <?=$dataType?> format. 
history = self.history[<?=$dataType?>](symbol, 5, Resolution.MINUTE)
# Iterate through each QuoteBar and calculate the dollar volume on the bid.
for quote_bar in history:
    bid_dollar_volume = quote_bar.last_bid_size * quote_bar.bid.close</pre>
</div>

<p>The resolution of data you request must support <code>QuoteBar</code> data. Otherwise, the history request won't return any data. To check which resolutions for <?=$assetClass?> support <code>QuoteBar</code> data, see <a href='<?=$resolutionLink?>'>Resolutions</a>.</p>
