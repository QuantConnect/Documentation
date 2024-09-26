<p>To check if you have a position open for a security as defined by the <code>FillGroupingMethod</code>, call the <code class="csharp">HasOpenPosition</code><code class="python">has_open_position</code> method.</p>

<div class="section-example-container">
<pre class="csharp">// Check if the trade builder has recorded any active trades (unclosed positions)
// for a security.
var hasOpenPosition = TradeBuilder.HasOpenPosition(_symbol);</pre>
<pre class="python"># Check if the trade builder has recorded any active trades (unclosed positions)
# for a security.
has_open_position = self.trade_builder.has_open_position(self._symbol)</pre>
</div>

