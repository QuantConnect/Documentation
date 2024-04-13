<p>To update parameters in live mode, add a <a href="/docs/v2/writing-algorithms/scheduled-events">Schedule Event</a> that <a href="/docs/v2/writing-algorithms/importing-data/bulk-downloads">downloads</a> a remote file and uses its contents to update the parameter values.</p>
<div class="section-example-container">
    <pre class="csharp">private Dictionary<string, decimal> _parameters = new();
public override void Initialize()
{
    if (LiveMode)
    {
        Schedule.On(
            DateRules.EveryDay(),
            TimeRules.Every(TimeSpan.FromMinutes(1)),
            ()=>
            {
                var content = Download(urlToRemoteFile);
                // Convert content to _parameters
            });
    }
}</pre>
    <pre class="python">def initialize(self):
    self.parameters = { }
    if self.live_mode:
        def download_parameters():
            content = self.download(url_to_remote_file)
            # Convert content to self.parameters

        self.schedule.on(self.date_rules.every_day(), self.time_rules.every(timedelta(minutes=1)), download_parameters)
</pre>
</div>
