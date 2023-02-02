<p>To update parameters in live mode, the algorithm can implement <a href="/docs/v2/writing-algorithms/scheduled-events">schedule events</a> that <a href="/docs/v2/writing-algorithms/importing-data/bulk-downloads">downloads</a> dynamic data from a remote file:</p>
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
    <pre class="python">def Initialize(self):
    self.parameters = { }
    if self.LiveMode:
        def download_parameters():
            content = self.Download(url_to_remote_file)
            # Convert content to self.parameters

        self.Schedule.On(
            self.DateRules.EveryDay(),
            self.TimeRules.Every(timedelta(minutes=1)),
            download_parameters)</pre>
</div>