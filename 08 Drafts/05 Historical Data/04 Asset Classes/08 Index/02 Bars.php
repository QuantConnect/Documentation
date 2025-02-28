<p class='csharp'>
  To get historical <a href='/docs/v2/writing-algorithms/securities/asset-classes/index/handling-data#02-Bars'>price data</a>, call the <code>History</code> method with an Index <code>Symbol</code>.
</p>

<p class='python'>
  To get historical <a href='/docs/v2/writing-algorithms/securities/asset-classes/index/handling-data#02-Bars'>price data</a>, call the <code>history</code> method with an Index <code>Symbol</code>.
  This method returns a DataFrame with columns for the open, high, low, and close.
</p>

<div class="section-example-container">
    <pre class="csharp">public class IndexPriceHistoryAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        SetStartDate(2024, 12, 19);
        // Get the Symbol of an Index.
        var symbol = AddIndex("SPX").Symbol;
        // Get the 5 trailing daily bars of the Index. 
        var history = History(symbol, 5, Resolution.Daily);
        // Iterate through each bar and get its price.
        foreach (var bar in history)
        {
            var t = bar.EndTime;
            var price = bar.Close;
        }
    }
}</pre>
    <pre class="python">class IndexPriceHistoryAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 12, 19)
        # Get the Symbol of an Index.
        symbol = self.add_index('SPX').symbol
        # Get the 5 trailing daily bars of the Index in DataFrame format. 
        history = self.history(symbol, 5, Resolution.DAILY)</pre>
</div>

<div class='dataframe-wrapper'>
<table class='dataframe python'>
  <thead>
    <tr style='text-align: right;'>
      <th></th>
      <th></th>
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
    </tr>
  </thead>
  <tbody>
    <tr>
      <th rowspan='5' valign='top'>SPX</th>
      <th>2024-12-12 15:15:00</th>
      <td>6051.70</td>
      <td>6079.68</td>
      <td>6051.70</td>
      <td>6074.05</td>
    </tr>
    <tr>
      <th>2024-12-13 15:15:00</th>
      <td>6050.83</td>
      <td>6078.58</td>
      <td>6035.77</td>
      <td>6068.48</td>
    </tr>
    <tr>
      <th>2024-12-16 15:15:00</th>
      <td>6074.48</td>
      <td>6085.19</td>
      <td>6059.14</td>
      <td>6064.04</td>
    </tr>
    <tr>
      <th>2024-12-17 15:15:00</th>
      <td>6050.31</td>
      <td>6057.68</td>
      <td>6035.19</td>
      <td>6046.33</td>
    </tr>
    <tr>
      <th>2024-12-18 15:15:00</th>
      <td>5869.22</td>
      <td>6070.67</td>
      <td>5867.79</td>
      <td>6048.69</td>
    </tr>
  </tbody>
</table>
</div>

<div class="python section-example-container">
    <pre class="python"># Calculate the daily growth.
daily_growth = history.close.pct_change().iloc[1:]</pre>
</div>

<div class="python section-example-container">
    <pre>symbol  time               
SPX     2024-12-13 15:15:00   -0.000144
        2024-12-16 15:15:00    0.003909
        2024-12-17 15:15:00   -0.003979
        2024-12-18 15:15:00   -0.029931
Name: close, dtype: float64</pre>
</div>

<p class='python'>
  If you intend to use the data in the DataFrame to create <code>TradeBar</code> objects, request that the history request returns the data type you need. 
  Otherwise, LEAN consumes unnecessary computational resources populating the DataFrame.  
  To get a list of <code>TradeBar</code> objects instead of a DataFrame, call the <code>history[TradeBar]</code> method.
</p>

<div class="python section-example-container">
    <pre class="python"># Get the 5 trailing daily bars of the Index in TradeBar format. 
history = self.history[TradeBar](symbol, 5, Resolution.DAILY)
# Iterate through the TradeBar objects and access their prices.
for trade_bar in history:
    price = trade_bar.price</pre>
</div>

