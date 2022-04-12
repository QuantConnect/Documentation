<p>
Follow these steps to manually create a consolidator:</p>
<p>1) Create a RenkoConsolidator object and pass the constructor bar size.</p>

<p>2) Define the consolidation event handler</p>

<p>3) Add the event handler to the consolidator</p>

<div class="section-example-container">
<pre class="python">def Initialize(self):
    # Create Renko consolidator to trigger event when price moves $2.50
    self.consolidator = RenkoConsolidator(2.5)
    self.consolidator.DataConsolidated += self.consolidation_handler
    
def consolidation_handler(self, sender, consolidated_bar):
    # Bar period is now 30 min from the consolidator above.
    self.Debug(str(consolidated_bar.EndTime - consolidated_bar.Time) + " " + consolidated_bar.ToString())</pre>
<pre class="csharp">public override void Initialize()
{ 
    // Create Renko consolidator to trigger event when price moves $2.50
    _consolidator = new RenkoConsolidator(2.5m);
    _consolidator.DataConsolidated += ConsolidationHandler;
}

private void ConsolidationHandler(object sender, TradeBar consolidatedBar) 
    Debug((consolidatedBar.EndTime - consolidatedBar.Time).ToString() + " " + consolidatedBar.ToString());
}</pre>
</div>