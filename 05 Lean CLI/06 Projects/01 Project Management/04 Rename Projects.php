<p>Follow these steps to rename a project that you have on your local machine and in QuantConnect Cloud:</p>

<ol>
    <li>Open the <a href='/docs/v2/lean-cli/initialization/organization-workspaces'>organization workspaces</a> on your local machine where you store the project.</li>
    <li>Rename the project file.</li>
    <?php echo "<p>" . file_get_contents(DOCS_RESOURCES."/cli/project-name-rules.html") . "</p>"; ?>
    <li><a href="/docs/v2/lean-cli/initialization/authentication#02-Log-In">Log in</a> to the CLI if you haven't done so already.</li>
    <li>Open a terminal in the same organization workspace.</li>
    <li>Run <code>lean cloud push --project "&lt;projectName&gt;"</code>.</li>
</ol>

<div class="cli section-example-container">
<pre>$ lean cloud push --project "My Renamed Project"
[1/1] Pushing "My Renamed Project"
Renaming project in the cloud from 'My Project' to 'My Renamed Project'
Successfully updated name and files and libraries for 'My Project'</pre>
</div>

<p>Alternatively, you can <a href='/docs/v2/cloud-platform/projects/getting-started#07-Rename-Projects'>rename the project</a> in QuantConnect Cloud and then <a href='/docs/v2/lean-cli/projects/cloud-synchronization#02-Pulling-Cloud-Projects'>pull the project</a> to your local machine.</p>
