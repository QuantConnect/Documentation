<?
include(DOCS_RESOURCES."/initialization/set-time-zone.php");
?>

<p>To get the time zone of your algorithm, use the<code class="csharp">TimeZone</code><code class="python">time_zone</code> property.</p>

<div class="section-example-container">
<pre class="python"># The algorithm timezone property can assist with multi-asset trading.
time_zone = self.time_zone</pre>
<pre class="csharp">// The algorithm timezone property can assist with multi-asset trading.
var timeZone = TimeZone;</pre>
</div> 

 <p>To get the algorithm time in Coordinated Universal Time (UTC), use the <code class="csharp">UtcTime</code><code class="python">utc_time</code> property.</p>

<div class="section-example-container">
<pre class="python"># Access the current UTC time to coordinate multi-timezone events or improve logging.
utc_time = self.utc_time</pre>
<pre class="csharp">// Access the current UTC time to coordinate multi-timezone events or improve logging.
var utcTime = UtcTime;</pre>
</div> 

<p>The <code class="csharp">Time</code><code class="python">time</code> and <code class="csharp">UtcTime</code><code class="python">utc_time</code> objects have no time zone. LEAN maintains their state to be consistent.</p>
