<?
$dataTypeLink = "/docs/v2/writing-algorithms/securities/asset-classes/us-equity/corporate-actions#03-Dividends";
$dataType = "Dividend";
?>

<p class='csharp'>
  To get historical <a href='<?=$dataTypeLink?>'>dividend data</a>, call the <code>History&lt;Dividend&gt;</code> method with an asset's <code>Symbol</code>.
  Dividends are a sparse dataset, so use a time period history request since most days have no data.
</p>

<p class='python'>
  To get historical <a href='<?=$dataTypeLink?>'>dividend data</a>, call the <code>history</code> method with the <code>Dividend</code> type and an asset's <code>Symbol</code>.
  This method returns a DataFrame with columns for the dividend payment and reference price.
  Dividends are a sparse dataset, so use a time period history request since most days have no data.
</p>

<div class="section-example-container">
    <pre class="csharp">public class USEquityDividendHistoryAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        SetStartDate(2024, 12, 1);
        // Get the Symbol of an asset.
        var symbol = AddEquity("AAPL").Symbol;
        // Get the dividends that the stock paid over the last 2 years. 
        var history = History&lt;Dividend&gt;(symbol, TimeSpan.FromDays(2*365));
        // Calculate the mean dividend payment.
        var meanDividend = history.Average(split => split.Distribution);
    }
}</pre>
    <pre class="python">class USEquityDividendHistoryAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 12, 1)
        # Get the Symbol of an asset.
        symbol = self.add_equity('AAPL').symbol
        # Get the dividends that the stock paid over the last 2 years in DataFrame format. 
        history = self.history(Dividend, symbol, timedelta(2*365))</pre>
</div>

<div class="dataframe-wrapper">
  <table class="dataframe python">
    <thead>
      <tr style="text-align: right;">
        <th></th>
        <th></th>
        <th>distribution</th>
        <th>referenceprice</th>
        <th>value</th>
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
        <th rowspan="5" valign="top">AAPL</th>
        <th>2022-12-23</th>
        <td>0.17</td>
        <td>132.23</td>
        <td>0.17</td>
      </tr>
      <tr>
        <th>2023-02-10</th>
        <td>0.23</td>
        <td>150.87</td>
        <td>0.23</td>
      </tr>
      <tr>
        <th>2023-05-12</th>
        <td>0.24</td>
        <td>173.75</td>
        <td>0.24</td>
      </tr>
      <tr>
        <th>2023-08-11</th>
        <td>0.24</td>
        <td>177.97</td>
        <td>0.24</td>
      </tr>
      <tr>
        <th>2023-11-10</th>
        <td>0.24</td>
        <td>182.41</td>
        <td>0.24</td>
      </tr>
    </tbody>
  </table>
</div>


<div class="python section-example-container">
    <pre class="python"># Calculate the mean dividend payment.
mean_dividend = history.distribution.mean()</pre>
</div>

<p class='python'>
  If you intend to use the data in the DataFrame to create <code><?=$dataType?></code> objects, request that the history request returns the data type you need. 
  Otherwise, LEAN consumes unnecessary computational resources populating the DataFrame.  
  To get a list of <code><?=$dataType?></code> objects instead of a DataFrame, call the <code>history[<?=$dataType?>]</code> method.
</p>


<div class="python section-example-container">
    <pre class="python"># Get the dividends that a stock paid over the last 2 years in Dividend format. 
history = self.history[Dividend](symbol, timedelta(2*365))
# Iterate through each Dividend object.
for dividend in history:
    distribution = dividend.distribution</pre>
</div>
