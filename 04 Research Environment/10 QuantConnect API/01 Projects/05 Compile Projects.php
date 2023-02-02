<p>To create a new compile job request for a project, call the <code>CreateCompile</code> method with the project ID.</p>
<div class="section-example-container">
    <pre class="csharp">var compile = api.CreateCompile(projectId);</pre>
    <pre class="python">compile = api.CreateCompile(project_id)</pre>
</div>

<?php include(DOCS_RESOURCES."/qc-api/get-project-id-in-research.html"); ?>

<p>To read a compile packet job result, call the <code>ReadCompile</code> method with the project Id and compile ID.</p>
<div class="section-example-container">
    <pre class="csharp">var compileResults = api.ReadCompile(projectId, compile.CompileId);</pre>
    <pre class="python">compile_results = api.ReadCompile(project_id, compile.CompileId)</pre>
</div>

<p>The <code>CreateCompile</code> and <code>ReadCompile</code> methods return a <code>Compile</code> object, which have the following attributes:</p>
<div data-tree="QuantConnect.Api.Compile"></div>