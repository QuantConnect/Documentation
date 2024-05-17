<p>To add a custom universe to your algorithm, in the <code class="csharp">Initialize</code><code class="python">initialize</code> method, pass your universe type and a selector function to the <code class="csharp">AddUniverse</code><code class="python">add_universe</code> method.</p>

<div class="section-example-container">
<pre class="csharp">AddUniverse&lt;MyCustomUniverseDataClass&gt;("myCustomUniverse", Resolution.Daily, SelectorFunction)</pre>
<pre class="python">self.add_universe(MyCustomUniverseDataClass, "myCustomUniverse", Resolution.DAILY, self._selector_function)</pre>
</div>
