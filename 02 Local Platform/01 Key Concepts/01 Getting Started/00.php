<p>The Local Platform enables you to seamlessly develop quant strategies on-premise and in QuantConnect Cloud, getting the best of both environments. With Local Platform, you can harness your local version control, autocomplete, and coding tools with the full power of a scalable cloud at your finger tips. We intend to keep complete feature parity with our cloud environment, allowing you to harness cloud or local datasets to power on-premise quantitative research.</p>

<p>We encourage a hybrid "cloud + local" workflow, so you can use right tool for each stage of your development process. With the Local Platform, you can create, debug, and run projects on premise while using your own on-site tools. With the Cloud Platform you can deploy backtests at scale and harness our massive data library at low cost.</p>

<p>Follow these steps to create a new trading algorithm and backtest it in QuantConnect Cloud:</p>

<ol>
    <li><a href='/docs/v2/local-platform/installation'>Install Local Platform</a>.</li>
    <li>Open Visual Studio Code.</li>
    <li>In the Initialization Checklist panel, click <span class="button-name">Login to QuantConnect</span>.</li>
    <img class='docs-image' src='https://cdn.quantconnect.com/i/tu/initialization-checklist-1.png' alt="Initialization checklist panel">

    <li>In the Visual Studio Code window, click <span class="button-name">Open</span>.</li>
    <img class='docs-image' src='https://cdn.quantconnect.com/i/tu/local-lab-log-in-pop-up.jpg' alt="VS Code login popup">

    <li>On the Code Extension Login page, click <span class="button-name">Grant Access</span>.</li>

    <li>In VS Code, in the Select Workspace panel, click <span class="button-name">Pull Organization Workspace</span>.</li>
    <img class="docs-image" src="https://cdn.quantconnect.com/i/tu/select-workspace.png" alt="Select workspace">
    <li>In the Pull QuantConnect Organization Workspace window, click the cloud workspace (<a href='https://www.quantconnect.com/docs/v2/cloud-platform/organizations'>organization</a>) that you want to pull.</li>
    <img class="docs-image" src="https://cdn.quantconnect.com/i/tu/pull-cloud-organization.png" alt="Pull cloud organization">
    
    <li>In the Pull QuantConnect Organization Workspace window, create a directory to serve as the workspace and then click <span class="button-name">Select</span>.</li>
    <img class="docs-image" src="https://cdn.quantconnect.com/i/tu/pull-cloud-workspaces.png" alt="Pull cloud workspace"></li>

    <? include(DOCS_RESOURCES."/cli/init/wsl.html"); ?>

    <li>In the Open Project panel, click <span class="button-name">Create Project</span>.</li>
    <img class="docs-image" src="https://cdn.quantconnect.com/i/tu/open-new-project.png" alt="Open a new project">

    <li>Enter the project name and then press <span class="key-combinations">Enter</span>.</li>
    <img class="docs-image" src="https://cdn.quantconnect.com/i/tu/name-project-window.png" alt="Name a new project">

    <p>Congratulations! You just created your first local project.</p>
    <img class="python docs-image" src="https://cdn.quantconnect.com/i/tu/local-lab-new-project-py.png" alt="Python local lab interface">
    <img class="csharp docs-image" src="https://cdn.quantconnect.com/i/tu/local-platform-new-project-c.png" alt="C# local lab interface">

    <li>In the top-right corner of VS Code, click <img class='inline-icon' src="https://cdn.quantconnect.com/i/tu/build-button.png"> <span class="button-name">Build</span> and then click <img class='inline-icon' src='https://cdn.quantconnect.com/i/tu/backtest-button.png'> <span class='icon-name'>Backtest</span>.</li> 

    <p>The backtest results page displays your algorithm's performance over the backtest period.</p>
    <img class='docs-image' src='https://cdn.quantconnect.com/i/tu/backtest-results-page-local-platform.png' alt="Local backtest result interface">
</ol>
