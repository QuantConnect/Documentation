<p>To get all of the keys and values in the Object Store, iterate through the <code>ObjectStore</code> object.</p>

<div class='section-example-container'>
    <pre class='csharp'>foreach (var kvp in <?=$research ? "qb." : ""?>ObjectStore)
{
    var key = kvp.Key;
    var value = kvp.Value;
}</pre>
    <pre class='python'>for kvp in <?=$research ? "qb." : "self."?>ObjectStore:
    key = kvp.Key
    value = kvp.Value</pre>
</div>

<p>To iterate through just the keys in the Object Store, iterate through the <code>Keys</code> property.</p>

<div class='section-example-container'>
    <pre class='csharp'>foreach (var key in <?=$research ? "qb." : ""?>ObjectStore.Keys)
{
    continue;
}</pre>
    <pre class='python'>for key in <?=$research ? "qb." : "self."?>ObjectStore.Keys:
    continue</pre>
</div>