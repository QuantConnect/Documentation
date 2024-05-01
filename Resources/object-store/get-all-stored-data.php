<p>To get all of the keys and values in the Object Store, iterate through the <code class="csharp">ObjectStore</code><code class="python">object_store</code> property.</p>

<div class='section-example-container'>
    <pre class='csharp'>foreach (var kvp in <?=$research ? "qb." : ""?>ObjectStore)
{
    var key = kvp.Key;
    var value = kvp.Value;
}</pre>
    <pre class='python'>for kvp in <?=$research ? "qb." : "self."?>object_store:
    key = kvp.key
    value = kvp.value</pre>
</div>

<p>To iterate through just the keys in the Object Store, iterate through the <code class="csharp">Keys</code><code class="python">keys</code> property.</p>

<div class='section-example-container'>
    <pre class='csharp'>foreach (var key in <?=$research ? "qb." : ""?>ObjectStore.Keys)
{
    continue;
}</pre>
    <pre class='python'>for key in <?=$research ? "qb." : "self."?>object_store.keys:
    continue</pre>
</div>