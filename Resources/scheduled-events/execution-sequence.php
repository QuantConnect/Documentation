<p>The algorithm manager calls events in the following order:</p>
<ol>
    <? if ($linkScheduledEvents) { ?>
    <li><a href='/docs/v2/writing-algorithms/scheduled-events'>Scheduled Events</a></li>
    <li>Consolidation event handlers</li>
    <? } else { ?>
    <li>Scheduled Events</li>
    <li><a href='/docs/v2/writing-algorithms/consolidating-data/getting-started'>Consolidation event handlers</a></li>
    <? } ?>
    <li><code class="csharp">OnData</code><code class="python">on_data</code> event handler</li>
</ol>
<p>This event flow is important to note. For instance, if your consolidation handlers or <code class="csharp">OnData</code><code class="python">on_data</code> event handler appends data to a <code>RollingWindow</code> and you use that <code>RollingWindow</code> in your Scheduled Event, when the Scheduled Event executes, the <code>RollingWindow</code> won't contain the most recent data.</p>
