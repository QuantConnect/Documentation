<p>To automate live deployments of a number of projects, save the projects under the same directory first. In this tutorial, the projects are saved under the directory <code>/Live</code>.</p>

<p>Follow the below steps to get the project Ids of all projects under the <code>/Live</code> directory.</p>
<ol>
<?
$additionalImports = "";
include(DOCS_RESOURCES."/datasets/research-environment/load-csharp-assemblies.php");
?>

    <li>Call the API to get a list of all project responses.</li>
    <div class="section-example-container">
        <pre class="python">list_project_response = api.ListProjects()</pre>
        <pre class="csharp">var listProjectResponse = api.ListProjects();</pre>
    </div>

    <li>Obtain the project Ids for the projects in <code>/Live</code> directory.</li>
    <div class="section-example-container">
        <pre class="python">project_ids = [project.ProjectId for project in list_project_response.Projects
            if project.Name.split("/")[0] == "Live"]</pre>
        <pre class="csharp">var projectIds = listProjectResponse.Projects
            .Where(project =&gt; project.Name.Split('/').First() == "Live")
            .Select(project =&gt; project.ProjectId)
            .ToList();</pre>
    </div>
</ol>