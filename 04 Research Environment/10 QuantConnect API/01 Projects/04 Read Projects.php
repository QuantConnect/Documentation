<p>To get the details of a project, call the <code>ReadProject</code> with the project ID.</p>

<div class="section-example-container">
    <pre class="csharp">var response = api.ReadProject(projectId);</pre>
    <pre class="python">response = api.ReadProject(project_id)</pre>
</div>

<?php include(DOCS_RESOURCES."/qc-api/get-project-id-in-research.html"); ?>

<p>The <code>ReadProject</code> method returns a <code>ProjectResponse</code> object, which have the following attributes:</p>

<div data-tree="QuantConnect.Api.ProjectResponse"></div>

<p>Note that the project response is a snapshot of the project at the current moment in time.</p>