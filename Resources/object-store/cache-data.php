<? $location = $isAlgorithm ? "algorithm" : "notebook" ; ?>

<p>When you write to or read from the Object Store, the <?=$location?> caches the data. The cache speeds up the <?=$location?> execution because if you try to read the Object Store data again with the same key, it returns the cached data instead of downloading the data again. The cache speeds up execution, but it can cause problems if you are trying to share data between two nodes under the same Object Store key. For example, consider the following scenario:</p>
    
    <ol>
        <li>You open project A and save data under the key <code>123</code>.</li>
        <li>You open project B and save new data under the same key <code>123</code>.</li>
        <li>In project A, you read the Object Store data under the key <code>123</code>, expecting the data from project B, but you get the original data you saved in step #1 instead.</li>
        <p>You get the data from step 1 instead of step 2 because the cache contains the data from step 1.</p>
    </ol>
        
    <p>To clear the cache, call the <code>Clear</code> method.</p>
    
<div class='section-example-container'>
    <pre class='csharp'><?=$cSharpPrefix?>ObjectStore.Clear();</pre>
    <pre class='python'><?=$pythonPrefix?>object_store.clear()</pre>
</div>