<p>If you train models locally or in another environment, you can upload the model files to the Object Store so your algorithms can use them. Use the model key that matches the key your algorithm expects when it calls <code class="python">object_store.get_file_path</code>.</p>

<p>Follow one of these approaches to upload your model files:</p>

<h4>LEAN CLI</h4>
<p>Run the <a href='/docs/v2/lean-cli/api-reference/lean-cloud-object-store-set'><code>lean cloud object-store set</code></a> command to upload a local file to the Object Store.</p>
<?php if (is_array($modelName)) { ?>
<div class="cli section-example-container">
<?php foreach ($modelName as $name) { ?>
<pre>$ lean cloud object-store set &lt;projectId&gt;/<?=$name?> &lt;pathTo&gt;/<?=$name?></pre>
<?php } ?>
</div>
<?php } else { ?>
<div class="cli section-example-container">
<pre>$ lean cloud object-store set &lt;projectId&gt;/<?=$modelName?> &lt;pathTo&gt;/<?=$modelName?></pre>
</div>
<?php } ?>
<p>Replace <code>&lt;projectId&gt;</code> with your project Id and <code>&lt;pathTo&gt;</code> with the path to the local model file.</p>

<h4>Cloud API</h4>
<p>Use the <a href='/docs/v2/cloud-platform/api-reference/object-store-management/upload-object-store-files'>Upload Object Store Files</a> endpoint to upload a model file through the API.</p>
