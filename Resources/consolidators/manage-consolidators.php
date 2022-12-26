<?php
if (!class_exists('ConsolidatorFormatInfo'))
{
class ConsolidatorFormatInfo
{
	public $textName,
		$input,
		$output,
		$className,
		$typeOf,
		$consolidationHandlerType,
		$manualUpdateCode;
}

class TradeBarConsolidatorFormatInfo extends ConsolidatorFormatInfo
{
	function __construct($output = "<code>TradeBar</code> objects of the same size or larger", $consolidationHandlerType = "TradeBar")
	{
		$this->output = $output;
		$this->consolidationHandlerType = $consolidationHandlerType;
		$this->textName = "<code>TradeBar</code>";
		$this->className = "TradeBarConsolidator";
		$this->input = "<code>TradeBar</code> objects";
		$this->typeOf = "TradeBar";
		$this->manualUpdateCode = file_get_contents(DOCS_RESOURCES."/consolidators/trade-bar-manual-update.html");
	}
}

class QuoteBarConsolidatorFormatInfo extends ConsolidatorFormatInfo
{
	function __construct($output = "<code>QuoteBar</code> objects of the same size or larger", $consolidationHandlerType = "QuoteBar")
	{
		$this->output = $output;
		$this->consolidationHandlerType = $consolidationHandlerType;
		$this->textName = "<code>QuoteBar</code>";
		$this->className = "QuoteBarConsolidator";
		$this->input = "<code>QuoteBar</code> objects";
		$this->typeOf = "QuoteBar";
		$this->manualUpdateCode = file_get_contents(DOCS_RESOURCES."/consolidators/quote-bar-manual-update.html");
	}
}

class TickConsolidatorFormatInfo extends ConsolidatorFormatInfo
{
	function __construct($output = "<code>TradeBar</code> objects", $consolidationHandlerType = "TradeBar")
	{
		$this->output = $output;
		$this->consolidationHandlerType = $consolidationHandlerType;
		$this->textName = "<code>Tick</code>";
		$this->className = "TickConsolidator";
		$this->input = "<code>Tick</code> objects";
		$this->typeOf = "Tick";
		$this->manualUpdateCode = file_get_contents(DOCS_RESOURCES."/consolidators/tick-manual-update.html");
	}
}

class TickQuoteBarConsolidatorFormatInfo extends ConsolidatorFormatInfo
{
	function __construct($output = "<code>QuoteBar</code> objects", $consolidationHandlerType = "QuoteBar")
	{
		$this->output = $output;
		$this->consolidationHandlerType = $consolidationHandlerType;
		$this->textName = "<code>Tick</code> quote bar";
		$this->className = "TickQuoteBarConsolidator";
		$this->input = "<code>Tick</code> objects that represent quotes";
		$this->typeOf = "Tick";
		$this->manualUpdateCode = file_get_contents(DOCS_RESOURCES."/consolidators/tick-manual-update.html");
	}
}

class ConsolidatorInfo
{
	function get_define_handler_text()
	{
		return "";
	}

	function get_shortcut_text($consolidationHandlerType)
	{
		return "";
	}
}

class TimePeriodConsolidatorInfo extends ConsolidatorInfo
{
	public $timeSpanPeriod, 
		$timeDeltaPeriod, 
		$resolutionPeriod, 
		$createConsolidatorExtraArgs,
		$resolveConsolidatorExtraArgsC,
		$resolveConsolidatorExtraArgsPy,
		$resolutionArgExtraExamplesC,
		$consolidationTextResolution,
		$consolidationTextReceiveTime1,
		$consolidationTextReceiveTime2,
		$manualUpdateCode,
		$basedOnText;

	function __construct($timeSpanPeriod, 
		$timeDeltaPeriod, 
		$resolutionPeriod, 
		$createConsolidatorExtraArgs,
		$resolveConsolidatorExtraArgsC,
		$resolveConsolidatorExtraArgsPy,
		$resolutionArgExtraExamplesC,
		$resolutionArgExtraExamplesPy,
		$consolidationTextResolution,
		$consolidationTextReceiveTime1,
		$consolidationTextReceiveTime2,
		$shortcutClass1,
		$shortcutClass2,
		$shortCutTickTypeArg)
	{
		$this->timeSpanPeriod = $timeSpanPeriod;
		$this->timeDeltaPeriod = $timeDeltaPeriod;
		$this->resolutionPeriod = $resolutionPeriod;
		$this->createConsolidatorExtraArgs = $createConsolidatorExtraArgs;
		$this->resolveConsolidatorExtraArgsC = $resolveConsolidatorExtraArgsC;
		$this->resolveConsolidatorExtraArgsPy = $resolveConsolidatorExtraArgsPy;
		$this->resolutionArgExtraExamplesC = $resolutionArgExtraExamplesC;
		$this->resolutionArgExtraExamplesPy = $resolutionArgExtraExamplesPy;
		$this->consolidationTextResolution = $consolidationTextResolution;
		$this->consolidationTextReceiveTime1 = $consolidationTextReceiveTime1;
		$this->consolidationTextReceiveTime2 = $consolidationTextReceiveTime2;
		$this->shortcutClass1 = $shortcutClass1;
		$this->shortcutClass2 = $shortcutClass2;
		$this->shortCutTickTypeArg = $shortCutTickTypeArg;
		$this->basedOnText = "a period of time";
	}

	function get_create_consolidator_text($consolidatorClassName, $typeOf) 
	{
		$result = "
<p>To set the time period for the consolidator, you can use either a <code class='python'>timedelta</code><code class='csharp'>TimeSpan</code> or <code>Resolution</code> object. The consolidator time period must be greater than or equal to the resolution of the security subscription. For instance, you can aggregate minute bars into 10-minute bars, but you can't aggregate hour bars into 10-minute bars.</p>
<ul>
	<li class='python'><code>timedelta</code> Periods</li>
	<li class='csharp'><code>TimeSpan</code> Periods</li>
		
	<div class='section-example-container'>
		<pre class='csharp'>_consolidator = new {$consolidatorClassName}(TimeSpan.{$this->timeSpanPeriod});
// Aliases:
// _consolidator = CreateConsolidator(TimeSpan.{$this->timeSpanPeriod}, typeof({$typeOf}){$this->createConsolidatorExtraArgs});
// _consolidator = ResolveConsolidator(_symbol, TimeSpan.{$this->timeSpanPeriod}{$this->resolveConsolidatorExtraArgsC});</pre>
			<pre class='python'>self.consolidator = {$consolidatorClassName}(timedelta({$this->timeDeltaPeriod}))
# Aliases:
# self.consolidator = self.CreateConsolidator(timedelta({$this->timeDeltaPeriod}), {$typeOf}{$this->createConsolidatorExtraArgs})
# self.consolidator = self.ResolveConsolidator(self.symbol, timedelta({$this->timeDeltaPeriod}){$this->resolveConsolidatorExtraArgsPy})</pre>
	</div>
		";
	
		$result .= file_get_contents(DOCS_RESOURCES."/consolidators/time-period-start-time.html");

		$result .= "

	<li><code>Resolution</code> Periods</li>
	<p>The <code>Resolution</code> enumeration has the following members:</p>
	<div data-tree='QuantConnect.Resolution'></div>


	<div class='section-example-container'>
		<pre class='csharp'>{$this->resolutionArgExtraExamplesC}_consolidator = ResolveConsolidator(_symbol, Resolution.{$this->resolutionPeriod});</pre>
		<pre class='python'>{$this->resolutionArgExtraExamplesPy}self.consolidator = self.ResolveConsolidator(self.symbol, Resolution.{$this->resolutionPeriod})</pre>
	</div>
</ul>
		";

		if ($typeOf == "TradeBar" || $typeOf == "QuoteBar")
		{
			$result .= "
<p>If the security subscription in your algorithm provides <code>TradeBar</code> and <code>QuoteBar</code> data, <code>ResolveConsolidator</code> returns a <code>TradeBarConsolidator</code>.</p>
			";
		}

		$result .= file_get_contents(DOCS_RESOURCES."/consolidators/time-period-end-time.html");
		return $result;
	}

	function get_define_handler_text()
	{
		$result = "<p>The consolidation event handler receives bars when the consolidated bar closes based on the <a href='/docs/v2/writing-algorithms/key-concepts/time-modeling/time-zones#05-Data-Time-Zone'>data time zone</a>. ";
		include(DOCS_RESOURCES."/consolidators/consolidation-handler-close-explanation.php");
		$result .= $getConsolidationExplanationText($this->consolidationTextResolution, $this->consolidationTextReceiveTime1, $this->consolidationTextReceiveTime2);
		$result .= "</p>";
		return $result;
	}


	function get_shortcut_text($consolidationHandlerType)
	{
		$methodTyping = $consolidationHandlerType == "QuoteBar" ? "&lt;QuoteBar&gt;" : "";

		$result = "
<p {$this->shortcutClass1}>You can also use the <code>Consolidate</code> helper method to create period consolidators and register them for automatic updates. With just one line of code, you can create data in any time period based on a <code class='python'>timedelta</code><code class='csharp'>TimeSpan</code> or <code>Resolution</code> object:</p>

<ul {$this->shortcutClass1}>
    <li class='csharp'><code>TimeSpan</code> Periods</li>
    <li class='python'><code>timedelta</code> Periods</li>
    <div class='section-example-container'>
		<pre class='csharp'>_consolidator = Consolidate{$methodTyping}(_symbol, TimeSpan.{$this->timeSpanPeriod}, {$this->shortCutTickTypeArg}ConsolidationHandler);</pre>
		<pre class='python'>self.consolidator = self.Consolidate(self.symbol, timedelta({$this->timeDeltaPeriod}), {$this->shortCutTickTypeArg}self.consolidation_handler)</pre>
	</div>

    <li><code>Resolution</code> Periods</li>
    <div class='section-example-container'>
		<pre class='csharp'>_consolidator = Consolidate{$methodTyping}(_symbol, Resolution.{$this->resolutionPeriod}, {$this->shortCutTickTypeArg}ConsolidationHandler);</pre>
		<pre class='python'>self.consolidator = self.Consolidate(self.symbol, Resolution.{$this->resolutionPeriod}, {$this->shortCutTickTypeArg}self.consolidation_handler)</pre>
	</div>
</ul>
		";

		include(DOCS_RESOURCES."/consolidators/get-shortcut-text.php");
		$result .= $getShortcutText($this->shortcutClass1, $this->shortcutClass2, $consolidationHandlerType);
		return $result;
	}
}


class CountConsolidatorInfo extends ConsolidatorInfo
{
	public $numSamples,
		$basedOnText;

	function __construct($numSamples)
	{
		$this->numSamples = $numSamples;
		$this->basedOnText = "a number of samples";
	}

	function get_create_consolidator_text($consolidatorClassName, $typeOf) 
	{
		return "
<p>To create a count consolidator, pass the number of samples to the consolidator constructor.</p>

<div class='section-example-container'>
	<pre class='csharp'>_consolidator = new {$consolidatorClassName}({$this->numSamples});</pre>
	<pre class='python'>self.consolidator = {$consolidatorClassName}({$this->numSamples})</pre>
</div>
		";
	}

	function get_define_handler_text()
	{
		return "<p>When the consolidator receives the <span class='latex-variable'>n</span>-th bar or tick, it passes the consolidated bar to the event handler.</p>";
	}

}


class MixedModeConsolidatorInfo extends ConsolidatorInfo
{
	public $numSamples,
		$timeSpanPeriod,
		$timeDeltaPeriod,
		$consolidationTextResolution,
		$consolidationTextReceiveTime1,
		$consolidationTextReceiveTime2,
		$basedOnText;

	function __construct($numSamples, $timeSpanPeriod, $timeDeltaPeriod, $consolidationTextResolution, $consolidationTextReceiveTime1, $consolidationTextReceiveTime2)
	{
		$this->numSamples = $numSamples;
		$this->timeSpanPeriod = $timeSpanPeriod;
		$this->timeDeltaPeriod = $timeDeltaPeriod;
		$this->consolidationTextResolution = $consolidationTextResolution;
		$this->consolidationTextReceiveTime1 = $consolidationTextReceiveTime1;
		$this->consolidationTextReceiveTime2 = $consolidationTextReceiveTime2;
		$this->basedOnText = "a period of time or a number of samples, whichever occurs first";
	}

	function get_create_consolidator_text($consolidatorClassName, $typeOf) 
	{
		$result = "
<p>To create a mixed-mode consolidator, pass a <code class='python'>timedelta</code><code class='csharp'>TimeSpan</code> object and an integer to the consolidator constructor. The consolidator time period must be greater than or equal to the resolution of the security subscription. For instance, you can aggregate minute bars into 10-minute bars, but you can't aggregate hour bars into 10-minute bars.</p>
		";

		$result .= file_get_contents(DOCS_RESOURCES."/consolidators/time-period-start-time.html");

		$result .= "
<div class='section-example-container'>
	<pre class='csharp'>_consolidator = new {$consolidatorClassName}({$this->numSamples}, TimeSpan.{$this->timeSpanPeriod});</pre>
	<pre class='python'>self.consolidator = {$consolidatorClassName}({$this->numSamples}, timedelta({$this->timeDeltaPeriod}))</pre>
</div>
		";
		$result .= file_get_contents(DOCS_RESOURCES."/consolidators/time-period-end-time.html");
		return $result;
	}

	function get_define_handler_text()
	{
		$result = "<p>The consolidation event handler receives bars when the consolidated bar closes based on the <a href='/docs/v2/writing-algorithms/key-concepts/time-modeling/time-zones#05-Data-Time-Zone'>data time zone</a> or the number of samples, whichever occurs first. ";
		include(DOCS_RESOURCES."/consolidators/consolidation-handler-close-explanation.php");
		$result .= $getConsolidationExplanationText($this->consolidationTextResolution, $this->consolidationTextReceiveTime1, $this->consolidationTextReceiveTime2);
		$result .= "</p>";
		return $result;
	}
}



class CalendarConsolidatorInfo extends ConsolidatorInfo
{
	public $createConsolidatorExtraArgs,
	 	$shortcutClass1,
	 	$shortcutClass2,
	 	$shortCutTickTypeArg,
		$basedOnText;

	function __construct($createConsolidatorExtraArgs, $shortcutClass1, $shortcutClass2, $shortCutTickTypeArg)
	{
		$this->createConsolidatorExtraArgs = $createConsolidatorExtraArgs;
		$this->shortcutClass1 = $shortcutClass1;
		$this->shortcutClass2 = $shortcutClass2;
		$this->shortCutTickTypeArg = $shortCutTickTypeArg;
		$this->basedOnText = "custom start and end periods";
	}

	function get_create_consolidator_text($consolidatorClassName, $typeOf) 
	{
		return "
<p>To set the time period for the consolidator, you can use the built-in <code>CalendarInfo</code> objects or create your own. The following list describes each technique:</p>

<ul>
	<li>Standard Periods</li>
	<p>The following table describes the helper methods that the <code>Calendar</code> class provides to create the built-in <code>CalendarInfo</code> objects:</p>
	<table class='qc-table table'>
	    <thead>
	        <tr>
	            <th>Method</th>
	            <th>Description</th>
	        </tr>
	    </thead>
	    <tbody>
	        <tr>
	            <td><code>Calendar.Weekly</code></td>
		    	<td>Computes the start of week (previous Monday) of the given date/time</td>
	        </tr>
	        <tr>
	            <td><code>Calendar.Monthly</code></td>
			    <td>Computes the start of month (1st of the current month) of the given date/time</td>
	        </tr>
	        <tr>
	            <td><code>Calendar.Quarterly</code></td>
			    <td>Computes the start of quarter (1st of the starting month of the current quarter) of the given date/time</td>
	        </tr>
	        <tr>
	            <td><code>Calendar.Yearly</code></td>
			    <td>Computes the start of year (1st of the current year) of the given date/time</td>
	        </tr>
	    </tbody>
	</table>

	<div class='section-example-container'>
		<pre class='csharp'>_consolidator = new {$consolidatorClassName}(Calendar.Weekly);
// Alias:
// _consolidator = CreateConsolidator(Calendar.Weekly, typeof({$typeOf}){$this->createConsolidatorExtraArgs});</pre>
		<pre class='python'>self.consolidator = {$consolidatorClassName}(Calendar.Weekly)
# Alias:
# self.consolidator = self.CreateConsolidator(Calendar.Weekly, {$typeOf}{$this->createConsolidatorExtraArgs})</pre>
	</div>



	<li>Custom Periods</li>

	<p>If you need something more specific than the preceding time periods, define a method to set the start time and period of the consolidated bars. The method should receive a <code class='python'>datetime</code><code class='csharp'>DateTime</code> object that's based in the <a href='/docs/v2/writing-algorithms/key-concepts/time-modeling/time-zones#05-Data-Time-Zone'>data time zone</a> and should return a <code>CalendarInfo</code> object, which contains the start time of the bar in the data time zone and the duration of the consolidation period. The following example demonstrates how to create a custom consolidator for weekly bars:</p>

	<div class='section-example-container'>
    	<pre class='csharp'>_consolidator = new {$consolidatorClassName}(datetime => {
    var period = TimeSpan.FromDays(7);

    var timeSpan = new TimeSpan(17, 0, 0);
    var newDateTime = datetime.Date + timeSpan;
    var delta = 1 + (int)newDateTime.DayOfWeek;
    if (delta &gt; 6)
    {
        delta = 0;
    }
    var start = newDateTime.AddDays(-delta);

    return new CalendarInfo(start, period);
});</pre>    
    	<pre class='python'># Define a consolidation period method
def consolidation_period(self, dt: datetime) -&gt; CalendarInfo:
    period = timedelta(7)

    dt = dt.replace(hour=17, minute=0, second=0, microsecond=0)
    delta = 1+dt.weekday()
    if delta &gt; 6:
        delta = 0
    start = dt-timedelta(delta)

    return CalendarInfo(start, period)

# Create the consolidator with the consolidation period method
self.consolidator = {$consolidatorClassName}(self.consolidation_period)</pre>
	</div>
</ul>
		";
	}

	function get_define_handler_text()
	{
		return "<p>If you use a custom consolidation period method, LEAN passes the consolidated bar to the consolidation handler when the consolidation period ends. The <code>Time</code> and <code>EndTime</code> properties of the consolidated bar reflect the data time zone, but the <code>Time</code> property of the algorithm still reflects the <a href='/docs/v2/writing-algorithms/key-concepts/time-modeling/time-zones#04-Algorithm-Time-Zone'>algorithm time zone</a>.</p>";
	}


	function get_shortcut_text($consolidationHandlerType)
	{
		$methodTyping = $consolidationHandlerType == "QuoteBar" ? "&lt;QuoteBar&gt;" : "";

		$result = "
<p {$this->shortcutClass1}>You can also use the <code>Consolidate</code> helper method to create calendar consolidators and register them for automatic updates.</p>

<ul {$this->shortcutClass1}>
    <div class='section-example-container'>
		<pre class='csharp'>_consolidator = Consolidate{$methodTyping}(_symbol, Calendar.Weekly, {$this->shortCutTickTypeArg}ConsolidationHandler);</pre>
		<pre class='python'>self.consolidator = self.Consolidate(self.symbol, Calendar.Weekly, {$this->shortCutTickTypeArg}self.consolidation_handler)</pre>
	</div>
</ul>
		";

		include(DOCS_RESOURCES."/consolidators/get-shortcut-text.php");
		$result .= $getShortcutText($this->shortcutClass1, $this->shortcutClass2, $consolidationHandlerType);
		return $result;
	}
}



class RenkoConsolidatorInfo extends ConsolidatorInfo
{
	public $basedOnText;

	function __construct()
	{
		$this->basedOnText = "***TODO";
	}

	function get_create_consolidator_text($consolidatorClassName, $typeOf) 
	{
		return "
<p>To create a Renko consolidator, pass the bar size to the <code>RenkoConsolidator</code> constructor.</p>

<div class='section-example-container'>
	<pre class='csharp'>// Create a Renko consolidator that emits a bar when the price moves $1
_consolidator = new RenkoConsolidator(1m);</pre>
	<pre class='python'># Create a Renko consolidator that emits a bar when the price moves $1
self.consolidator = RenkoConsolidator(1)</pre>
</div>
		";
	}

	function get_define_handler_text()
	{
		return "<p>The consolidation event handler receives bars when the price movement forms a new Renko bar.</p>";
	}
}

class ClassicRenkoConsolidatorInfo extends ConsolidatorInfo
{
	public $basedOnText,
		$extraExamples;

	function __construct($extraExamples = "")
	{
		$this->basedOnText = "**TODO";
		$this->extraExamples = $extraExamples;
	}

	function get_create_consolidator_text($consolidatorClassName, $typeOf) 
	{
		return "
<p>To create a classic Renko consolidator, pass the bar size to the <code>ClassicRenkoConsolidator</code> constructor.</p>

<div class='section-example-container'>
	<pre class='csharp'>// Create a Classic Renko consolidator that emits a bar when the price moves $1
_consolidator = new ClassicRenkoConsolidator(1m);</pre>
	<pre class='python'># Create a Classic Renko consolidator that emits a bar when the price moves $1
self.consolidator = ClassicRenkoConsolidator(1)</pre>
</div>
		

<p>The <code>ClassicRenkoConsolidator</code> has the following default behavior:</p>
<ul>
    <li>It uses the <code>Value</code> property of the <code>IBaseData</code> object it receives to build the Renko bars</li>
    <li>It ignores the volume of the input data</li>
    <li>It enforces the open and close of each bar to be a multiple of the bar size</li>
</ul>

{$this->extraExamples}

<p>To relax the requirement that the open and close of the Renko bars must be a multiple of bar size, disable the <code>evenBars</code> argument. If you disable <code>evenBars</code>, the open value of the first Renko bar is set to the first value from the <code>selector</code>. The following opening and closing Renko bar values are all multiples of the first value from the <code>selector</code></p>

<div class='section-example-container'>
	<pre class='python'>self.consolidator = ClassicRenkoConsolidator(1, evenBars = False)</pre>
	<pre class='csharp'>_consolidator = new ClassicRenkoConsolidator(1m, evenBars: false);</pre>
</div>

<p>To view a full example of a <code>ClassicRenkoConsolidator</code>, see the <a href='https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/ClassicRenkoConsolidatorAlgorithm.py' class='python' rel='nofollow' target='_blank'>ClassicRenkoConsolidatorAlgorithm</a><a href='https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/ClassicRenkoConsolidatorAlgorithm.cs' class='csharp' rel='nofollow' target='_blank'>ClassicRenkoConsolidatorAlgorithm</a> in the LEAN GitHub repository.</p>
		";
	}

	function get_define_handler_text()
	{
		return "<p>The consolidation event handler receives bars when the price movement forms a new classic Renko bar.</p>";
	}
}
}


$getConsolidatorText = function($dataFormatInfo, $consolidatorInfo)
{
	echo "
<p>{$dataFormatInfo->textName} consolidators aggregate {$dataFormatInfo->input} into {$dataFormatInfo->output}. Follow these steps to create and manage a {$dataFormatInfo->textName} consolidator based on {$consolidatorInfo->basedOnText}:</p>

<ol>
    <li>Create the consolidator.</li>
    ";
    echo $consolidatorInfo->get_create_consolidator_text($dataFormatInfo->className, $dataFormatInfo->typeOf);


	echo "
	<li>Add an event handler to the consolidator.</li>
	<div class='section-example-container'>
		<pre class='csharp'>_consolidator.DataConsolidated += ConsolidationHandler;</pre>
		<pre class='python'>self.consolidator.DataConsolidated += self.consolidation_handler</pre>
	</div>
	<p>LEAN passes consolidated bars to the consolidator event handler in your algorithm. The most common error when creating consolidators is to put parenthesis <code>()</code> at the end of your method name when setting the event handler of the consolidator. If you use parenthesis, the method executes and the result is passed as the event handler instead of the method itself. Remember to pass the name of your method to the event system. Specifically, it should be <code class='csharp'>ConsolidationHandler</code><code class='python'>self.consolidation_handler</code>, not <code class='csharp'>ConsolidationHandler()</code><code class='python'>self.consolidation_handler()</code>.</p>
	";


	echo "
	<li>Define the consolidation handler.</li>
	<div class='section-example-container'>
		<pre class='csharp'>void ConsolidationHandler(object sender, {$dataFormatInfo->consolidationHandlerType} consolidatedBar)
{

}</pre>
		<pre class='python'>def consolidation_handler(self, sender: object, consolidated_bar: {$dataFormatInfo->consolidationHandlerType}) -&gt; None:
    pass</pre>
	</div>
	";

	echo $consolidatorInfo->get_define_handler_text();


	echo "
	<li>Update the consolidator.</li>

	<p>You can automatically or manually update the consolidator.</p>

	<ul>
        <li>Automatic Updates</li>

		<p>To automatically update a consolidator with data from the security subscription, call the <code>AddConsolidator</code> method of the Subscription Manager.</p>
		<div class='section-example-container'>
			<pre class='python'>self.SubscriptionManager.AddConsolidator(self.symbol, self.consolidator)</pre>
			<pre class='csharp'>SubscriptionManager.AddConsolidator(_symbol, _consolidator);</pre>
		</div>


        <li>Manual Updates</li>

		<p>Manual updates let you control when the consolidator updates and what data you use to update it. If you need to warm up a consolidator with data outside of the <a href='/docs/v2/writing-algorithms/historical-data/warm-up-periods'>warm-up period</a>, you can update the consolidator with data from a <a href='/docs/v2/writing-algorithms/historical-data/history-requests'>history request</a>. To manually update a consolidator, call its <code>Update</code> method with a <code>{$dataFormatInfo->typeOf}</code> object. You can update the consolidator with data from the <a href='/docs/v2/writing-algorithms/key-concepts/time-modeling/timeslices'>Slice</a> object in the <code>OnData</code> method or with data from a <a href='/docs/v2/writing-algorithms/historical-data/history-requests'>history request</a>.</p>
		
		{$dataFormatInfo->manualUpdateCode}

	</ul>


	<li>If you create consolidators for securities in a dynamic universe and register them for automatic updates, remove the consolidator when the security leaves the universe.</li>
	<div class='section-example-container'>
		<pre class='csharp'>SubscriptionManager.RemoveConsolidator(_symbol, _consolidator);</pre>
		<pre class='python'>self.SubscriptionManager.RemoveConsolidator(self.symbol, self.consolidator)</pre>
	</div>
	<p>If you have a dynamic universe and don't remove consolidators, they compound internally, causing your algorithm to slow down and eventually die once it runs out of RAM. For an example of removing consolidators from universe subscriptions, see the <a rel='nofollow' target='_blank' href='https://github.com/QuantConnect/Lean/blob/cfa08a11fba02704d82689268c475fbba9744f5e/Algorithm.CSharp/Alphas/GasAndCrudeOilEnergyCorrelationAlpha.cs#L255' class='csharp'>GasAndCrudeOilEnergyCorrelationAlpha</a><a rel='nofollow' target='_blank' href='https://github.com/QuantConnect/Lean/blob/03f56481d4baf5cd803ff3a39bdd04d2a6050058/Algorithm.Python/Alphas/GasAndCrudeOilEnergyCorrelationAlpha.py#L169' class='python'>GasAndCrudeOilEnergyCorrelationAlpha</a> in the LEAN GitHub repository.</p></p>
</ol>
	";

	echo $consolidatorInfo->get_shortcut_text($dataFormatInfo->consolidationHandlerType);
}
?>
