<p>To get details on all the files in a project, call the <code>ReadProjectFiles</code> method with the project ID.</p>

<div class="section-example-container">
    <pre class="csharp">var response = api.ReadProjectFiles(projectId);</pre>
    <pre class="python">response = api.ReadProjectFiles(project_id)</pre>
</div>

<?php include(DOCS_RESOURCES."/qc-api/get-project-id-in-research.html"); ?>

<p>To get details on a specific file in a project, call the <code>ReadProjectFile</code> method with the project Id and file name.</p>

<div class="section-example-container">
    <pre class="csharp">var response = api.ReadProjectFile(projectId, fileName);</pre>
    <pre class="python">response = api.ReadProjectFile(project_id, file_name)</pre>
</div>

<p>The <code>ReadProjectFiles</code> and <code>ReadProjectFile</code> methods return a <code>ProjectFilesResponse</code> object, which have the following attributes:</p>
<div data-tree="QuantConnect.Api.ProjectFilesResponse"></div>