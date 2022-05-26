<p>To get all of the keys and values in the Object Store, iterate through the <code>ObjectStore</code> object.</p>

<div class="section-example-container">
    <pre class="csharp">foreach (var kvp in ObjectStore)
{
    var key = kvp.Key;
    var value = kvp.Value;
}</pre>
    <pre class="python">for kvp in self.ObjectStore:
    key = kvp.Key
    value = kvp.Value</pre>
</div>
