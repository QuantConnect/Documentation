<p>To get the file path for a specific key in the Object Store, call the <code>GetFilePath</code> method. If the key you pass to the method doesn't already exist in the Object Store, it's added to the Object Store.</p> 

<div class='section-example-container'>
    <pre class='csharp'>var filePath = <?=$research ? "qb." : ""?>ObjectStore.GetFilePath(key);</pre>
    <pre class='python'>file_path = <?=$research ? "qb." : "self."?>object_store.get_file_path(key)</pre>
</div>