<?
$dataTypeLink = "/docs/v2/writing-algorithms/securities/asset-classes/us-equity/corporate-actions#05-Delistings";
$dataType = "Delisting";
?>

<p class='csharp'>
  To get historical <a href='<?=$dataTypeLink?>'>delisting data</a>, call the <code>History&lt;Delisting&gt;</code> method with an asset's <code>Symbol</code>.
  Delistings are a sparse dataset, so use a time period history request since most days have no data.
</p>

<p class='python'>
  To get historical <a href='<?=$dataTypeLink?>'>delisting data</a>, call the <code>history</code> method with the <code>Delisting</code> type and an asset's <code>Symbol</code>.
  This method returns a DataFrame with columns for the old symbol and new symbol during each change.
  Delistings are a sparse dataset, so use a time period history request since most days have no data.
</p>

<div class="section-example-container">
    <pre class="csharp">public class USEquityDelistingHistoryAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        SetStartDate(2024, 12, 1);
        // Get the Symbol of an asset.
        var symbol = AddEquity("BBBY").Symbol;
        // Get the deslistings of the asset over the last 10 years. 
        var history = History&lt;Delisting&gt;(symbol, TimeSpan.FromDays(10*365));
        // Get the dates of the delist warnings.
        var delistWarningDates = history
            .Where(delisting => delisting.Type == DelistingType.WARNING)
            .Select(delisting => delisting.EndTime);
    }
}</pre>
    <pre class="python">class USEquityDelistingHistoryAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 12, 1)
        # Get the Symbol of an asset.
        symbol = self.add_equity('BBBY').symbol
        # Get the deslistings of the asset over the last 10 years in DataFrame format.
        history = self.history(Delisting, symbol, timedelta(10*365))</pre>
</div>

<div class="dataframe-wrapper">
  <table class="dataframe python">
    <thead>
      <tr style="text-align: right;">
        <th></th>
        <th></th>
        <th>type</th>
      </tr>
      <tr>
        <th>symbol</th>
        <th>time</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th rowspan="2" valign="top">BBBY</th>
        <th>2023-05-02</th>
        <td>0</td>
      </tr>
      <tr>
        <th>2023-05-03</th>
        <td>1</td>
      </tr>
    </tbody>
  </table>
</div>


<p class='python'>In the preceding DataFrame, the <code>type</code> column represents the <code>DelistingType</code> enumeration, where 0=<code>DelistingType.WARNING</code> and 1=<code>DelistingType.DELISTED</code>.</p>

<div class="python section-example-container">
    <pre class="python"># Select the rows in the DataFrame that represent deslist warnings.
warnings = history[history.type == DelistingType.WARNING].type</pre>
</div>

<div class="python section-example-container">
    <pre>symbol  time      
BBBY    2023-05-02    0
Name: type, dtype: int64</pre>
</div>

<p class='python'>
  If you intend to use the data in the DataFrame to create <code><?=$dataType?></code> objects, request that the history request returns the data type you need. 
  Otherwise, LEAN consumes unnecessary computational resources populating the DataFrame.  
  To get a list of <code><?=$dataType?></code> objects instead of a DataFrame, call the <code>history[<?=$dataType?>]</code> method.
</p>

<div class="python section-example-container">
    <pre class="python"># Get the deslistings of an asset over the last 10 years in Delisting format. 
history = self.history[Delisting](symbol, timedelta(10*365))
# Iterate through each Deslisting object and access the warning dates.
for deslisting in history:
    if deslisting.type == DelistingType.WARNING:
        warning_date = deslisting.end_time</pre>
</div>
