<? include(DOCS_RESOURCES."/universes/future/continous-contracts-intro.html"); ?>

<p>To configure how LEAN identifies the current Future contract in the continuous series and how it forms the adjusted price between each contract, provide <code class="csharp">dataMappingMode</code><code class="python">data_mapping_mode</code>, <code class="csharp">dataNormalizationMode</code><code class="python">data_normalization_mode</code>, and <code class="csharp">contractDepthOffset</code><code class="python">contract_depth_offset</code> arguments to the <code class="csharp">AddFuture</code><code class="python">add_future</code> method. The <code>Future</code> object that the <code class="csharp">AddFuture</code><code class="python">add_future</code> method returns contains a <code class="csharp">Mapped</code><code class="python">mapped</code> property that references the current contract in the continuous contract series. As the contracts roll over, the <code class="csharp">Mapped</code><code class="python">mapped</code> property references the next contract in the series and you receive a <code>SymbolChangedEvent</code> object in the <code class="csharp">OnData</code><code class="python">on_data</code> method. The <code>SymbolChangedEvent</code> references the old contract <code>Symbol</code> and the new contract <code>Symbol</code>. You can use <code>SymbolChangedEvents</code> to roll over contracts.</p>

<div class="section-example-container">
    <pre class="csharp">public class BasicFutureAlgorithm : QCAlgorithm
{
    // Track when the continuous contract switches from one contract to the next.
    public override void OnSymbolChangedEvents(SymbolChangedEvents symbolChangedEvents)
    {
        foreach (var (symbol, changedEvent) in symbolChangedEvents)
        {
            var oldSymbol = changedEvent.OldSymbol;
            var newSymbol = changedEvent.NewSymbol;
            var quantity = Portfolio[oldSymbol].Quantity;

            // Rolling over: To liquidate the old mapped contract and switch to the new mapped contract.
            var tag = $"Rollover - Symbol changed at {Time}: {oldSymbol} -> {newSymbol}";
            Liquidate(oldSymbol, tag: tag);
            if (quantity != 0) MarketOrder(newSymbol, quantity, tag: tag);
        }
    }
}</pre>
    <pre class="python">class BasicFutureAlgorithm(QCAlgorithm):
    # Track when the continuous contract switches from one contract to the next.
    def on_symbol_changed_events(self, symbol_changed_events):
        for symbol, changed_event in  symbol_changed_events.items():
            old_symbol = changed_event.old_symbol
            new_symbol = changed_event.new_symbol
            quantity = self.portfolio[old_symbol].quantity

            # Rolling over: To liquidate the old mapped contract and switch to the new mapped contract.
            tag = f"Rollover - Symbol changed at {self.time}: {old_symbol} -> {new_symbol}"
            self.liquidate(old_symbol, tag=tag)
            if quantity: self.market_order(new_symbol, quantity, tag=tag)</pre>
</div>

<p>In backtesting, the <code>SymbolChangedEvent</code> occurs at midnight Eastern Time (ET). In live trading, the live data for continuous contract mapping arrives at 6/7 AM ET, so that's when it occurs.</p>

<h4>Data Normalization Modes</h4>
<p>The <code class="csharp">dataNormalizationMode</code><code class="python">data_normalization_mode</code> argument defines how the price series of two contracts are stitched together when the contract rollovers occur. The following <code>DataNormalizatoinMode</code> enumeration members are available for continuous contracts:</p>
<div data-tree='QuantConnect.DataNormalizationMode' data-fields='ForwardPanamaCanal,BackwardsPanamaCanal,BackwardsRatio,FORWARD_PANAMA_CANAL,BACKWARDS_PANAMA_CANAL,BACKWARDS_RATIO,'></div>
<p>We use the entire Futures history to adjust historical prices. This process ensures you get the same adjusted prices, regardless of the backtest end date.</p>

<h4>Data Mapping Modes</h4>
<p>The <code class="csharp">dataMappingMode</code><code class="python">data_mapping_mode</code> argument defines when contract rollovers occur. The <code>DataMappingMode</code> enumeration has the following members:</p>
<div data-tree="QuantConnect.DataMappingMode"></div>

<p><code>Futures.Indices.VIX</code> (VX) doesn't support continous contract rolling with <code class="csharp">DataMappingMode.OpenInterest</code><code class="python">DataMappingMode.OPEN_INTEREST</code> and <code class="csharp">DataMappingMode.OpenInterestAnnuak</code><code class="python">DataMappingMode.OPEN_INTEREST_ANNUAL</code>.</p>

<h4>Contract Depth Offsets</h4>
<p>The <code class="csharp">contractDepthOffset</code><code class="python">contract_depth_offset</code> argument defines which contract to use. 0 is the front month contract, 1 is the following back month contract, and 2 is the second back month contract.</p>
