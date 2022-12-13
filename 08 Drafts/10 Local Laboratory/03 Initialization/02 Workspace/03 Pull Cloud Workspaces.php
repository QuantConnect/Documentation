<p>Follow these steps to set the workspace directory:</p>
<ol>
    <li><a href="/docs/v2/drafts/local-laboratory/initialization/account#02-Log-In">Log in to the Local Lab</a>.</li>
    <li>In the left navigation menu, click the <img class="inline-icon" src="https://cdn.quantconnect.com/i/tu/vscode-qc-icon.jpg"> <span class="icon-name">QuantConnect</span> icon.</li>
    <li>In the Select Workspace panel, click <span class="button-name">Pull Organization Workspace</span>.</li>
    <img class="docs-image" src="https://cdn.quantconnect.com/i/tu/select-workspace.png">
    <li>In the Pull QuantConnect Organization Workspace window, click the cloud workspace (<a href='/docs/v2/our-platform/organizations'>organization</a>) that you want to pull.</li>
    <img class="docs-image" src="https://cdn.quantconnect.com/i/tu/pull-cloud-workspaces.png">
    
    <li>In the Pull QuantConnect Organization Workspace window, create a directory to serve as the workspace and then click <span class="button-name">Select</span>.</li>
    <img class="docs-image" src="https://cdn.quantconnect.com/i/tu/create-workspace-folder.png">
</ol>

<p>It takes a few minutes to create a new workspace directory and populate it with the <a href='/docs/v2/drafts/local-laboratory/initialization/workspace#02-Structure'>the initial file structure</a>. After the workspace directory is populated with the initial file structure, it pulls your cloud project files.</p>

<?php echo file_get_contents(DOCS_RESOURCES."/cli/init/wsl.php"); ?>
