<p class='csharp'>
  To get historical <a href='/docs/v2/writing-algorithms/securities/asset-classes/future-options/handling-data#02-Trades'>trade data</a>, call the <code>History&lt;TradeBar&gt;</code> method with a security's <code>Symbol</code>.
</p>

<p class='python'>
  To get historical <a href='/docs/v2/writing-algorithms/securities/asset-classes/future-options/handling-data#02-Trades'>trade data</a>, call the <code>history</code> method with the <code>TradeBar</code> type and a security's <code>Symbol</code>.
  This method returns a DataFrame with columns for the open, high, low, close, and volume.
</p>

<div class="section-example-container">
    <pre class="csharp">public class FutureOptionsTradeBarHistoryAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        SetStartDate(2024, 12, 19);
        // Add a FOP universe.
        var future = AddFuture(Futures.Indices.SP500EMini);
        future.SetFilter(universe => universe.FrontMonth());
        AddFutureOption(future.Symbol, universe => universe.FrontMonth().Strikes(-1, 0).CallsOnly());
    }

    // Get trailing data whenever a new FOP contract enters the universe.
    public override void OnSecuritiesChanged(SecurityChanges changes)
    {
        foreach (var security in changes.AddedSecurities)
        {
            if (security.Type == SecurityType.FutureOption)
            {
                // Get the 3 trailing daily TradeBar objects of the security. 
                var history = History&lt;TradeBar&gt;(security.Symbol, 3, Resolution.Daily);
                // Iterate through each TradeBar and calculate its dollar volume.
                foreach (var bar in history)
                {
                    var t = bar.EndTime;
                    var dollarVolume = bar.Close * bar.Volume;
                }
            }
        }
    }
}</pre>
    <pre class="python">class FutureOptionsTradeBarHistoryAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 12, 19)
        # Add a FOP universe.
        future = self.add_future(Futures.Indices.SP_500_E_MINI)
        future.set_filter(lambda universe: universe.front_month())
        self.add_future_option(future.symbol, lambda universe: universe.front_month().strikes(-1, 0).calls_only())

    # Get trailing data whenever a new FOP contract enters the universe.
    def on_securities_changed(self, changes):
        for security in changes.added_securities:
            if security.type == SecurityType.FUTURE_OPTION:
                # Get the 3 trailing daily TradeBar objects of the security in DataFrame format. 
                history = self.history(TradeBar, security.symbol, 3, Resolution.DAILY)</pre>
</div>

<div class='dataframe-wrapper'>
<table class="dataframe python">
  <thead>
    <tr style="text-align: right;">
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th>close</th>
      <th>high</th>
      <th>low</th>
      <th>open</th>
      <th>volume</th>
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
    </tr>
  </thead>
  <tbody>
    <tr>
      <th rowspan="3" valign="top">2024-12-20</th>
      <th rowspan="3" valign="top">5870.0</th>
      <th rowspan="3" valign="top">1</th>
      <th rowspan="3" valign="top">ES 32NKVT5YYX1US|ES YOGVNNAOI1OH</th>
      <th>2024-12-16 17:00:00</th>
      <td>2.15</td>
      <td>2.15</td>
      <td>1.45</td>
      <td>1.80</td>
      <td>227.0</td>
    </tr>
    <tr>
      <th>2024-12-17 17:00:00</th>
      <td>2.35</td>
      <td>3.05</td>
      <td>2.30</td>
      <td>2.30</td>
      <td>14.0</td>
    </tr>
    <tr>
      <th>2024-12-18 17:00:00</th>
      <td>38.75</td>
      <td>43.75</td>
      <td>1.75</td>
      <td>1.85</td>
      <td>399.0</td>
    </tr>
  </tbody>
</table>
</div>


<div class="python section-example-container">
    <pre class="python"># Calculate the daily returns.
daily_returns = history.close.pct_change().iloc[1:]</pre>
</div>

<div class="python section-example-container">
    <pre>expiry      strike  type  symbol                            time               
2024-12-20  5870.0  1     ES 32NKVT5YYX1US|ES YOGVNNAOI1OH  2024-12-17 17:00:00     0.093023
                                                            2024-12-18 17:00:00    15.489362
Name: close, dtype: float64</pre>
</div>

<p class='python'>
  If you intend to use the data in the DataFrame to create <code>TradeBar</code> objects, request that the history request returns the data type you need. 
  Otherwise, LEAN consumes unnecessary computational resources populating the DataFrame.  
  To get a list of <code>TradeBar</code> objects instead of a DataFrame, call the <code>history[TradeBar]</code> method.
</p>

<div class="python section-example-container">
    <pre class="python"># Get the 3 trailing daily TradeBar objects of the security in TradeBar format. 
history = self.history[TradeBar](symbol, 3, Resolution.DAILY)
# Iterate through the TradeBar objects and access their volumes.
for trade_bar in history:
    t = trade_bar.end_time
    volume = trade_bar.volume</pre>
</div>

<p>Request minute, hour, or daily resolution data. Otherwise, the history request won't return any data.</p>
