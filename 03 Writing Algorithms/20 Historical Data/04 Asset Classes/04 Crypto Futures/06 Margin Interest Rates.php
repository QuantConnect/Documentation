<p class='csharp'>
  To get historical <a href='/docs/v2/writing-algorithms/securities/asset-classes/crypto-futures/handling-data#06-Margin-Interest-Rates'>margin interest rate data</a>, call the <code>History&lt;MarginInterestRate&gt;</code> method with a security's <code>Symbol</code>.
</p>

<p class='python'>
  To get historical <a href='/docs/v2/writing-algorithms/securities/asset-classes/crypto-futures/handling-data#06-Margin-Interest-Rates'>margin interest rate data</a>, call the <code>history</code> method with the <code>MarginInterestRate</code> type and a security's <code>Symbol</code>.
  This method returns a DataFrame with a single column for the interest rate.
</p>

<div class="section-example-container">
    <pre class="csharp">public class CryptoFutureMarginInterestRateHistoryAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        SetStartDate(2024, 12, 19);
        // Get the Symbol of a security.
        var symbol = AddCryptoFuture("BTCUSD").Symbol;
        // Get the MarginInterestRate objects of the security over the last 2 trading days. 
        var history = History&lt;MarginInterestRate&gt;(symbol, 2, Resolution.Daily);
        // Iterate through each MarginInterestRate object and get its value.
        foreach (var dataPoint in history)
        {
            var t = dataPoint.EndTime;
            var interestRate = dataPoint.InterestRate;
        }
    }
}</pre>
    <pre class="python">class CryptoFutureMarginInterestRateHistoryAlgorithmHistoryAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 12, 19)
        # Get the Symbol of a security.
        symbol = self.add_crypto_future('BTCUSD').symbol
        # Get the MarginInterestRate data of the security over the last 2 trading days in DataFrame format. 
        history = self.history(MarginInterestRate, symbol, 2, Resolution.DAILY)</pre>
</div>

<div class='dataframe-wrapper'>
<table class="dataframe python">
  <thead>
    <tr style="text-align: right;">
      <th></th>
      <th></th>
      <th>interestrate</th>
    </tr>
    <tr>
      <th>symbol</th>
      <th>time</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th rowspan="6" valign="top">BTCUSD</th>
      <th>2024-12-17 08:00:00</th>
      <td>0.0001</td>
    </tr>
    <tr>
      <th>2024-12-17 16:00:00</th>
      <td>0.0001</td>
    </tr>
    <tr>
      <th>2024-12-18 00:00:00</th>
      <td>0.0001</td>
    </tr>
    <tr>
      <th>2024-12-18 08:00:00</th>
      <td>0.0001</td>
    </tr>
    <tr>
      <th>2024-12-18 16:00:00</th>
      <td>0.0001</td>
    </tr>
    <tr>
      <th>2024-12-19 00:00:00</th>
      <td>0.0001</td>
    </tr>
  </tbody>
</table>
</div>


<div class="python section-example-container">
    <pre class="python"># Calculate the change in interest rates.
delta = history.interestrate.diff()[1:]</pre>
</div>

<div class="python section-example-container">
    <pre>symbol  time               
BTCUSD  2024-12-17 16:00:00    0.0
        2024-12-18 00:00:00    0.0
        2024-12-18 08:00:00    0.0
        2024-12-18 16:00:00    0.0
        2024-12-19 00:00:00    0.0
Name: interestrate, dtype: float64</pre>
</div>

<p class='python'>
  If you intend to use the data in the DataFrame to create <code>MarginInterestRate</code> objects, request that the history request returns the data type you need. 
  Otherwise, LEAN consumes unnecessary computational resources populating the DataFrame.  
  To get a list of <code>MarginInterestRate</code> objects instead of a DataFrame, call the <code>history[MarginInterestRate]</code> method.
</p>

<div class="python section-example-container">
    <pre class="python"># Get the MarginInterestRate data of the security over the last 2 trading days in MarginInterestRate format. 
history = self.history[MarginInterestRate](symbol, 2, Resolution.DAILY)
# Iterate through the MarginInterestRate objects and access their values
for margin_interest_rate in history:
    t = margin_interest_rate.end_time
    interest_rate = margin_interest_rate.interest_rate</pre>
</div>
