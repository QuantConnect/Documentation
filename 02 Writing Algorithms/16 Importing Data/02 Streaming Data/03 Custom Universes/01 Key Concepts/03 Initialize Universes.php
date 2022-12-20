<p>To add a custom universe to your algorithm, in the <code>Initialize</code> method, pass your universe type and a selector function to the <code>AddUniverse</code> method.</p>

<div class="section-example-container">
<pre class="csharp">AddUniverse&lt;MyCustomUniverseDataClass&gt;("myCustomUniverse", Resolution.Daily, SelectorFunction)</pre>
<pre class="python">self.AddUniverse(MyCustomUniverseDataClass, "myCustomUniverse", Resolution.Daily, self.selector_function)</pre>
</div>
