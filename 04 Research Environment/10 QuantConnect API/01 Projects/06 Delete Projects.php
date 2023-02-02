<p>To delete a project, call the <code>DeleteProject</code> method with the project ID.</p>
<div class="section-example-container">
    <pre class="csharp">var response = api.DeleteProject(projectId);</pre>
    <pre class="python">response = api.DeleteProject(project_id)</pre>
</div>

<?php include(DOCS_RESOURCES."/qc-api/get-project-id-in-research.html"); ?>

<p>The <code>DeleteProject</code> method returns a <code>RestResponse</code> object, which have the following attributes:</p>
<div data-tree='QuantConnect.Api.RestResponse'></div>