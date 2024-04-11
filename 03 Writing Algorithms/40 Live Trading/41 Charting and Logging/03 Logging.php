<? include(DOCS_RESOURCES."/logging-statements/introduction.php"); ?>

<p>If you run algorithms on QuantConnect, you must stay within the <a href="/docs/v2/cloud-platform/organizations/resources#09-Log-Quotas">log quota</a>. To only log when your algorithm is live, use the <code>LiveMode</code> property.</p>

<div class='section-example-container'>
    <pre class='csharp'>if (LiveMode)
{
    Log("My log message");
}</pre>
    <pre class='python'>if self.live_mode:
    self.log("My log message")</pre>
</div>