<?php echo file_get_contents(DOCS_RESOURCES."/logging-statements/introduction.html"); ?>

<p>You can use the <code>LiveMode</code> property to add logging statements to your algorithm and avoid consuming the <a href="/docs/v2/our-platform/organizations/resources#09-Log-Quotas">backtest quota</a>.</p>

<div class='section-example-container'>
    <pre class='csharp'>if (LiveMode)
{
    Log("My log message");
}</pre>
    <pre class='python'>if self.LiveMode:
    self.Log("My log message")</pre>
</div>