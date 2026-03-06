<p>You must have an available <a href="/docs/v2/cloud-platform/organizations/resources#04-Live-Trading-Nodes">live trading node</a> for each live trading algorithm you deploy.</p>

<p>Follow these steps to deploy a live trading algorithm that uses the Polygon data provider:</p>

<ol>
    <li><a href="/docs/v2/cloud-platform/projects/getting-started#02-View-All-Projects">Open the project</a> that you want to deploy.</li>
    <li>Click the <img class="inline-icon" src= "https://cdn.quantconnect.com/i/tu/deploy-live-icon.png" alt="Deploy live icon"> <span class="icon-name">Deploy Live</span> icon.</li>
    <li>On the Deploy Live page, click the <span class="field-name">Brokerage</span> field and then click your brokerage from the drop-down menu.</li>
    <li>Enter the required brokerage authentication information.</li>
    <p>For more information about the required information for each brokerage, see the <span class='page-section-name'>Deploy Live Algorithms</span> section of your <a href='/docs/v2/cloud-platform/live-trading/brokerages'>brokerage documentation</a>.</p>
	
    <li>In the <span class='page-section-name'>Data Provider</span> section of the deployment wizard, click <span class='button-name'>Show</span>.</li>
    <li>Click the <span class='field-name'>Data Provider 1</span> field and then click <span class='button-name'>Polygon</span> from the drop-down menu.</li>
    <li>Enter your Polygon API Key.</li>
    <img class='docs-image' src='https://cdn.quantconnect.com/i/tu/polygon-data-provider.png' alt='Polygon selected as the data provider in the deployment wizard'>
    <li>Click <span class='button-name'>Save</span>.</li>
	

    <li><span class="qualifier">(Optional)</span> If your brokerage supports exisiting <a href='https://vimeo.com/703024505' rel='nofollow' target="_blank">cash</a> and <a href='https://vimeo.com/703024505' rel='nofollow' target="_blank">position holdings</a>, add them.</li>
    <li><span class="qualifier">(Optional)</span> <a href="/docs/v2/cloud-platform/live-trading/notifications">Set up notifications</a>.</li>

    <li>Configure the <span class="box-name">Automatically restart algorithm</span> setting.</li>
    <p>By enabling <a href="/docs/v2/cloud-platform/live-trading/deployment#07-Automatic-Restarts">automatic restarts</a>, the algorithm will use best efforts to restart the algorithm if it fails due to a runtime error. This can help improve the algorithm's resilience to temporary outages such as a brokerage API disconnection.</p>
    <li>Click <span class="button-name">Deploy</span>.</li>
</ol>
