<p>When the <a href='/docs/v2/writing-algorithms/universes/futures#12-Continous-Contracts'>continuous contract</a> rolls over, LEAN passes a <code>SymbolChangedEvent</code> to your <code class="csharp">OnData</code><code class="python">on_data</code> method, which contains the old contract <code>Symbol</code> and the new contract <code>Symbol</code>. <code>SymbolChangedEvent</code> objects have the following properties:</p>

<div data-tree='QuantConnect.Data.Market.SymbolChangedEvent'></div>

<p>To get the <code>SymbolChangedEvent</code>, use the <code class='csharp'>SymbolChangedEvents</code><code class='python'>symbol_changed_events</code> property of the <code>Slice</code>. You can use the <code>SymbolChangedEvent</code> to roll over contracts.</p>

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
    <pre class="python">def on_data(self, slice: Slice) -&gt; None:
    for symbol, changed_event in  slice.symbol_changed_events.items():
        old_symbol = changed_event.old_symbol
        new_symbol = changed_event.new_symbol
        tag = f"Rollover - Symbol changed at {self.time}: {old_symbol} -> {new_symbol}"
        quantity = self.portfolio[old_symbol].quantity

        # Rolling over: to liquidate any position of the old mapped contract and switch to the newly mapped contract
        self.liquidate(old_symbol, tag=tag)
        if quantity: self.market_order(new_symbol, quantity, tag=tag)
        self.log(tag)</pre>
</div>

<p>In backtesting, the <code>SymbolChangedEvent</code> occurs at midnight Eastern Time (ET). In live trading, the live data for continuous contract mapping arrives at 6/7 AM ET, so that's when it occurs.</p>
