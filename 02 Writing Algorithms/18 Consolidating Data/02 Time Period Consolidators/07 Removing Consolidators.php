<p>Manually creating consolidators gives you the most control over how they are updated and when they are removed.</p>

<h4>Creating Consolidators</h4>
<p>
Follow these steps to manually create a consolidator:</p><p>1) Create a consolidator object (TradeBarConsolidator, QuoteBarConsolidator, TickConsolidator, or TickQuoteConsolidator)</p><p>2) Define the consolidation event handler</p><p>3) Add the event handler to the consolidator</p>

<div class="section-example-container">
<pre class="python">def Initialize(self):
    self.consolidator = TradeBarConsolidator(timedelta(minutes=30))
    self.consolidator.DataConsolidated += self.consolidation_handler
    
def consolidation_handler(self, sender, consolidated_bar):
    # Bar period is now 30 min from the consolidator above.
    self.Debug(str(consolidated_bar.EndTime - consolidated_bar.Time) + " " + consolidated_bar.ToString())
</pre>
<pre class="csharp">public override void Initialize()
{ 
    _consolidator = new TradeBarConsolidator(TimeSpan.FromMinutes(30));
    _consolidator.DataConsolidated += ConsolidationHandler;
}

private void ConsolidationHandler(object sender, TradeBar consolidatedBar) {
    // Bar period is 30 min from the consolidator above.
    Debug((consolidatedBar.EndTime - consolidatedBar.Time).ToString() + " " + consolidatedBar.ToString());
}
</pre>


<h4>Updating the Aggregated Bar</h4>

<p>You can update the consolidator manually or automatically.</p><p>- To manually update a consolidator, pass a Tick, TradeBar, or QuoteBar (depending on the type of consolidator) to it's `Update` method.</p>

<div class="section-example-container">
<pre class="python">self.consolidator.Update(data)</pre>
<pre class="csharp">_consolidator.Update(data);</pre>
</div>

<p>- To automatically update a consolidator with a data subscription, create the subscription and then register the consolidator with the Subscription Manager.</p>

<div class="section-example-container">
<pre class="python">def Initialize(self):
    # Make sure you have the data you need
    self.symbol = self.AddEquity("QQQ", Resolution.Minute).Symbol

    # Create consolidator you need and attach event handler
    self.consolidator = TradeBarConsolidator(timedelta(minutes=30))
    self.consolidator.DataConsolidated += self.consolidation_handler

    # Register consolidator to get automatically updated with minute data
    self.SubscriptionManager.AddConsolidator(self.symbol, self.consolidator)
</pre>
<pre class="csharp">public override void Initialize()
{ 
     // Make sure you have the data you need
    _symbol = AddEquity("QQQ", Resolution.Minute).Symbol;

    // Create consolidator you need and attach event handler
    _consolidator = new TradeBarConsolidator(TimeSpan.FromMinutes(30));
    _consolidator.DataConsolidated += ConsolidationHandler;

    // Register consolidator to get automatically updated with minute data
    SubscriptionManager.AddConsolidator(_symbol, _consolidator);
}
</pre>
</div>

<h4>Receiving Consolidated Bars</h4>

<p>The consolidation handler accepts two arguments, the sender (consolidator object) and the consolidated bar. The consolidated bars are automatically passed to your consolidation handler when the consolidated bar closes.<br>

</p><h4>Removing Consolidators</h4>
<p>If you manually create a consolidator for a universe subscription and register it to automatic updates, remove the consolidator once the security leaves your universe. If you do not "tidy up", the consolidators can compound internally, causing your algorithm to slow down and eventually die once it runs out of RAM. To remove consolidators, save a reference to the consolidator when you create it. We recommend using a class to organize all of the symbol-specific objects created over the lifetime of a security in your universe. See this example Alpha as an example of removing consolidators from universe subscriptions. </p>

<div class="section-example-container">
<pre class="csharp">self.SubscriptionManager.RemoveConsolidator(_symbol, _consolidator)
</pre>
<pre class="python">self.SubscriptionManager.RemoveConsolidator(self.symbol, self.consolidator)
</pre>
</div></div>
