<p>The following sections explain how to operate the Research Environment with <a rel='nofollow' target='_blank' href='https://www.jetbrains.com/pycharm/'>PyCharm</a>.</p>

<h4>Open Notebooks</h4>
<p>Follow these steps to open a research notebook in PyCharm:</p>

<ol>
    <li><a href="/docs/v2/lean-cli/research#02-Start-the-Research-Environment">Start a local research environment</a> for the project that contains the notebook.</li>
    <li>Open the same project in PyCharm.</li>
    <li>In the top navigation menu of PyCharm, click <span class='button-name'>File > Settings</span>.</li>
    <li>In the Settings window, click <span class='button-name'>Language & Frameworks > Jupyter > Jupyter Servers</span>.</li>
    <li>Click <span class='button-name'>Configured Server</span>.</li>
    <li>Enter <span class='key-combinations'>http://localhost:8888/?token=</span> into the field.</li>
    <li>Click <span class='button-name'>Apply</span> to save the changes.</li>
    <li>Click <span class='button-name'>OK</span> to exit the window.</li>
    <li>In the left navigation menu, click the <img class='inline-icon' src='https://cdn.quantconnect.com/i/tu/pycharm-project-icon.png' alt='PyCharm Project icon'> <span class='icon-name'>Project</span> icon.</li>
    <li>In the Project panel, double-click the notebook file you want to open.</li>
	<p>The default notebook is <span class='public-file-name'>research.ipynb</span>.</p>
</ol>

<h4>Run Notebook Cells</h4>
<? include(DOCS_RESOURCES."/getting-started/research-environment/run-notebook-cells.html"); ?>
<p>For more information about keyboard shortcuts in PyCharm, see <a rel='nofollow' target='_blank' href='https://www.jetbrains.com/help/pycharm/mastering-keyboard-shortcuts.html'>Keyboard Shortcuts</a> in the PyCharm documentation.</p>

<h4>Stop Nodes</h4>
<p>Follow these steps to stop a research node in PyCharm:</p>
<ol>
    <li>In the left navigation menu, click the <img class='inline-icon' src='https://cdn.quantconnect.com/i/tu/pycharm-project-icon.png' alt='PyCharm Project icon'> <span class='icon-name'>Project</span> icon.</li>
    <li>In the Project panel, right-click the name of the notebook file and then click <span class='button-name'>Shutdown Kernel</span> from the drop-down menu.</li>
</ol>
<p>For more information about the Jupyter notebook servers in PyCharm, see <a rel='nofollow' target='_blank' href='https://www.jetbrains.com/help/pycharm/configuring-jupyter-notebook.html'>Manage Jupyter notebook servers</a> in the PyCharm documentation.</p>

<h4>Add Notebooks</h4>
<p>To add notebook files to a project in PyCharm, see <a rel='nofollow' target='_blank' href='https://www.jetbrains.com/help/pycharm/editing-jupyter-notebook-files.html#create-notebook-file'>Create a notebook file</a> in the PyCharm documentation.</p>

<h4>Rename Notebooks</h4>
<p>Follow these steps to rename notebook files in PyCharm:</p>
<ol>
    <li><a href="/docs/v2/lean-cli/research#02-Start-the-Research-Environment">Start a local research environment</a> for the project in PyCharm.</li>
    <li>In the left navigation menu, click the <img class='inline-icon' src='https://cdn.quantconnect.com/i/tu/pycharm-project-icon.png' alt='PyCharm Project icon'> <span class='icon-name'>Project</span> icon.</li> 
    <li>In the Project panel, right-click the notebook file you want to rename and then click <span class='button-name'>Refactor > Rename</span> from the drop-down menu.</li>
    <li>Enter the new name.</li>
    <li>Press <span class='key-combinations'>Enter</span>.</li>
</ol>

<h4>Delete Notebooks</h4>
<p>Follow these steps to delete notebook files in PyCharm:</p>
<ol>
    <li><a href="/docs/v2/lean-cli/research#02-Start-the-Research-Environment">Start a local research environment</a> for the project in PyCharm.</li>
    <li>In the left navigation menu, click the <img class='inline-icon' src='https://cdn.quantconnect.com/i/tu/pycharm-project-icon.png' alt='PyCharm Project icon'> <span class='icon-name'>Project</span> icon.</li> 
    <li>In the Project panel, right-click the notebook file you want to rename and then click <span class='button-name'>Delete</span> from the drop-down menu.</li>
    <li>In the Delete window, click <span class='button-name'>OK</span>.</li>
</ol>

