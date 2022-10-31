<p>The Local Lab workspace is a directory that contains a <span class="private-file-name">data</span> directory, a Lean configuration file, and all your project files. You need the <span class="private-file-name">data</span> directory and a Lean configuration file to run the LEAN engine on your local machine.</p>

<p>Follow these steps to set the initial workspace directory:</p>
<ol>
    <li><a href="/docs/v2/drafts/local-laboratory/initialization#02-Log-In">Log in to the Local Lab</a>.</li>
    <li>In the Project panel, click <span class="button-name">Create LEAN Workspace</span> or <span class="button-name">Select LEAN Workspace</span>.</li>
    <img class="docs-image" src="https://cdn.quantconnect.com/i/tu/local-lab-workspace-menu.jpg">
    <p>You can create a new workspace directory or, if you use the LEAN CLI, you can set it to <a href="/docs/v2/lean-cli/initialization/directory-structure#02-lean-init">the directory that lean init creates</a>.</p>
    <li>If you clicked <span class="button-name">Create LEAN Workspace</span>, in the Create Lean CLI Workspace window, create a directory to serve as the workspace and then click <span class="button-name">Select</span>.</li>
     <img class="docs-image" src="https://cdn.quantconnect.com/i/tu/local-lab-create-new-workspace.jpg">
</ol>

<p>It takes a few minutes to create a new workspace directory. To view the progress, see the purple bar at the bottom of Visual Studio Code.</p>
<img class="docs-image" src="https://cdn.quantconnect.com/i/tu/local-lab-workspace-progress.jpg">

<?php 
include(DOCS_RESOURCES."/cli/init/wsl.php");
$isCLIDocs = true;
$getWSLText($isCLIDocs);
?>

<p>
    If you create a new workspace, the workspace directory has following structure:
</p>

<?php
include(DOCS_RESOURCES."/cli/init/structure.php"); 
$isCLIDocs = false;
$getStructureText($isCLIDocs);
?>
