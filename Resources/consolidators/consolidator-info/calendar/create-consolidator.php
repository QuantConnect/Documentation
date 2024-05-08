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
	            <td><code class="csharp">Calendar.Weekly</code><code class="python">Calendar.WEEKLY</code></td>
		    	<td>Computes the start of week (previous Monday) of the given date/time</td>
	        </tr>
	        <tr>
	            <td><code class="csharp">Calendar.Monthly</code><code class="python">Calendar.MONTHLY</code></td>
			    <td>Computes the start of month (1st of the current month) of the given date/time</td>
	        </tr>
	        <tr>
	            <td><code class="csharp">Calendar.Quarterly</code><code class="python">Calendar.QUARTERLY</code></td>
			    <td>Computes the start of quarter (1st of the starting month of the current quarter) of the given date/time</td>
	        </tr>
	        <tr>
	            <td><code class="csharp">Calendar.Yearly</code><code class="python">Calendar.YEARLY</code></td>
			    <td>Computes the start of year (1st of the current year) of the given date/time</td>
	        </tr>
	    </tbody>
	</table>

	<div class='section-example-container'>
		<pre class='csharp'>_consolidator = new <?=$consolidatorClassName?>(Calendar.Weekly);
// Alias:
// _consolidator = CreateConsolidator(Calendar.Weekly, typeof(<?=$typeOf?>)<?=$this->createConsolidatorExtraArgs?>);</pre>
		<pre class='python'>self._consolidator = <?=$consolidatorClassName?>(Calendar.WEEKLY)
# Alias:
# self._consolidator = self.create_consolidator(Calendar.WEEKLY, <?=$typeOf?><?=$this->createConsolidatorExtraArgs?>)</pre>
	</div>
	
	<p class='csharp'>To use the <code>Calendar</code> class, you may need to add the following code to the imports section because of ambiguity with <a rel='nofollow' target='_blank' href='https://learn.microsoft.com/en-us/dotnet/api/system.globalization.calendar?view=net-7.0'>.NET Calendar Class</a>:</p>
	<div class='csharp section-example-container'>
		<pre class='csharp'>using Calendar = QuantConnect.Data.Consolidators.Calendar;</pre>
	</div>


	<li>Custom Periods</li>

	<p>If you need something more specific than the preceding time periods, define a method to set the start time and period of the consolidated bars. The method should receive a <code class='python'>datetime</code><code class='csharp'>DateTime</code> object that's based in the <a href='/docs/v2/writing-algorithms/key-concepts/time-modeling/time-zones#05-Data-Time-Zone'>data time zone</a> and should return a <code>CalendarInfo</code> object, which contains the start time of the bar in the data time zone and the duration of the consolidation period. The following example demonstrates how to create a custom consolidator for weekly bars:</p>

	<div class='section-example-container'>
    	<pre class='csharp'>_consolidator = new <?=$consolidatorClassName?>(datetime => {
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
def _consolidation_period(self, dt: datetime) -&gt; CalendarInfo:
    period = timedelta(7)

    dt = dt.replace(hour=17, minute=0, second=0, microsecond=0)
    delta = 1+dt.weekday()
    if delta &gt; 6:
        delta = 0
    start = dt-timedelta(delta)

    return CalendarInfo(start, period)

# Create the consolidator with the consolidation period method
self._consolidator = <?=$consolidatorClassName?>(self._consolidation_period)</pre>
	</div>
</ul>
