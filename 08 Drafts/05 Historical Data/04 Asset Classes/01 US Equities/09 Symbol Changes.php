<?
$dataTypeLink = "/docs/v2/writing-algorithms/securities/asset-classes/us-equity/corporate-actions#04-Symbol-Changes";
$dataType = "SymbolChangedEvent";
?>

<p class='csharp'>
  To get historical <a href='<?=$dataTypeLink?>'>symbol change data</a>, call the <code>History&lt;SymbolChangedEvent&gt;</code> method with an asset's <code>Symbol</code>.
</p>

<p class='python'>
  To get historical <a href='<?=$dataTypeLink?>'>symbol change data</a>, call the <code>history</code> method with the <code>SymbolChangedEvent</code> type and an asset's <code>Symbol</code>.
  This method returns a DataFrame with columns for the old symbol and new symbol during each change.
</p>

<div class="section-example-container">
    <pre class="csharp">public class USEquitySymbolChangedEventHistoryAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        SetStartDate(2024, 12, 1);
        // Get the Symbol of an asset.
        var symbol = AddEquity("META").Symbol;
        // Get the symbol changes of the stock over the last 10 years. 
        var history = History&lt;SymbolChangedEvent&gt;(symbol, TimeSpan.FromDays(10*365));
        // Iterate through each SymbolChangedEvent object to access their data point attributes.
        foreach (var symbolChangedEvent in history)
        {
            var t = symbolChangedEvent.EndTime;
            var oldSymbol = symbolChangedEvent.OldSymbol;
            var newSymbol = symbolChangedEvent.NewSymbol;
        }
    }
}</pre>
    <pre class="python">class USEquitySymbolChangedEventHistoryAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 12, 1)
        # Get the Symbol of an asset.
        symbol = self.add_equity('META').symbol
        # Get the symbol changes of the stock over the last 10 years in DataFrame format. 
        history = self.history(SymbolChangedEvent, symbol, timedelta(10*365))</pre>
</div>

<table border="1" class="dataframe python">
  <thead>
    <tr style="text-align: right;">
      <th></th>
      <th></th>
      <th>newsymbol</th>
      <th>oldsymbol</th>
    </tr>
    <tr>
      <th>symbol</th>
      <th>time</th>
      <th></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th>META</th>
      <th>2022-06-09</th>
      <td>META</td>
      <td>FB</td>
    </tr>
  </tbody>
</table>


<div class="python section-example-container">
    <pre class="python"># Select the dates of each ticker change.
dates = list(history.index.levels[1])</pre>
</div>

<p class='python'>
  If you intend to use the data in the DataFrame to create <code><?=$dataType?></code> objects, request that the history request returns the data type you need. 
  Otherwise, LEAN consumes unnecessary computational resources populating the DataFrame.  
  To get a list of <code><?=$dataType?></code> objects instead of a DataFrame, call the <code>history[<?=$dataType?>]</code> method.
</p>

<div class="python section-example-container">
    <pre class="python"># Get the symbol changes of a stock over the last 10 years in SymbolChangedEvent format. 
history = self.history[SymbolChangedEvent](symbol, timedelta(10*365))
# Iterate through each SymbolChangedEvent object.
for symbol_changed_event in history:
    t = symbol_changed_event.end_time
    old_symbol = symbol_changed_event.old_symbol
    new_symbol = symbol_changed_event.new_symbol</pre>
</div>
