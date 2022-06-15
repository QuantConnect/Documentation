<p>A continuous Future contract represents a series of separate contracts stitched together to form a continuous price. The <code>Future</code> object that the <code>AddFuture</code> method returns contains a <code>Mapped</code> property that references the current contract in the continuous contract series. As the contracts rollover, the <code>Mapped</code> property references the next contract in the series. If you need a lot of historical data to warm up an indicator, apply the indicator to the continuous contract price series.</p>

<p>To configure how LEAN identifies the current Future contract in the continuous series, provide <code>dataMappingMode</code>, <code>dataNormalizationMode</code>, and <code>contractDepthOffset</code> arguments to the <code>AddFuture</code> method. When the contract rolls over, you receive a <code>SymbolChangedEvent</code> object in the <code>OnData</code> method. The <code>SymbolChangedEvent</code> references the old contract <code>Symbol</code> and the new contract <code>Symbol</code>.</p>

<div class="section-example-container">
    <pre class="csharp"></pre>
    <pre class="python">def OnData(self, data):
    for changed_event in data.SymbolChangedEvents.Values:
        self.Log(f"Contract rollover from {changed_event.OldSymbol} to {changed_event.NewSymbol}")</pre>
</div>

<h4>Price Scaling</h4>

<?php echo file_get_contents(DOCS_RESOURCES."/enumerations/data_mapping_mode.html"); ?>

<h4>Contract Rollover Logic</h4>
(dataNormalizationMode)
<br>-LastTradingDay: The contract maps on the previous day of expiration of the front month.
<br>-FirstDayMonth: The contract maps on the first date of the delivery month of the front month. If the contract expires prior to this date, then it rolls on the contract's last trading date instead.
<br>-OpenInterest: The contract maps when the back month contract has a higher traded volume that the current front month.

<h4>Front and Back Months</h4>
(contractDepthOffset)
<br>- From the `AddFuture` API `contractDepthOffset` will allow specifying which contract to use, 0 (default) is the front month, 1 the following back month and so on. Initially we have added support for the first 2 back months contracts.
