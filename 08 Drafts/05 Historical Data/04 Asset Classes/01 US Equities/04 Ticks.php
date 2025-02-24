<?
$dataTypeLink = "/docs/v2/writing-algorithms/securities/asset-classes/us-equity/handling-data#05-Ticks";
$dataType = "Tick";
?>

<p class='csharp'>
  To get historical <a href='<?=$dataTypeLink?>'>tick data</a>, call the <code>History&lt;Tick&gt;</code> method with an asset's <code>Symbol</code> and <code>Resolution.Tick</code>.
</p>

<p class='python'>
  To get historical <a href='<?=$dataTypeLink?>'>tick data</a>, call the <code>history</code> method with an asset's <code>Symbol</code> and <code>Resolution.TICK</code>.
  This method returns a DataFrame that contains data on bids, asks, and trades.
</p>

<div class="section-example-container">
    <pre class="csharp">public class USEquityTickHistoryAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        SetStartDate(2024, 12, 19);
        // Get the Symbol of an asset.
        var symbol = AddEquity("SPY").Symbol;
        // Get the trailing 2 days of ticks for the asset.
        var history = History&lt;Tick&gt;(symbol, TimeSpan.FromDays(2), Resolution.Tick);
        // Select the ticks that represent trades, excluding the quote ticks.
        var trades = history.Where(tick => tick.TickType == TickType.Trade);
    }
}</pre>
    <pre class="python">class USEquityTickHistoryAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 12, 19)
        # Get the Symbol of an asset.
        symbol = self.add_equity('SPY').symbol
        # Get the trailing 2 days of ticks for the asset in DataFrame format.
        history = self.history(symbol, timedelta(2), Resolution.TICK)</pre>
</div>

<div class="dataframe-wrapper">
  <table class="dataframe python">
    <thead>
      <tr style="text-align: right;">
        <th></th>
        <th></th>
        <th>askprice</th>
        <th>asksize</th>
        <th>bidprice</th>
        <th>bidsize</th>
        <th>exchange</th>
        <th>lastprice</th>
        <th>quantity</th>
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
      </tr>
    </thead>
    <tbody>
      <tr>
        <th rowspan="5" valign="top">SPY</th>
        <th>2024-12-17 09:30:00.000214</th>
        <td>0.00</td>
        <td>0.0</td>
        <td>604.17</td>
        <td>1000.0</td>
        <td>NASDAQ</td>
        <td>604.17</td>
        <td>0.0</td>
      </tr>
      <tr>
        <th>2024-12-17 09:30:00.000214</th>
        <td>604.19</td>
        <td>1900.0</td>
        <td>0.00</td>
        <td>0.0</td>
        <td>ARCA</td>
        <td>604.19</td>
        <td>0.0</td>
      </tr>
      <tr>
        <th>2024-12-17 09:30:00.001110</th>
        <td>NaN</td>
        <td>NaN</td>
        <td>NaN</td>
        <td>NaN</td>
        <td>NYSE</td>
        <td>604.19</td>
        <td>100.0</td>
      </tr>
      <tr>
        <th>2024-12-17 09:30:00.001480</th>
        <td>NaN</td>
        <td>NaN</td>
        <td>NaN</td>
        <td>NaN</td>
        <td>ARCA</td>
        <td>604.19</td>
        <td>69.0</td>
      </tr>
      <tr>
        <th>2024-12-17 09:30:00.001482</th>
        <td>0.00</td>
        <td>0.0</td>
        <td>604.17</td>
        <td>1000.0</td>
        <td>NASDAQ</td>
        <td>604.17</td>
        <td>0.0</td>
      </tr>
    </tbody>
  </table>
</div>


<div class="python section-example-container">
    <pre class="python"># Select the rows in the DataFrame that represent trades. Drop the bid/ask columns since they are NaN.
trade_ticks = history[history.quantity > 0].dropna(axis=1)</pre>
</div>

<div class="dataframe-wrapper">
  <table class="dataframe python">
    <thead>
      <tr style="text-align: right;">
        <th></th>
        <th></th>
        <th>exchange</th>
        <th>lastprice</th>
        <th>quantity</th>
      </tr>
      <tr>
        <th>symbol</th>
        <th>time</th>
        <th></th>
        <th></th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th rowspan="5" valign="top">SPY</th>
        <th>2024-12-17 09:30:00.001110</th>
        <td>NYSE</td>
        <td>604.19</td>
        <td>100.0</td>
      </tr>
      <tr>
        <th>2024-12-17 09:30:00.001480</th>
        <td>ARCA</td>
        <td>604.19</td>
        <td>69.0</td>
      </tr>
      <tr>
        <th>2024-12-17 09:30:00.001483</th>
        <td>ARCA</td>
        <td>604.19</td>
        <td>100.0</td>
      </tr>
      <tr>
        <th>2024-12-17 09:30:00.001483</th>
        <td>ARCA</td>
        <td>604.19</td>
        <td>231.0</td>
      </tr>
      <tr>
        <th>2024-12-17 09:30:00.001564</th>
        <td>NASDAQ</td>
        <td>604.19</td>
        <td>100.0</td>
      </tr>
    </tbody>
  </table>
</div>


<p class='python'>
  If you intend to use the data in the DataFrame to create <code><?=$dataType?></code> objects, request that the history request returns the data type you need. 
  Otherwise, LEAN consumes unnecessary computational resources populating the DataFrame.  
  To get a list of <code><?=$dataType?></code> objects instead of a DataFrame, call the <code>history[<?=$dataType?>]</code> method.
</p>


<div class="python section-example-container">
    <pre class="python"># Get the trailing 2 days of ticks for an asset in Tick format. 
history = self.history[Tick](symbol, timedelta(2), Resolution.TICK)
# Iterate through each quote tick and calculate the quote size.
for tick in history:
    if tick.tick_type == TickType.Quote:
        size = max(tick.bid_size, tick.ask_size)</pre>
</div>

<p>Ticks are a sparse dataset, so request ticks over a trailing period of time or between start and end times.</p>
