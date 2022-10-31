<p>
    After installing the CLI, open a terminal in an empty directory and run <code>lean init</code> to create your CLI root directory.
    This command scaffolds a standard directory structure containing a data directory and a <a href="/docs/v2/lean-cli/initialization/configuration#03-Lean-Configuration">Lean configuration file</a>, both of which are required to run the LEAN engine locally.
    We recommend running all Lean CLI commands in your CLI root directory.
</p>

<?php 
include(DOCS_RESOURCES."/cli/init/wsl.php");
$isCLIDocs = true;
$getWSLText($isCLIDocs);
?>

<p>
    <code>lean init</code> creates the following structure:
</p>

<?php
include(DOCS_RESOURCES."/cli/init/structure.php"); 
$isCLIDocs = true;
$getStructureText($isCLIDocs);
?>

<p>
    We recommend running all Lean CLI commands in your CLI root directory.
    Doing so ensures the directory structure is always kept consistent when synchronizing projects between the cloud and your local drive.
    It also makes it possible for the CLI to automatically find the Lean configuration file when running the LEAN engine locally.
</p>

<p>To set the default language of new projects, run <code>lean init --language &lt;value&gt;</code> where the <code>&lt;value&gt;</code> is <code>python</code> or <code>csharp</code>.</p>
