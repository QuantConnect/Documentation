<p>To free up storage space, delete the key-value pairs in the ObjectStore by calling the <code>Delete</code> method with a key.</p>
<div class="section-example-container">
    <pre class="csharp">ObjectStore.Delete(key);</pre>
    <pre class="python">self.ObjectStore.Delete(key)</pre>
</div>

<? if ($localPlatform) { ?> <p>Alternative, delete the files in <span class="public-file-name"><a href="/docs/v2/local-platform/development-environment/organization-workspaces">&lt;organizationWorkspace&gt;</a> / &lt;projectName&gt; / storage</span> directory directory.</p> <? } ?>
