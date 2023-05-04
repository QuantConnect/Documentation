<p>Algorithm parameters are hard-coded values for variables in your project that are set outside of the code files. Add parameters to your projects to remove hard-coded values from your code files and to perform parameter optimizations. You can add parameters, set default parameter values, and remove parameters from your projects.</p>

<h4>Add Parameters</h4>
<p>Follow these steps to add an algorithm parameter to a project:</p>
<ol>
    <li><a href='<?=$openProjectLink?>'>Open the project</a>.</li>
    <?=$localPlatform ? "<li>In the left navigation menu, click the <img class='inline-icon' src='https://cdn.quantconnect.com/i/tu/vscode-qc-icon.jpg'> <span class='icon-name'>QuantConnect</span> icon.</li>" : "" ?>
    <li>In the Project panel, click <span class='button-name'>Add New Parameter</span>.</li>
    <li>Enter the parameter name.</li>
    <p>The parameter name must be unique in the project.</p>
    <li>Enter the default value.</li>
    <li>Click <span class='button-name'>Create Parameter</span>.</li>
</ol>

<p>To get the parameter values into your algorithm, see <a href='/docs/v2/writing-algorithms/parameters#03-Get-Parameters'>Get Parameters</a>.</p>

<h4>Set Default Parameter Values</h4>
<p>Follow these steps to set the default value of an algorithm parameter in a project:</p>
<ol>
    <li><a href='<?=$openProjectLink ?>'>Open the project</a>.</li>
    <?=$localPlatform ? "<li>In the left navigation menu, click the <img class='inline-icon' src='https://cdn.quantconnect.com/i/tu/vscode-qc-icon.jpg'> <span class='icon-name'>QuantConnect</span> icon.</li>" : "" ?>
    <li>In the Project panel, hover over the algorithm parameter and then click the <span class='icon-name'>pencil</span> icon that appears.</li>
    <img class='docs-image' src='https://cdn.quantconnect.com/i/tu/set-default-value.png' alt="Edit default parameter">
    <li>Enter a default value for the parameter and then click <span class='button-name'>Save</span>.</li>
    <p>The Project panel displays the default parameter value next to the parameter name.</p>
</ol>

<h4>Delete Parameters</h4>
<p>Follow these steps to delete an algorithm parameter in a project:</p>
<ol>
    <li><a href='<?=$openProjectLink ?>'>Open the project</a>.</li>
    <?=$localPlatform ? "<li>In the left navigation menu, click the <img class='inline-icon' src='https://cdn.quantconnect.com/i/tu/vscode-qc-icon.jpg'> <span class='icon-name'>QuantConnect</span> icon.</li>" : "" ?>
    <li>In the Project panel, hover over the algorithm parameter and then click the <span class='icon-name'>trash can</span> icon that appears.</li>
    <img class='docs-image' src='https://cdn.quantconnect.com/i/tu/delete-parameters.png' alt="Delete a parameter">
    <li>Remove the <code>GetParameter</code> calls that were associated with the parameter from your code files.</li>
</ol>
