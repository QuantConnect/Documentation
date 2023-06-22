<p>Option strategies synchronously execute by default. To asynchronously execute Option strategies, set the <code>asynchronous</code> argument to <code class='python'>False</code><code class='csharp'>false</code>. You can also provide a tag and <a href="/docs/v2/writing-algorithms/trading-and-orders/order-properties">order properties</a> to the 
<?php
foreach ($methodNames as $i=>$methodName)
{
	if ($i > 0)
	{
		echo " and ";
	}
	echo "<code>{$methodName}</code>";
}
if (count($methodNames) == 1)
{
	echo " method";
}
else
{
	echo " methods";
}
?>
.</p>

<div class="section-example-container">
<pre class="csharp"><?php 
foreach ($methodNames as $methodName) 
{
	echo "{$methodName}(optionStrategy, quantity, asynchronous, tag, orderProperties);
";
}
?></pre>
<pre class="python"><?php 
foreach ($methodNames as $methodName) 
{
	echo "self.{$methodName}(option_strategy, quantity, asynchronous, tag, order_properties)
";
}
?></pre>
</div>
