<p>To get all of the keys, values, and file paths in the Object Store, iterate through the <code>ObjectStore</code> object.</p>

<div class="section-example-container">
    <pre class="csharp">foreach (var kvp in ObjectStore)
{
    var key = kvp.Key;
    var value = kvp.Value;
    var filePath = ObjectStore.GetFilePath(key);
}</pre>
    <pre class="python">for kvp in self.ObjectStore:
    key = kvp.Key
    value = kvp.Value
    file_path = self.ObjectStore.GetFilePath(key)</pre>
</div>