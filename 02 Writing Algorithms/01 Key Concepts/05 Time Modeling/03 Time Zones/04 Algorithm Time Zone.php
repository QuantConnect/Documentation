<?php echo file_get_contents(DOCS_RESOURCES."/time-zone.html"); ?>

<p>To get the time zone of your algorithm, use the <code>TimeZone</code> property of your algorithm.</p>

<div class="section-example-container">
<pre class="python">time_zone = self.TimeZone</pre>
<pre class="csharp">var timeZone = TimeZone;</pre>
</div> 

 <p>To get the algorithm time in Coordinated Universal Time (UTC), use the <code>UtcTime</code> property.</p>


<div class="section-example-container">
<pre class="python">utc_time = self.UtcTime</pre>
<pre class="csharp">var utcTime = UtcTime;</pre>
</div> 

<p>The <code>Time</code> and <code>UtcTime</code> objects have no time zone. LEAN maintains their state to be consistent.</p>
