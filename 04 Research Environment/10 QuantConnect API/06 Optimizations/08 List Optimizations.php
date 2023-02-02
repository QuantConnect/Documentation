<p>To get all the optimization results for a project, call the <code>ListOptimizations</code> method with the project Id.</p>
<div class="section-example-container">
    <pre class="csharp">var optimizations = api.ListOptimizations(projectId);
foreach (var optimization in optimizations)
{

}</pre>
    <pre class="python">optimizations = api.ListOptimizations(project_id)
for optimization in optimizations:
	pass</pre>
</div>

<?php include(DOCS_RESOURCES."/qc-api/get-project-id-in-research.html"); ?>

<p>The <code>ListOptimizations</code> method returns a list of <code>BaseOptimization</code> objects, which have the following attributes:</p>

<div data-tree="QuantConnect.Api.BaseOptimization"></div>