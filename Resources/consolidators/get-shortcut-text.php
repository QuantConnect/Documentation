<p>If you use the <code>Consolidate</code> helper method, the consolidation handler doesn't receive an <code>object</code> argument.</p>
<div class='section-example-container'>
	<pre class='csharp'>void ConsolidationHandler(<?=$consolidationHandlerType?> consolidatedBar)
{

}</pre>
	<pre class='python'>def _consolidation_handler(self, consolidated_bar: <?=$consolidationHandlerType?>) -&gt; None:
    pass</pre>
</div>

