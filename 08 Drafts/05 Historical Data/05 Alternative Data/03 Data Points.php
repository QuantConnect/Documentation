<p>
  To request a list of data points containing historical alternative data, call one of the <code class='csharp'>History&lt;<span class='placeholder-text'>Type</span>&gt;</code><code class='python'>history[<span class='placeholder-text'>Type</span>]</code> methods.
  
</p>


<div class='python'>
  <p>
    Some alternative datasets provide multiple entries per <a href='/docs/v2/writing-algorithms/key-concepts/time-modeling/timeslices'>time step</a>. 
    For example, the <a href='/datasets/regalytics-us-regulatory-alerts'>US Regulatory Alerts</a> can provide multiple alerts per day.
    In this case, to organize the data into a DataFrame, set the <code>flatten</code> argument to <code>True</code>.
  </p>

  <div class="section-example-container">
    <pre class="python"># Get the all the Regalytics articles that were published over the last day, organized in a DataFrame.
dataset_symbol = self.add_data(RegalyticsRegulatoryArticles, "REG").symbol
history = self.history(dataset_symbol, 1, Resolution.DAILY, flatten=True)</pre>
  </div>

  <img src='https://cdn.quantconnect.com/i/tu/regalytics-dataframe-history.png' class='docs-image' alt='DataFrame of ExtractAlphaTrueBeats data for AAPL on 01/02/2024.'>
</div>
