<p>By default, LEAN only subscribes to the continuous Future contract. A continuous Future contract represents a series of separate contracts stitched together to form a continuous price. If you need a lot of historical data to warm up an indicator, apply the indicator to the continuous contract price series. The <code>Future</code> object has a <code>Symbol</code> property and a <code>Mapped</code> property. The price of the <code>Symbol</code> property is the adjusted price of the continuous contract. The price of the <code>Mapped</code> property is the raw price of the currently selected contract in the continuous contract series.</p>

<div class="section-example-container">
    <pre class="csharp">// Get the adjusted price of the continuous contract
var adjustedPrice = Securities[_future.Symbol].Price; 

// Get the raw price of the currently selected contract in the continuous contract series
var rawPrice = Securities[_future.Mapped].Price;</pre>
    <pre class="python"># Get the adjusted price of the continuous contract
adjusted_price = self.Securities[self.future.Symbol].Price 

# Get the raw price of the currently selected contract in the continuous contract series
raw_price = self.Securities[self.future.Mapped].Price</pre>
</div>


<p>To configure how LEAN identifies the current Future contract in the continuous series and how it forms the adjusted price between each contract, provide <code>dataMappingMode</code>, <code>dataNormalizationMode</code>, and <code>contractDepthOffset</code> arguments to the <code>AddFuture</code> method. The <code>Future</code> object that the <code>AddFuture</code> method returns contains a <code>Mapped</code> property that references the current contract in the continuous contract series. As the contracts roll over, the <code>Mapped</code> property references the next contract in the series and you receive a <code>SymbolChangedEvent</code> object in the <code>OnData</code> method. The <code>SymbolChangedEvent</code> references the old contract <code>Symbol</code> and the new contract <code>Symbol</code>. You can use <code>SymbolChangedEvents</code> to roll over contracts.</p>

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
    <pre class="python">def OnData(self, slice: Slice) -> None:
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

<h4>Data Normalization Modes</h4>
<p>The <code>dataNormalizationMode</code> argument defines how the price series of two contracts are stitched together when the contract rollovers occur. The following <code>DataNormalizatoinMode</code> enumeration members are available for continuous contracts:</p>
<div data-tree='QuantConnect.DataNormalizationMode' data-fields='Raw,ForwardPanamaCanal,BackwardsPanamaCanal,BackwardsRatio'></div>
<p>We use the entire Futures history to adjust historical prices. This process ensures you get the same adjusted prices, regardless of the backtest end date.</p>

<h4>Data Mapping Modes</h4>
<p>The <code>dataMappingMode</code> argument defines when contract rollovers occur. The <code>DataMappingMode</code> enumeration has the following members:</p>
<div data-tree="QuantConnect.DataMappingMode"></div>


<h4>Contract Depth Offsets</h4>
<p>The <code>contractDepthOffset</code> argument defines which contract to use. 0 is the front month contract, 1 is the following back month contract, and 3 is the second back month contract.</p>
