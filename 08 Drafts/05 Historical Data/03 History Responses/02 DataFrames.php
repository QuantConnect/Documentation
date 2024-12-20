<p class='csharp'>To get a DataFrame from a history request, use Python.</p>

<div class='python'>
  <p>
    The most popular return type is a DataFrame. 
    To request a DataFrame of historical data, call the <code>history</code> method with atleast one <code>Symbol</code> object and omit the <code>[Type]</code> configuration.
  </p>
  
  <div class="section-example-container">
      <pre class="python">history = self.history(symbols, 3, Resolution.DAILY)</pre>
  </div>

  <img class='docs-image' src='https://cdn.quantconnect.com/i/tu/history-response-dataframe.png' alt='DataFrame of daily OHLCV values for two assets.'>

  <p>
    The structure of the DataFrame depends on the dataset.
    In most cases, there is a mulit-index that contains the <code>Symbol</code> and a timestamp.
    The timestamps in the DataFrame are based on the <a href='/docs/v2/writing-algorithms/key-concepts/time-modeling/time-zones#05-Data-Time-Zone'>data time zone</a>.
  </p>
  
  <p>
    If you request a DataFrame, LEAN unpacks the data from <code>Slice</code> objects to populate the DataFrame. 
    If you intend to use the data in the DataFrame to create <code>TradeBar</code> or <code>QuoteBar</code> objects, request that the history request returns the data type you need. 
    Otherwise, LEAN will waste computational resources populating the DataFrame.
  </p>
</div>
