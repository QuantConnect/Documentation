What: 
<br>- If your algorithm has a dynamic universe, when you remove securities from your universe, remove the consolidator that updates the security's indicator.
<br><br>Why:
<br>Remove the consolidators in dynamic universes so that the list of consolidators doesn't compound over time and slow down your algorithm.
<br><br>How:
<br>-To remove the consolidator that's updating an indicator, you need to save a reference to the consolidator.
<br>- To save a reference to the consolidator, either:
<br>&nbsp;&nbsp;&nbsp;- If you use the RegisterIndicator method, pass a consolidator to the method
<div class="section-example-container">
<pre class="csharp"></pre>
<pre class="python">self.consolidator = TradeBarConsolidator(30)
self.RegisterIndicator(self.symbol, self.indicator, self.consolidator)</pre>
</div>
&nbsp;&nbsp;&nbsp;- If you don't use the RegisterIndicator method, save a reference to the consolidator
<div class="section-example-container">
<pre class="csharp"></pre>
<pre class="python">self.consolidator = TradeBarConsolidator(30)
self.consolidator += self.consolidation_handler # Update the indicator in the consolidation handler</pre>
</div>
<br>-Now that you have a reference to the consolidator, you can remove it
<div class="section-example-container">
<pre class="csharp"></pre>
<pre class="python">algorithm.SubscriptionManager.RemoveConsolidator(self.symbol, self.consolidator)</pre>
</div>