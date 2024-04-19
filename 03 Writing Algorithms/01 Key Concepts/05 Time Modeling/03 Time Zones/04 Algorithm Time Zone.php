<?
include(DOCS_RESOURCES."/initialization/set-time-zone.php");
?>

<p>To get the time zone of your algorithm, use the<code class="csharp">TimeZone</code><code class="python">time_zone</code> property.</p>

<div class="section-example-container">
<pre class="python">time_zone = self.time_zone</pre>
<pre class="csharp">var timeZone = TimeZone;</pre>
</div> 

 <p>To get the algorithm time in Coordinated Universal Time (UTC), use the <code class="csharp">UtcTime</code><code class="python">utc_time</code> property.</p>


<div class="section-example-container">
<pre class="python">utc_time = self.utc_time</pre>
<pre class="csharp">var utcTime = UtcTime;</pre>
</div> 

<p>The <code class="csharp">Time</code><code class="python">time</code> and <code class="csharp">UtcTime</code><code class="python">utc_time</code> objects have no time zone. LEAN maintains their state to be consistent.</p>
