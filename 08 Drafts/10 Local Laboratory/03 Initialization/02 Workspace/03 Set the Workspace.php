<p>Follow these steps to set the workspace directory:</p>
<ol>
    <li><a href="/docs/v2/drafts/local-laboratory/initialization#02-Log-In">Log in to the Local Lab</a>.</li>
    <li>In the Project panel, click <span class="button-name">Create LEAN Workspace</span> or <span class="button-name">Select LEAN Workspace</span>.</li>
    <img class="docs-image" src="https://cdn.quantconnect.com/i/tu/local-lab-workspace-menu.jpg">
    <p>You can create a new workspace directory or, if you use the LEAN CLI, you can set it to the <a href="/docs/v2/lean-cli/initialization/workspace">workspace that lean init creates</a>.</p>
    <li>If you clicked <span class="button-name">Create LEAN Workspace</span>, in the Create Lean CLI Workspace window, create a directory to serve as the workspace and then click <span class="button-name">Select</span>.</li>
    <img class="docs-image" src="https://cdn.quantconnect.com/i/tu/local-lab-create-new-workspace.jpg">
</ol>

<p>It takes a few minutes to create a new workspace directory and populate it with the <a href='/docs/v2/drafts/local-laboratory/initialization/workspace#02-Structure'>the initial file structure</a>. To view the progress, see the purple bar at the bottom of Visual Studio Code.</p>
<img class="docs-image" src="https://cdn.quantconnect.com/i/tu/local-lab-workspace-progress.jpg">

<?php echo file_get_contents(DOCS_RESOURCES."/cli/init/wsl.php"); ?>