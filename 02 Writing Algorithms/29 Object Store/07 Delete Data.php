<p>Delete objects in the Object Store to remove objects that you no longer need. If you run algorithms in QC Cloud, you must stay within your <a href='/docs/v2/our-platform/projects/data-storage#03-Storage-Sizes'>storage quota</a> to avoid issues. To delete data in the cloud Object Store, you need <a href='/docs/v2/our-platform/organizations/members#06-Permissions'>storage delete permissions</a>.</p>

<p>To delete objects from the Object Store, call the <code>Delete</code> method. Before you delete data, check if the key exists. If you try to delete an object with a key that doesn't exist in the Object Store, the method raises an exception.</p>

<div class="section-example-container">
    <pre class="csharp">if (ObjectStore.ContainsKey(key))
{
    ObjectStore.Delete(key);
}</pre>
    <pre class="python">if self.ObjectStore.ContainsKey(key):
    self.ObjectStore.Delete(key)</pre>
</div>

<p>To delete all of the content in the Object Store, iterate through all the stored data.</p>
<div class="section-example-container">
    <pre class="csharp">foreach (var kvp in ObjectStore)
{
    ObjectStore.Delete(kvp.Key);
}</pre>
    <pre class="python">for kvp in self.ObjectStore:
    self.ObjectStore.Delete(kvp.Key)</pre>
</div>
