<p>The Local Lab workspace is a directory that contains a <span class="private-file-name">data</span> directory, a Lean configuration file, and all your project files. You need the <span class="private-file-name">data</span> directory and a Lean configuration file to run the LEAN engine on your local machine.</p>

<h4>Structure</h4>

<p>
    The workspace directory has following structure:
</p>

<?php
include(DOCS_RESOURCES."/cli/init/structure.php"); 
$isCLIDocs = false;
$getStructureText($isCLIDocs);
?>

<h4>Set the Workspace</h4>

<p>Follow these steps to set the workspace directory:</p>
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
$isCLIDocs = false;
$getWSLText($isCLIDocs);
?>

<h4>Change the Workspace</h4>
<p>Follow these steps to change the workspace directory:</p>

<ol>
    <li><a href="/docs/v2/drafts/local-laboratory/initialization#02-Log-In">Log in to the Local Lab</a>.</li>
    <li>In the left navigation bar, click <img class="inline-icon" src="https://cdn.quantconnect.com/i/tu/local-lab-projects-tab.jpg"> <span class="icon-name">Project</span>.</li>
    <li>If a project is already open, in the Project panel, click <span class='button-name'>Close</span>.</li>
    <li>Click <span class='button-name'>Change Here</span>.</li>
    <img class='docs-image' src='https://cdn.quantconnect.com/i/tu/local-labe-starting-project-panel.jpg'>
    <li>In the Update Lean CLI Workspace window, click the new workspace directory and then click <span class='button-name'>Select</span>.</li>
    <img class="docs-image" src="https://cdn.quantconnect.com/i/tu/local-lab-create-new-workspace.jpg">
    <p>The new workspace directory you chose must have the correct structure.</p>
</ol>


<h4>Remove the Workspace</h4>

<p>Follow these steps to remove the existing workspace directory:</p>

<ol>
    <li><a href="/docs/v2/drafts/local-laboratory/initialization#02-Log-In">Log in to the Local Lab</a>.</li>
    <li>In Visual Studio Code, click <span class='menu-name'>File &gt; Preferences &gt; Settings</span>.</li>
    <li>On the Settings page, click <span class='menu-name'>Extensions &gt; QuantConnect</span>.</li>
    <li>In the <span class='field-name'>QC &gt; Lean: Init</span> field, remove the workspace path.</li>
    <li>Restart Visual Studio Code.</li>
</ol>
<p>When you open Visual Studio Code again, it will prompt you to set the workspace.</p>
