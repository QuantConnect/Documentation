<p>You need the following to optimize parameters:</p>

<ul>
    <li>At least one <a href='/docs/v2/writing-algorithms/parameters#02-Set-Parameters'>algorithm parameter in your project</a>.</li>
    <li>The <a href='/docs/v2/writing-algorithms/parameters#03-Get-Parameters'>GetParameter</a> method <span class='csharp'>or <code>Parameter</code> attribute </span>in your project.</li>
    <li>A successful backtest of the project.</li>
    <li><? if ($localPlatform) { ?>If you deploy the optimization job to QuantConnect Cloud, <? } ?><a href='/docs/v2/cloud-platform/organizations/credit'>QuantConnect Credit (QCC)</a> in your organization.</li>
</ul>

<p>Follow these steps to optimize parameters:</p>

<? $openProjectLink = $cloudPlatform ? "/docs/v2/cloud-platform/projects/getting-started#02-View-All-Projects" : "/docs/v2/local-platform/projects/getting-started#04-Open-Projects"; ?>

<ol>
    <li><a href="<?=$openProjectLink?>">Open the project</a> that contains the parameters you want to optimize.</li>
    <li>In the top-right corner of the IDE, click the <? if ($localPlatform) { ?><img class='inline-icon' src='https://cdn.quantconnect.com/i/tu/optimize-local.svg' alt="Local optimization icon"> / <? } ?><img class='inline-icon' src='https://cdn.quantconnect.com/i/tu/optimize-parameters-icon.png' alt="Cloud optimization icon"> <span class='icon-name'>Optimize</span> icon.</li>
    
    <li>On the Optimization page, in the <span class='page-section-name'>Parameter &amp; Constraints</span> section, enter the name of the parameter to optimize.</li>
    <p>The parameter name must match a parameter name in the Project panel.</p>
    <li>Enter the minimum and maximum parameter values.</li>
    <li>Click the <span class='icon-name'>gear</span> icon next to the parameter and then enter a step size.</li>
    <li>If you want to add a second parameter to optimize, click <span class='button-name'>Add Parameter</span>.</li>
    <p>You can optimize a maximum of two parameters. To optimize more parameters, <a href='/docs/v2/lean-cli/optimization/deployment#02-Run-Local-Optimizations'>run local optimizations with the CLI</a>.</p>
    
    <li>If you want to add <a href='/docs/v2/cloud-platform/optimization/objectives#06-Constraints'>optimization constraints</a>, follow these steps:</li>
    <ol>
    	<li>Click <span class='button-name'>Add Constraint</span>.</li>
    	<li>Click the <span class='field-name'>target</span> field and then select a <a href='/docs/v2/cloud-platform/optimization/objectives'>target</a> from the drop-down menu.</li>
    	<li>Click the <span class='field-name'>operation</span> field and then an operation from the drop-down menu.</li>
    	<li>Enter a constraint value.</li>
    </ol>
    
    <li>If you are deploying to QuantConnect Cloud, in the <span class='page-section-name'>Estimated Number and Cost of Backtests</span>, click an <a href='/docs/v2/cloud-platform/optimization/deployment#02-Resources'>optimization node</a> and then select a maximum number of nodes to use.</li>
    
    <li>In the <span class='page-section-name'>Strategy & Target</span> section, click the <span class='field-name'>Choose Optimization Strategy</span> field and then select a <a href='/docs/v2/cloud-platform/optimization/strategies'>strategy</a> from the drop-down menu.</li>
    <li>Click the <span class='field-name'>Select Target</span> field and then select a target from the drop-down menu.</li>
    <p>The target (also known as objective) is the performance metric the optimizer uses to compare the backtest performance of different parameter values.</p>
    <li>Click <span class='box-name'>Maximize</span> or <span class='box-name'>Minimize</span> to maximize or minimize the optimization target, respectively.</li>
    <li>Click <span class='button-name'>Launch Optimization</span>.</li>
    <? if ($cloudPlatform) { ?>
    <p>The <a href='/docs/v2/cloud-platform/optimization/results#02-View-Optimization-Results'>optimization results page</a> displays. As the optimization job runs, you can close or refresh the window without interrupting the job because the nodes are processing on our servers.</p>
    <? } else { ?>
    <p>The optimization results page displays. If you deploy a local optimization job, you can close Local Platform and Docker Desktop as the optimization job runs without interfering with the backtests. Just don't quit Docker Desktop. If you deploy the optimization job to QuantConnect Cloud, you can close Local Platform and Docker Desktop without interrupting with the backtests because the nodes are processing on our servers.</p>
    <? { ?>

    <p>To abort a running optimization job, in the Status panel, click <span class='button-name'>Abort</span> and then click <span class='button-name'>Yes</span>.</p>
</ol>
