<p>When the <a href='/docs/v2/writing-algorithms/universes/futures#12-Continous-Contracts'>continuous contract</a> rolls over, LEAN passes a <code>SymbolChangedEvent</code> to your <code>OnData</code> method, which contains the old contract <code>Symbol</code> and the new contract <code>Symbol</code>. <code>SymbolChangedEvent</code> objects have the following properties:</p>

<div data-tree='QuantConnect.Data.Market.SymbolChangedEvent'></div>

<p>To get the <code>SymbolChangedEvent</code>, use the <code>SymbolChangedEvents</code> property of the <code>Slice</code>.</p>

<div class="section-example-container">
    <pre class="csharp">public override void OnData(Slice slice)
{
    foreach(var changeEvent in slice.SymbolChangedEvents.Values)
    {
        Log($"Contract rollover from {changed_event.OldSymbol} to {changed_event.NewSymbol}");
    }
}</pre>
    <pre class="python">def OnData(self, slice: Slice) -&gt; None:
    for changed_event in slice.SymbolChangedEvents.Values:
        self.Log(f"Contract rollover from {changed_event.OldSymbol} to {changed_event.NewSymbol}")</pre>
</div>