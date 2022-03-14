<p>You can add parameters, set default parameter values, and remove parameters from your projects.</p>

<h4>Add Parameters</h4>
<p>Follow these steps to add an algorithm parameter:</p>
<ol>
    <li><a href="/docs/v2/our-platform/tutorials/projects/managing-projects#05-Open-Existing-Projects">Open the project</a> to which you want to add a parameter.</li>
    <li>In the Project panel, click <span class='button-name'>Add New Parameter</span>.</li>
    <li>Enter the parameter name and then click <span class='button-name'>Create Parameter</span>.</li>
    <p>The parameter name must be unique in the project.</p>
    <li>Add the <code>GetParameter</code> method to your code files to inject the parameter value into your algorithm.</li>
</ol>


<h4>Set Default Parameter Values</h4>
<p>Follow these steps to set default values for algorithm parameters:</p>
<ol>
    <li><a href="/docs/v2/our-platform/tutorials/projects/managing-projects#05-Open-Existing-Projects">Open the project</a> that contains the parameter for which you want to set a default value.</li>
    <li>In the Project panel, hover over the algorithm parameter and then click the pencil icon that appears.</li>
    <img class='docs-image' src="https://cdn.quantconnect.com/i/tu/set-default-value.png">
    <li>Enter a default value for the parameter and then click <span class='button-name'>Save</span></li>
    <p>The Project panel displays the default parameter value next to the parameter name.</p>
</ol>


<h4>Delete Parameters</h4>
<p>Follow these steps to delete algorithm parameters:</p>
<ol>
    <li><a href="/docs/v2/our-platform/tutorials/projects/managing-projects#05-Open-Existing-Projects">Open the project</a> that contains the parameter you want to delete.</li>
    <li>In the Project panel, hover over the algorithm parameter and then click the trash can icon that appears.</li>
    <img class='docs-image' src="https://cdn.quantconnect.com/i/tu/delete-parameters.png">
    <li>Remove the <code>GetParameter</code> calls that were associated with the parameter from your code files.</li>
</ol>
