-For contract rollover of the continuous contract
<br>-Determined by the dataMappingMode when you call AddFuture
<br>-Example of detecting symbol changes in OnData

<div data-tree='QuantConnect.Data.Market.SymbolChangedEvent'></div>

<div class="section-example-container">
    <pre class="csharp"></pre>
    <pre class="python">def OnData(self, data):
    for changed_event in data.SymbolChangedEvents.Values:
        self.Log(f"Contract rollover from {changed_event.OldSymbol} to {changed_event.NewSymbol}")</pre>
</div>