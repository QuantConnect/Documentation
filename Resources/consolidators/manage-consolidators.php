<p><?=$dataFormatInfo->textName?> consolidators aggregate <?=$dataFormatInfo->input?> into <?=$dataFormatInfo->output?>. Follow these steps to create and manage a <?=$dataFormatInfo->textName?> consolidator based on <?=$consolidatorInfo->basedOnText?>:</p>

<ol>
    <li>Create the consolidator.</li>
    <?=$consolidatorInfo->get_create_consolidator_text($dataFormatInfo->className, $dataFormatInfo->typeOf)?>

	<li>Add an event handler to the consolidator.</li>
	<div class='section-example-container'>
		<pre class='csharp'>_consolidator.DataConsolidated += ConsolidationHandler;</pre>
		<pre class='python'>self.consolidator.data_consolidated += self.consolidation_handler</pre>
	</div>
	<p>LEAN passes consolidated bars to the consolidator event handler in your algorithm. The most common error when creating consolidators is to put parenthesis <code>()</code> at the end of your method name when setting the event handler of the consolidator. If you use parenthesis, the method executes and the result is passed as the event handler instead of the method itself. Remember to pass the name of your method to the event system. Specifically, it should be <code class='csharp'>ConsolidationHandler</code><code class='python'>self.consolidation_handler</code>, not <code class='csharp'>ConsolidationHandler()</code><code class='python'>self.consolidation_handler()</code>.</p>


	<li>Define the consolidation handler.</li>
	<div class='section-example-container'>
		<pre class='csharp'>void ConsolidationHandler(object sender, <?=$dataFormatInfo->consolidationHandlerType?> consolidatedBar)
{

}</pre>
		<pre class='python'>def consolidation_handler(self, sender: object, consolidated_bar: <?=$dataFormatInfo->consolidationHandlerType?>) -&gt; None:
    pass</pre>
	</div>
	<?=$consolidatorInfo->get_define_handler_text($dataFormatInfo->isSecurityData)?>


	<li>Update the consolidator.</li>

	<p>You can automatically or manually update the consolidator.</p>

	<ul>
        <li>Automatic Updates</li>

		<p>To automatically update a consolidator with data from the <?=$dataFormatInfo->isSecurityData ? "security" : "data"?> subscription, call the <code>AddConsolidator</code> method of the Subscription Manager.</p>
		<div class='section-example-container'>
			<pre class='python'>self.subscription_manager.add_consolidator(self.symbol, self.consolidator)</pre>
			<pre class='csharp'>SubscriptionManager.AddConsolidator(_symbol, _consolidator);</pre>
		</div>


        <li>Manual Updates</li>

		<p>Manual updates let you control when the consolidator updates and what data you use to update it. If you need to warm up a consolidator with data outside of the <a href='/docs/v2/writing-algorithms/historical-data/warm-up-periods'>warm-up period</a>, you can manually update the consolidator. To manually update a consolidator, call its <code>Update</code> method with a <code><?=$dataFormatInfo->typeOf?></code> object. You can update the consolidator with data from the <a href='/docs/v2/writing-algorithms/key-concepts/time-modeling/timeslices'>Slice</a> object in the <code>OnData</code> method or with data from a <a href='/docs/v2/writing-algorithms/historical-data/history-requests'>history request</a>.</p>
		
		<?=$dataFormatInfo->manualUpdateCode?>

	</ul>


	<li>If you create consolidators for securities in a dynamic universe and register them for automatic updates, remove the consolidator when the security leaves the universe.</li>
	<div class='section-example-container'>
		<pre class='csharp'>SubscriptionManager.RemoveConsolidator(_symbol, _consolidator);</pre>
		<pre class='python'>self.subscription_manager.remove_consolidator(self.symbol, self.consolidator)</pre>
	</div>
	<p>If you have a dynamic universe and don't remove consolidators, they compound internally, causing your algorithm to slow down and eventually die once it runs out of RAM. For an example of removing consolidators from universe subscriptions, see the <a rel='nofollow' target='_blank' href='https://github.com/QuantConnect/Lean/blob/cfa08a11fba02704d82689268c475fbba9744f5e/Algorithm.CSharp/Alphas/GasAndCrudeOilEnergyCorrelationAlpha.cs#L255' class='csharp'>GasAndCrudeOilEnergyCorrelationAlpha</a><a rel='nofollow' target='_blank' href='https://github.com/QuantConnect/Lean/blob/03f56481d4baf5cd803ff3a39bdd04d2a6050058/Algorithm.Python/Alphas/GasAndCrudeOilEnergyCorrelationAlpha.py#L169' class='python'>GasAndCrudeOilEnergyCorrelationAlpha</a> in the LEAN GitHub repository.</p></p>
</ol>

<?=$consolidatorInfo->get_shortcut_text($dataFormatInfo->consolidationHandlerType)?>

