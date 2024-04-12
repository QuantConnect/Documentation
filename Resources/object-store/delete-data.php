<p>Delete objects in the Object Store to remove objects that you no longer need. If you <?=$context?> in QuantConnect Cloud, you need <a href='/docs/v2/cloud-platform/organizations/members#08-Permissions'>storage delete permissions</a> to delete data from the Object Store.</p>

<p>To delete objects from the Object Store, call the <code>Delete</code> method. Before you delete data, check if the key exists. If you try to delete an object with a key that doesn't exist in the Object Store, the method raises an exception.</p>
<div class='section-example-container'>
    <pre class='csharp'>if (<?=$cSharpPrefix?>ObjectStore.ContainsKey(key))
{
    <?=$cSharpPrefix?>ObjectStore.Delete(key);
}</pre>
    <pre class='python'>if <?=$pythonPrefix?>object_store.contains_key(key):
    <?=$pythonPrefix?>object_store.delete(key)</pre>
</div>
<p>To delete all of the content in the Object Store, iterate through all the stored data.</p>
<div class='section-example-container'>
    <pre class='csharp'>foreach (var kvp in <?=$cSharpPrefix?>ObjectStore)
{
    <?=$cSharpPrefix?>ObjectStore.Delete(kvp.Key);
}</pre>
    <pre class='python'>for kvp in <?=$pythonPrefix?>object_store:
    <?=$pythonPrefix?>object_store.delete(kvp.key)</pre>
</div>