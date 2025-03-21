<p class='csharp'>
  To get historical <a href='/docs/v2/writing-algorithms/securities/asset-classes/future-options/handling-data#03-Quotes'>quote data</a>, call the <code>History&lt;QuoteBar&gt;</code> method with a security's <code>Symbol</code>.
</p>

<p class='python'>
  To get historical <a href='/docs/v2/writing-algorithms/securities/asset-classes/future-options/handling-data#03-Quotes'>quote data</a>, call the <code>history</code> method with the <code>QuoteBar</code> type and a security's <code>Symbol</code>.
  This method returns a DataFrame with columns for the open, high, low, close, and size of the bid and ask quotes. 
  The columns that don't start with "bid" or "ask" are the mean of the quote prices on both sides of the market.
</p>

<div class="section-example-container">
    <pre class="csharp">public class FutureOptionsQuoteBarHistoryAlgorithm : QCAlgorithm
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
                // Get the 3 trailing daily QuoteBar objects of the security. 
                var history = History&lt;QuoteBar&gt;(security.Symbol, 3, Resolution.Daily);
                // Iterate through the QuoteBar objects and calculate the spread.
                foreach (var bar in history)
                {
                    var t = bar.EndTime;
                    var spread = bar.Ask.Close - bar.Bid.Close;
                }
            }
        }
    }
}</pre>
    <pre class="python">class FutureOptionsQuoteBarHistoryAlgorithm(QCAlgorithm):

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
                # Get the 3 trailing daily QuoteBar objects of the security in DataFrame format. 
                history = self.history(QuoteBar, security.symbol, 3, Resolution.DAILY)</pre>
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
      <th rowspan="3" valign="top">2024-12-20</th>
      <th rowspan="3" valign="top">5870.0</th>
      <th rowspan="3" valign="top">1</th>
      <th rowspan="3" valign="top">ES 32NKVT5YYX1US|ES YOGVNNAOI1OH</th>
      <th>2024-12-16 17:00:00</th>
      <td>2.5</td>
      <td>2.55</td>
      <td>1.45</td>
      <td>1.85</td>
      <td>2.0</td>
      <td>2.35</td>
      <td>2.40</td>
      <td>1.35</td>
      <td>1.75</td>
      <td>46.0</td>
      <td>2.425</td>
      <td>2.475</td>
      <td>1.4</td>
      <td>1.800</td>
    </tr>
    <tr>
      <th>2024-12-17 17:00:00</th>
      <td>2.7</td>
      <td>3.15</td>
      <td>2.15</td>
      <td>3.05</td>
      <td>179.0</td>
      <td>2.45</td>
      <td>3.05</td>
      <td>2.05</td>
      <td>2.90</td>
      <td>46.0</td>
      <td>2.575</td>
      <td>3.100</td>
      <td>2.1</td>
      <td>2.975</td>
    </tr>
    <tr>
      <th>2024-12-18 17:00:00</th>
      <td>40.0</td>
      <td>215.50</td>
      <td>1.55</td>
      <td>2.30</td>
      <td>3.0</td>
      <td>36.50</td>
      <td>60.50</td>
      <td>0.05</td>
      <td>2.15</td>
      <td>2.0</td>
      <td>38.250</td>
      <td>138.000</td>
      <td>0.8</td>
      <td>2.225</td>
    </tr>
  </tbody>
</table>
</div>


<div class="python section-example-container">
    <pre class="python"># Calculate the spread.
spread = history.askclose - history.bidclose</pre>
</div>

<div class="python section-example-container">
    <pre>expiry      strike  type  symbol                            time               
2024-12-20  5870.0  1     ES 32NKVT5YYX1US|ES YOGVNNAOI1OH  2024-12-16 17:00:00    0.15
                                                            2024-12-17 17:00:00    0.25
                                                            2024-12-18 17:00:00    3.50
dtype: float64</pre>
</div>

<p class='python'>
  If you intend to use the data in the DataFrame to create <code>QuoteBar</code> objects, request that the history request returns the data type you need. 
  Otherwise, LEAN consumes unnecessary computational resources populating the DataFrame.  
  To get a list of <code>QuoteBar</code> objects instead of a DataFrame, call the <code>history[QuoteBar]</code> method.
</p>

<div class="python section-example-container">
    <pre class="python"># Get the 3 trailing daily QuoteBar objects of the security in QuoteBar format. 
history = self.history[QuoteBar](symbol, 3, Resolution.DAILY)
# Iterate through each QuoteBar and calculate the dollar volume on the bid.
for quote_bar in history:
    t = quote_bar.end_time
    bid_dollar_volume = quote_bar.last_bid_size * quote_bar.bid.close</pre>
</div>

<p>Request minute, hour, or daily resolution data. Otherwise, the history request won't return any data.</p>
