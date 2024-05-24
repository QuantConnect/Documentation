<p>The following sections explain how to operate the Research Environment with VS Code.</p>

<h4>Open Notebooks</h4>
<p>Follow these steps to open a research notebook in VS Code:</p>
<ol>
    <li><a href="/docs/v2/lean-cli/research#02-Running-Local-Research-Environment">Start a local research environment</a> for the project that contains the notebook.</li>
    <li>Open the same project VS Code.</li>
    <li>In the Explore panel, click the notebook file you want to open.</li>
	<p>The default notebook is <span class='public-file-name'>research.ipynb</span>.</p>
    <li>In the top-right corner of the notebook, click <span class="button-name">Select Kernel</span>.</li>
    <li>In the Select Another Kernel window, click <span class="button-name">Existing Jypter Server...</span>.</li>
    <li>Enter <span class="key-combinations">http://localhost:8888/</span> into the field.</li>
    <li>Press <span class="key-combinations">Enter</span>.</li>
    <li><span class='qualifier'>(Optional)</span> Enter a display name for the server and then press <span class="key-combinations">Enter</span>.</li>
    <li>Click <span class="python button-name">Foundation-Py-Default</span><span class="csharp button-name">Foundation-C#-Default</span>.</li>
</ol>

<h4>Run Notebook Cells</h4>
<? include(DOCS_RESOURCES."/getting-started/research-environment/run-notebook-cells.html"); ?>
<p>For more information about keyboard shortcuts in VS Code, see <a rel='nofollow' target='_blank' href='https://code.visualstudio.com/docs/getstarted/keybindings'>Key Bindings for Visual Studio Code</a> in the VS Code documentation.</p>

<h4>Add Notebooks</h4>
<p>Follow these steps to add notebook files to a project in VS Code:</p>

<ol>
	<li><a href='/docs/v2/local-platform/projects/getting-started#04-Open-Projects'>Open the project</a>.</li>
	<li>In the right navigation menu, click the <img class="inline-icon" src="https://cdn.quantconnect.com/i/tu/local-lab-explorer-icon.jpg" alt="Local lab explorer icon"> <span class="icon-name">Explorer</span> icon.</li>
    <li>In the Explorer panel, expand the <span class="button-name">QC (Workspace)</span> section.</li>
    <li>Click the <img class="inline-icon" src="https://cdn.quantconnect.com/i/tu/new-file-icon.png" alt="Add new file icon"> <span class="icon-name">New File</span> icon.</li>
    <li>Enter <span class="public-file-name"><span class="placeholder-text">fileName</span>.ipynb</span>.</li>
    <li>Press <span class="key-combinations">Enter</span>.</li>
</ol>

<h4>Rename Notebooks</h4>
<p>Follow these steps to rename notebook files in VS Code:</p>
<ol>
    <li><a href="/docs/v2/lean-cli/research#02-Running-Local-Research-Environment">Start a local research environment</a> for the project that contains the notebook.</li>
    <li>In the right navigation menu, click the <img class="inline-icon" src="https://cdn.quantconnect.com/i/tu/local-lab-explorer-icon.jpg" alt="Local lab explorer icon"> <span class="icon-name">Explorer</span> icon.</li>
    <li>In the Explorer panel, expand the <span class="button-name">QC (Workspace)</span> section.</li>
    <li>Click the <img class="inline-icon" src="https://cdn.quantconnect.com/i/tu/new-file-icon.png" alt="Add new file icon"> <span class="icon-name">New File</span> icon.</li>
    <li>Enter <span class="public-file-name"><span class="placeholder-text">fileName</span>.ipynb</span>.</li>
    <li>Press <span class="key-combinations">Enter</span>.</li>
</ol>

<h4>Delete Notebooks</h4>
<p>Follow these steps to delete notebook files in VS Code:</p>
<ol>
    <li><a href="/docs/v2/lean-cli/research#02-Running-Local-Research-Environment">Start a local research environment</a> for the project that contains the notebook.</li>
    <li>In the right navigation menu, click the <img class="inline-icon" src="https://cdn.quantconnect.com/i/tu/local-lab-explorer-icon.jpg" alt="Local lab explorer icon"> <span class="icon-name">Explorer</span> icon.</li>
    <li>In the Explorer panel, right-click the notebook you want to rename and then click <span class='menu-name'>Rename</span>.</li>
    <li>Enter the new name and then press <span class='button-name'>Enter</span>.</li>
</ol>