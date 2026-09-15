<p>Deployment details are the key-value pairs that your brokerage shares about the live deployment, like the account it trades. The keys depend on the brokerage. To see which details a brokerage shares, see the Deployment Details section of its <a href='/docs/v2/cloud-platform/live-trading/brokerages'>brokerage documentation</a>. The dictionary is empty in backtests because no brokerage is connected.</p>

<? include(DOCS_RESOURCES."/live-trading/read-deployment-details.php"); ?>

<p>To read a single detail, use its key.</p>

<div class="section-example-container">
<pre class="csharp">if (DeploymentDetails.TryGetValue("ib-account", out var account))
{
    Log($"Trading account: {account}");
}</pre>
<pre class="python">account = self.deployment_details.get('ib-account')
if account:
    self.log(f'Trading account: {account}')</pre>
</div>
