
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
