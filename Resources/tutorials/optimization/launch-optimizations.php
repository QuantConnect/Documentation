<p>You need the following to optimize parameters:</p>

<ul>
    <li>At least one algorithm parameter in your project.</li>
    <li>The <code>GetParameter</code> method or <code>Parameter</code> attribute in your project.</li>
    <li>A successful backtest of the project.</li>
    <li><a href='/docs/v2/our-platform/user-guides/organizations/credit'>QuantConnect Credit (QCC)</a> in your organization.</li>
</ul>

<p>Follow these steps to optimize parameters:</p>

<ol>
    <li><a href="/docs/v2/our-platform/projects/project-management#02-View-All-Projects">Open the project</a> that contains the parameters you want to optimize.</li>
    <li>In the top-right corner of the IDE, click the <img class='inline-icon' src='https://cdn.quantconnect.com/i/tu/optimize-parameters-icon.png'><span class='icon-name'>Optimize</span> icon.</li>
    
    <li>On the Optimization page, in the <span class='page-section-name'>Parameter & Constraints</span> section, enter the name of the parameter to optimize.</li>
    <p>The parameter name must match a parameter name in the Project panel.</p>
    <li>Enter the minimum and maximum parameter values.</li>
    <li>Click the <span class='icon-name'>gear</span> icon next to the parameter and then enter a step size.</li>
    <li>If you want to add a second parameter to optimize, click <span class='button-name'>Add Parameter</span>.</li>
    <p>You can optimize a maximum of two parameters. To optimize more parameters, see <a href='/docs/v2/lean-cli/tutorials/optimization/local-optimizations'>Local Optimizations</a>.</p>
    
    <li>If you want to add optimization constraints, follow these steps:</li>
    <ol>
    	<li>Click <span class='button-name'>Add Constraint</span>.</li>
    	<li>Click the <span class='field-name'>target</span> field and then select a target from the drop-down menu.</li>
    	<li>Click the <span class='field-name'>operation</span> field and then an operation from the drop-down menu.</li>
    	<li>Enter a constraint value.</li>
    </ol>
    
    <li>In the <span class='page-section-name'>Estimated Number and Cost of Backtests</span>, click an <a href='/docs/v2/our-platform/optimization/deployment#02-Resources'>optimization node</a>.</li>
    <li>Select a maximum number of nodes to use.</li>
    
    <li>In the <span class='page-section-name'>Estimated Number and Cost of Backtests</span>, click the <span class='field-name'>Choose Optimization Strategy</span> field and then select a <a href='/docs/v2/our-platform/optimization/strategies'>strategy</a> from the drop-down menu.</li>
    <li>Click the <span class='field-name'>Select Target</span> field and then select a target from the drop-down menu.</li>
    <li>Click <span class='box-name'>Maximize</span> or <span class='box-name'>Minimize</span> to maximize or minimize the optimization target, respectively.</li>
    <li>Click <span class='button-name'>Launch Optimization</span>.</li>
    <p>The optimization results page displays.</p>
    <img class='img-responsive' src="https://cdn.quantconnect.com/i/tu/view-optimization-results.png">
    <p>As the optimization job runs, you can close or refresh the window without interrupting the job because the nodes are processing on our servers.</p>
    <p>To abort a running optimization job, in the Status panel, click <span class='button-name'>Abort</span> and then click <span class='button-name'>Yes</span>.</p>
</ol>
