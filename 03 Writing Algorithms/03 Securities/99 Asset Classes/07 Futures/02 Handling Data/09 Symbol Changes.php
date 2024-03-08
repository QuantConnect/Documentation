<p>When the <a href='/docs/v2/writing-algorithms/universes/futures#12-Continous-Contracts'>continuous contract</a> rolls over, LEAN passes a <code>SymbolChangedEvent</code> to your <code>OnData</code> method, which contains the old contract <code>Symbol</code> and the new contract <code>Symbol</code>. <code>SymbolChangedEvent</code> objects have the following properties:</p>

<div data-tree='QuantConnect.Data.Market.SymbolChangedEvent'></div>

<p>To get the <code>SymbolChangedEvent</code>, use the <code>SymbolChangedEvents</code> property of the <code>Slice</code>. You can use <code>SymbolChangedEvents</code> to roll over contracts.</p>

<div class="section-example-container">
    <pre class="csharp">public override void OnData(Slice slice)
{
    foreach (var (symbol, changedEvent) in slice.SymbolChangedEvents)
    {
        var oldSymbol = changedEvent.OldSymbol;
        var newSymbol = changedEvent.NewSymbol;
        var tag = $"Rollover - Symbol changed at {Time}: {oldSymbol} -> {newSymbol}";
        var quantity = Portfolio[oldSymbol].Quantity;
        // Rolling over: to liquidate any position of the old mapped contract and switch to the newly mapped contract
        Liquidate(oldSymbol, tag: tag);
        if (quantity != 0) MarketOrder(newSymbol, quantity, tag: tag);
        Log(tag);
    }
}</pre>
    <pre class="python">def OnData(self, slice: Slice) -&gt; None:
    for symbol, changed_event in  slice.SymbolChangedEvents.items():
        old_symbol = changed_event.OldSymbol
        new_symbol = changed_event.NewSymbol
        tag = f"Rollover - Symbol changed at {self.Time}: {old_symbol} -> {new_symbol}"
        quantity = self.Portfolio[old_symbol].Quantity

        # Rolling over: to liquidate any position of the old mapped contract and switch to the newly mapped contract
        self.Liquidate(old_symbol, tag = tag)
        if quantity != 0: self.MarketOrder(new_symbol, quantity, tag = tag)
        self.Log(tag)</pre>
</div>

<p>In backtesting, the <code>SymbolChangedEvent</code> occurs at midnight Eastern Time (ET). In live trading, the live data for continuous contract mapping arrives at 6/7 AM ET, so that's when it occurs.</p>
