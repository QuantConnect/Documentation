<p class='csharp'>To get a DataFrame from a history request, use Python.</p>

<div class='python'>
  <p>
    The most popular return type is a DataFrame. 
    To request a DataFrame of historical data, call the <code>history</code> method with at least one <code>Symbol</code> object.
  </p>
  
  <div class="section-example-container">
      <pre class="python">class DataFrameHistoryResponseAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2014, 12, 20)
        # Get the Symbol objects of some assets.
        symbols = [self.add_equity(ticker).symbol for ticker in ['SPY', 'QQQ']]
        # Get 3 days of daily data for the assets.
        history = self.history(symbols, 3, Resolution.DAILY)</pre>
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
        <th rowspan="3" valign="top">SPY</th>
        <th>2024-12-17 16:00:00</th>
        <td>604.29</td>
        <td>605.16</td>
        <td>602.89</td>
        <td>604.22</td>
        <td>38527534.0</td>
      </tr>
      <tr>
        <th>2024-12-18 16:00:00</th>
        <td>586.28</td>
        <td>606.40</td>
        <td>585.89</td>
        <td>604.00</td>
        <td>80642184.0</td>
      </tr>
      <tr>
        <th>2024-12-19 16:00:00</th>
        <td>586.10</td>
        <td>593.00</td>
        <td>585.85</td>
        <td>591.43</td>
        <td>70752398.0</td>
      </tr>
      <tr>
        <th rowspan="3" valign="top">QQQ</th>
        <th>2024-12-17 16:00:00</th>
        <td>535.80</td>
        <td>537.49</td>
        <td>534.13</td>
        <td>536.41</td>
        <td>25048673.0</td>
      </tr>
      <tr>
        <th>2024-12-18 16:00:00</th>
        <td>516.47</td>
        <td>536.87</td>
        <td>515.01</td>
        <td>535.11</td>
        <td>47016560.0</td>
      </tr>
      <tr>
        <th>2024-12-19 16:00:00</th>
        <td>514.17</td>
        <td>521.75</td>
        <td>513.83</td>
        <td>521.11</td>
        <td>42192908.0</td>
      </tr>
    </tbody>
  </table>


  <p>
    The structure of the DataFrame depends on the <a href='/datasets'>dataset</a>.
    In most cases, there is a mulit-index that contains the <code>Symbol</code> and a timestamp.
    The timestamps in the DataFrame are based on the <a href='/docs/v2/writing-algorithms/key-concepts/time-modeling/time-zones#05-Data-Time-Zone'>data time zone</a>.
    Each row of the DataFrame represents the prices at a point in time. 
    Each column of the DataFrame is a property of that data (for example, open, high, low, and close (OHLC)).
  </p>
  
  <p>
    If you intend to use the data in the DataFrame to create <code>TradeBar</code> or <code>QuoteBar</code> objects, request that the history request returns the data type you need. 
    Otherwise, LEAN consumes unnecessary computational resources populating the DataFrame.
  </p>
</div>
