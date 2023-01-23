<p>Follow these steps to create a new trading algorithm and backtest it in QC Cloud:</p>

<ol>
    <li>Log in to the Algorithm Lab.</li>
    <li>Start <a href='/docs/v2/lean-cli/installation/installing-docker'>Docker Desktop</a>.</li>
    <li>Open Visual Studio Code.</li>
    <li>In the Initialization Checklist panel, click <span class="button-name">Login to QuantConnect</span>.</li>
    <img class='docs-image' src='https://cdn.quantconnect.com/i/tu/initialization-checklist-1.png'>

    <li>In the Visual Studio Code window, click <span class="button-name">Open</span>.</li>
    <img class='docs-image' src='https://cdn.quantconnect.com/i/tu/local-lab-log-in-pop-up.jpg'>

    <li>On the Code Extension Login page, click <span class="button-name">Grant Access</span>.</li>

    <li>In VS Code, in the Select Workspace panel, click <span class="button-name">Pull Organization Workspace</span>.</li>
    <img class="docs-image" src="https://cdn.quantconnect.com/i/tu/select-workspace.png">
    <li>In the Pull QuantConnect Organization Workspace window, click the cloud workspace (<a href='https://www.quantconnect.com/docs/v2/cloud-platform/organizations'>organization</a>) that you want to pull.</li>
    <img class="docs-image" src="https://cdn.quantconnect.com/i/tu/pull-cloud-organization.png">
    
    <li>In the Pull QuantConnect Organization Workspace window, create a directory to serve as the workspace and then click <span class="button-name">Select</span>.</li>
    <img class="docs-image" src="https://cdn.quantconnect.com/i/tu/pull-cloud-workspaces.png"></li>

    <?php echo file_get_contents(DOCS_RESOURCES."/cli/init/wsl.php"); ?>

    <li>In the Open Project panel, click <span class="button-name">Create Project</span>.</li>
    <img class="docs-image" src="https://cdn.quantconnect.com/i/tu/open-new-project.png">

    <li>Enter the project name and then press <span class="key-combinations">Enter</span>.</li>
    <img class="docs-image" src="https://cdn.quantconnect.com/i/tu/name-project-window.png">

    <p>The new project displays.</p>
    <img class="python docs-image" src="https://cdn.quantconnect.com/i/tu/local-lab-new-project-py.png">
    <img class="csharp docs-image" src="https://cdn.quantconnect.com/i/tu/local-platform-new-project-c.png">

    <li>In the top-right corner of VS Code, click <img class='inline-icon' src="https://cdn.quantconnect.com/i/tu/build-button.png"> <span class="button-name">Build</span> and then click <img class='inline-icon' src='https://cdn.quantconnect.com/i/tu/backtest-button.png'> <span class='icon-name'>Backtest</span>.</li> 

    <p>The backtest results page displays your algorithm's performance over the backtest period.</p>
    <img class='docs-image' src='https://cdn.quantconnect.com/i/tu/backtest-results-page-local-platform.png'>
</ol>
