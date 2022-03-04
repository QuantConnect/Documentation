<p>You need the following to optimize parameters:</p>

<ul>
    <li>At least one algorithm parameter in your project.</li>
    <li>The <code>GetParameter</code> method or <code>Parameter</code> attribute in your project.</li>
    <li>A successful backtest of the project.</li>
    <li><a href='/docs/v2/our-platform/user-guides/organizations/credit'>QuantConnect Credit (QCC)</a> in your organization.</li>
</ul>

<p>Follow these steps to optimize parameters:</p>

<ol>
    <li><a href="/docs/v2/our-platform/tutorials/projects/managing-projects#05-Open-Existing-Projects">Open the project</a> that contains the parameters you want to optimize.</li>
    <li>In the top-right corner of the IDE, click <span class='button-name'>Optimize</span>.</li>
    <li>In the <span class='page-section-name'>Choose Optimization Strategy</span> section of the Optimization Wizard, click the <span class='field-name'>optimization strategy</span> field and then select a strategy from the drop-down menu.</li>
    <li>In the <span class='page-section-name'>Select Target</span> section, click the <span class='field-name'>optimization target</span> field and then select a target from the drop-down menu.</li>
    <li>Click <span class='box-name'>Maximize</span> or <span class='box-name'>Minimize</span> to maximize or minimize the optimization target, respectively.</li>
    <li>Click <span class='button-name'>Next</span>.</li>
    <li>In the <span class='page-section-name'>Parameters</span> section, enter the name of the parameter to optimize.</li>
    <p>The parameter name must match a parameter name in the project panel.</p>
    <li>Enter the minimum and maximum parameter values.</li>
    <li>Click the <span class='icon-name'>gear</span> icon next to the parameter and then enter a step size.</li>
    <li>If you want to add a second parameter to optimize, click <span class='button-name'>Add Parameter</span> and then continue from step 7.</li>
    <p>You can optimize a maximum of two parameters.</p>
    <li>If you want to add optimization constraints, follow these steps:</li>
    <ol>
    	<li>In the <span class='page-section-name'>Constraint</span> section, click <span class='button-name'>Add Constraint</span>.</li>
    	<li>Click the <span class='field-name'>target</span> field and then select a target from the drop-down menu.</li>
    	<li>Click the <span class='field-name'>operation</span> field and then an operation from the drop-down menu.</li>
    	<li>Enter a constraint value.</li>
    	<li>If you want to add additional constraints, continue from step 11.1.</li>
    </ol>
    <li>Click <span class='button-name'>Next</span>.</li>
    <p>"Parameters saved" displays.</p>
    <li>In the <span class='page-section-name'>Type and Number of Compute Nodes</span> section, select one of the following <a href='/docs/v2/our-platform/user-guides/optimization/deployment#02-Resources'>optimization node models</a>.</li>

    <p>The following table explains when to use each model:</p>

    <?php include(DOCS_RESOURCES."/optimization-node-descriptions-table.php"); ?>

    <li>Select a maximum number of nodes to use.</li>
    <li>Click <span class='button-name'>Launch Optimization</span>.</li>
    <p>The optimization results page displays.</p>
    <img class='img-responsive' src="https://cdn.quantconnect.com/i/tu/view-optimization-results.png">
    <p>As the optimization job runs, you can close or refresh the window without interrupting the job because the nodes are processing on our servers.</p>
    <p>To abort a running optimization job, in the Status panel, click <span class='button-name'>Abort</span> and then click <span class='button-name'>Abort Optimization</span>.</p>
</ol>
