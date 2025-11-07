<p>If you <?=$research ? "use the Research Environment" : "run algorithms" ?> locally, you can store as much data as your hardware will allow. If you <?=$research ? "use the Research Environment" : "run algorithms" ?> in QuantConnect Cloud, you must stay within your <a href='/docs/v2/cloud-platform/object-store#05-Storage-Sizes'>storage quota</a>. To find your storage quota programmatically, use the <span class="csharp"><code>MaxSize</code> property</span><span class="python"><code>max_size</code> attribute</span> for storage size and <span class="csharp"><code>MaxFiles</code> property</span><span class="python"><code>max_files</code> attribute</span> for the maximum number of files.</p>

<div class='section-example-container'>
    <pre class='csharp'>var maxSize = <?=$research ? "qb." : ""?>ObjectStore.MaxSize;
var maxFiles = <?=$research ? "qb." : ""?>ObjectStore.MaxFiles;</pre>
    <pre class='python'>max_size = <?=$research ? "qb." : "self."?>object_store.max_size
max_files = <?=$research ? "qb." : "self."?>object_store.max_files</pre>
</div>

<p>If you need more storage space, <a href='/docs/v2/cloud-platform/object-store#07-Edit-Storage-Plan'>edit your storage plan</a>.</p>