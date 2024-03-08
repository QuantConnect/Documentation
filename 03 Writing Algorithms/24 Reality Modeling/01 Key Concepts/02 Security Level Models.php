<p>LEAN's philosophy is to make the models customizable per security as much as possible. The following models are security-level models:</p>

<ul>
    <li><a href="/docs/v2/writing-algorithms/reality-modeling/trade-fills/key-concepts">Fill</a></li>
    <li><a href="/docs/v2/writing-algorithms/reality-modeling/slippage/key-concepts">Slippage</a></li>
    <li><a href="/docs/v2/writing-algorithms/reality-modeling/transaction-fees/key-concepts">Fee</a></li>
    <li><a href="/docs/v2/writing-algorithms/reality-modeling/brokerages/key-concepts">Brokerages</a></li>
    <li><a href="/docs/v2/writing-algorithms/reality-modeling/buying-power">Buying Power</a></li>
    <li><a href="/docs/v2/writing-algorithms/reality-modeling/settlement/key-concepts">Settlement </a></li>
    <li><a href="/docs/v2/writing-algorithms/reality-modeling/short-availability/key-concepts">Short availability</a></li>
    <li>Option models</li>
    <ul>
        <li><a href="/docs/v2/writing-algorithms/reality-modeling/options-models/pricing">Pricing</a></li>
        <li><a href="/docs/v2/writing-algorithms/reality-modeling/options-models/volatility/key-concepts">Volatility</a></li>
        <li><a href="/docs/v2/writing-algorithms/reality-modeling/options-models/exercise">Exercise</a></li>
	<li><a href="/docs/v2/writing-algorithms/reality-modeling/options-models/assignment">Assignment</a></li>
    </ul>
    <li><a href="/docs/v2/writing-algorithms/reality-modeling/margin-interest-rate/key-concepts">Margin Interest Rate</a></li>
    <li><a href="/docs/v2/writing-algorithms/reality-modeling/dividend-yield/key-concepts">Dividend Yield</a></li>
</ul>


<p>To set a security-level reality model, call the set reality model method on the <code>Security</code> object. To get the correct method, see the preceding documentation page for each type of model.<br></p>

<div class="section-example-container">	
	<pre class="csharp">// Set IBM to have a constant $1 transaction fee 
Securities["IBM"].SetFeeModel(new ConstantFeeModel(1)); </pre>
        <pre class="python"># Set IBM to have a constant $1 transaction fee
self.Securities["IBM"].SetFeeModel(ConstantFeeModel(1))</pre>
</div>

<p>You can also set the security-specific models inside a <a href='/docs/v2/writing-algorithms/initialization#07-Set-Security-Initializer'>security initializer</a>.</p>

<?php
$overwriteCodePy = "security.SetFeeModel(ConstantFeeModel(1))";
$overwriteCodeC = "security.SetFeeModel(new ConstantFeeModel(1));";
include(DOCS_RESOURCES."/reality-modeling/brokerage-model-security-init.php");
?>
