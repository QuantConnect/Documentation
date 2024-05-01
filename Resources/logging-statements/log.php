<p>Log statements are added to the log file while your algorithm continues executing. Logging dataset information is not permitted. Use <code class="csharp">Log</code><code class="python">log</code> method statements to debug your backtests and live trading algorithms.</p>

<p><?=$cloudPlatform ? "Log length " : "If you execute algorithms in QuantConnect Cloud, log length " ?> is <a href='/docs/v2/cloud-platform/organizations/resources#09-Log-Quotas'>capped by organization tier</a>. If your organization hits the daily limit, <a href='/contact'>contact us</a>.</p>

<p>If you log the same content multiple times, only the first instance is added to the log file. To bypass this rate-limit, add a timestamp to your log messages.</p>

<p>For live trading, the log files of each cloud project can store up to 100,000 lines for up to one year. If you log more than 100,000 lines or some lines become older than one year, we remove the oldest lines in the files so your project stays within the quota.</p>

<p>To record the algorithm state when the algorithm stops executing, add log statements to the <code class="csharp">OnEndOfAlgorithm</code><code class="python">on_end_of_algorithm</code> event handler.</p>

<div class='section-example-container'>
    <pre class='csharp'>Log("My log message");</pre>
    <pre class='python'>self.log("My log message")</pre>
</div>
