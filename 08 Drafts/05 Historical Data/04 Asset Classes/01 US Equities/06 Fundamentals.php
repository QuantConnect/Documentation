<p class='csharp'>
  To get historical <a href='/docs/v2/writing-algorithms/securities/asset-classes/us-equity/corporate-fundamentals'>fundamental data</a>, call the <code>History&lt;Fundamental&gt;</code> method with an asset's <code>Symbol</code>.
</p>

<p class='python'>
  To get historical <a href='/docs/v2/writing-algorithms/securities/asset-classes/us-equity/corporate-fundamentals'>fundamental data</a>, call the <code>history</code> method with the <code>Fundamental</code> type and an asset's <code>Symbol</code>.
  This method returns a DataFrame with columns for the <a href='/docs/v2/writing-algorithms/datasets/morningstar/us-fundamental-data#06-Data-Point-Attributes'>data point attributes</a>.
</p>

<div class="section-example-container">
    <pre class="csharp">// Get the Symbol of an asset.
var symbol = AddEquity("AAPL").Symbol;
// Get the 3 trailing daily Fundamental objects of the asset. 
var history = History&lt;Fundamental&gt;(symbol, 3, Resolution.Daily);
// Iterate through each Fundamental object.
foreach (var fundamental in history)
{
    var t = fundamental.EndTime;
    var currentRatio = fundamental.OperationRatios.CurrentRatio.Value;
}
</pre>
    <pre class="python"># Get the Symbol of an asset.
symbol = self.add_equity('AAPL').symbol
# Get the 3 trailing daily Fundamental objects of the asset in DataFrame format. 
history = self.history(Fundamental, symbol, 3, Resolution.DAILY)</pre>
</div>

<img class='python docs-image' src='https://cdn.quantconnect.com/i/tu/fundamental-data-history-dataframe.png' alt='DataFrame of the fundamentals of an asset.'>

<div class="python section-example-container">
    <pre class="python"># Get the Current Ratio of each row.
current_ratios = history.apply(lambda row: row.operationratios.current_ratio.value, axis=1)</pre>
</div>

<div class="python section-example-container">
    <pre>symbol  time      
AAPL    2024-12-24    0.867313
        2024-12-25    0.867313
        2024-12-27    0.867313
dtype: float64</pre>
</div>

<p class='python'>
  If you request a DataFrame, LEAN unpacks the data from <code>Fundamental</code> objects to populate the DataFrame. 
  To avoid consuming computational resources populating the DataFrame, you can instead request <code>Fundamental</code> objects. 
  To get a list of <code>Fundamental</code> objects, call the <code>history[Fundamental]</code> method.
</p>

<div class="python section-example-container">
    <pre class="python"># Get the 3 trailing daily Fundamental objects of an asset in Fundamental format. 
history = self.history[Fundamental](symbol, 3, Resolution.DAILY)
# Iterate through each Fundamental object and access its properites.
for fundamental in history:
    symbol = fundamental.symbol
    pe_ratio = fundamental.valuation_ratios.pe_ratio</pre>
</div>
