<p>
The <code>Schedule</code> setting defines the selection schedule of the universe. 
Most universes run on a daily schedule.
To change the selection schedule, call the <code>UniverseSettings.Schedule.On</code> method with an <code>IDateRule</code> object before you add the universe.
</p>

<div class="section-example-container">
    <pre class="csharp">UniverseSettings.Schedule.On(DateRules.MonthStart());
AddUniverse(Universe.DollarVolume.Top(50));</pre>
    <pre class="python">self.universe_settings.schedule.on(self.date_rules.month_start())
self.add_universe(self.universe.dollar_volume.top(50))</pre>
</div>

<? include(DOCS_RESOURCES."/scheduled-events/date-rules.html"); ?>

<p>In live trading, scheduled universes run at roughly 8 AM Eastern Time to ensure there is enough time for the data to process.</p>