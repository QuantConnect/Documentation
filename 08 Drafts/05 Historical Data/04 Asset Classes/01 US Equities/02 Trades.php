<?
$imgLink = "https://cdn.quantconnect.com/i/tu/history-tradebar-dataframe-us-equities.png";
$dataTypeLink = "/docs/v2/writing-algorithms/securities/asset-classes/us-equity/handling-data#03-Trades";
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
    <pre class="csharp">public class USEquityTradeBarHistoryAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        SetStartDate(2024, 12, 19);
        // Get the Symbol of an asset.
        var symbol = AddEquity("SPY").Symbol;
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
    <pre class="python">class USEquityTradeBarHistoryAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 12, 19)
        # Get the Symbol of an asset.
        symbol = self.add_equity('SPY').symbol
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
      <th rowspan="5" valign="top">SPY</th>
      <th>2024-12-12 16:00:00</th>
      <td>604.33</td>
      <td>607.160</td>
      <td>604.33</td>
      <td>606.64</td>
      <td>24962360.0</td>
    </tr>
    <tr>
      <th>2024-12-13 16:00:00</th>
      <td>604.21</td>
      <td>607.100</td>
      <td>602.82</td>
      <td>606.38</td>
      <td>29250856.0</td>
    </tr>
    <tr>
      <th>2024-12-16 16:00:00</th>
      <td>606.79</td>
      <td>607.775</td>
      <td>605.22</td>
      <td>606.00</td>
      <td>33686372.0</td>
    </tr>
    <tr>
      <th>2024-12-17 16:00:00</th>
      <td>604.29</td>
      <td>605.160</td>
      <td>602.89</td>
      <td>604.22</td>
      <td>38527534.0</td>
    </tr>
    <tr>
      <th>2024-12-18 16:00:00</th>
      <td>586.28</td>
      <td>606.400</td>
      <td>585.89</td>
      <td>604.00</td>
      <td>80642184.0</td>
    </tr>
  </tbody>
</table>

<div class="python section-example-container">
    <pre class="python"># Calculate the daily returns.
daily_returns = history.close.pct_change().iloc[1:]</pre>
</div>

<div class="python section-example-container">
    <pre>symbol  time               
SPY     2024-12-13 16:00:00   -0.000199
        2024-12-16 16:00:00    0.004270
        2024-12-17 16:00:00   -0.004120
        2024-12-18 16:00:00   -0.029804
Name: close, dtype: float64</pre>
</div>


<p class='python'>
  If you request a DataFrame, LEAN unpacks the data from <code>Slice</code> objects to populate the DataFrame. 
  If you intend to use the data in the DataFrame to create <code><?=$dataType?></code> objects, request that the history request returns the data type you need. 
  Otherwise, LEAN will consume computational resources populating the DataFrame.  
  To get a list of <code><?=$dataType?></code> objects instead of a DataFrame, call the <code>history[<?=$dataType?>]</code> method.
</p>

<div class="python section-example-container">
    <pre class="python"># Get the 5 trailing daily <?=$dataType?> objects of an asset in <?=$dataType?> format. 
history = self.history[<?=$dataType?>](symbol, 5, Resolution.DAILY)
# Iterate through the TradeBar objects and access their volumes.
for trade_bar in history:
    volume = trade_bar.volume</pre>
</div>
