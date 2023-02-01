<p>To update a file name, call the <code>UpdateProjectFileName</code> method with the project ID, the old file name, and the new file name.</p>
<div class="section-example-container">
    <pre class="csharp">var response = api.UpdateProjectFileName(projectId, oldFileName, newFileName);</pre>
    <pre class="python">response = api.UpdateProjectFileName(project_id, old_file_name, new_file_name)</pre>
</div>

<?php include(DOCS_RESOURCES."/qc-api/get-project-id-in-research.html"); ?>

<p>To update the content of a file, call the <code>UpdateProjectFileContent</code> method with the project ID, the file name, and the new content.</p>
<div class="section-example-container">
    <pre class="csharp">var response = api.UpdateProjectFileContent(projectId, fileName, newContent);</pre>
    <pre class="python">response = api.UpdateProjectFileContent(project_id, file_name, new_content)</pre>
</div>

<p>The <code>UpdateProjectFileName</code> and <code>UpdateProjectFileContent</code> methods return a <code>RestResponse</code> object, which have the following attributes:</p>

<div data-tree='QuantConnect.Api.RestResponse'></div>

