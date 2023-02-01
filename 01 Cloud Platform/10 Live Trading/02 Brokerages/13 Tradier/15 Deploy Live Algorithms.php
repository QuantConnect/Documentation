<p>You must have an available <a href="/docs/v2/cloud-platform/organizations/resources#04-Live-Trading-Nodes">live trading node</a> for each live trading algorithm you deploy.</p>

<p>Follow these steps to deploy a live algorithm:</p>

<ol>
    <li><a href="/docs/v2/cloud-platform/projects/getting-started#02-View-All-Projects">Open the project</a> that you want to deploy.</li>
    <li>Click the <img class="inline-icon" src="https://cdn.quantconnect.com/i/tu/deploy-live-icon.png"> <span class="icon-name">Deploy Live</span> icon.</li>
    <li>On the Deploy Live page, click the <span class="field-name">Brokerage</span> field and then click <span class="button-name">Tradier</span> from the drop-down menu.</li>
    <li>Enter your Tradier account Id and token.</li>
    <?php echo file_get_contents(DOCS_RESOURCES."/brokerages/create-credentials/tradier.html"); ?>
    <li>Click the <span class="field-name">Environment</span> field and then click one of the environments from the drop-down menu.</li>

    <p>The following table shows the supported environments:</p>

    <table class="table qc-table">
        <thead>
            <tr>
                <th style="width: 25%">Environment</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Real</td>
                <td>Trade with real money</td>
            </tr>
            <tr>
                <td>Demo</td>
                <td>Trade with paper money</td>
            </tr>
        </tbody>
    </table>

    <li>Click the <span class="field-name">Data Provider</span> field and then click one of the data feeds from the drop-down menu.</li>
    <p>The following table describes the available data feeds:</p>
    <table class="qc-table table">
        <thead>
            <tr>
                <th style="width: 25%">Data Feed</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>QuantConnect</td>
                <td>Use data collected across all of the exchanges. For more details about this data feed, see <a href="/docs/v2/cloud-platform/live-trading/data-feeds">Data Feeds</a>.</td>
            </tr>
            <tr>
                <td>Tradier</td>
                <td>Use data sourced directly from Tradier. This data feed isn't available for the demo environment. For more details about this data feed, see the <a href="/docs/v2/cloud-platform/live-trading/data-feeds/brokerage-data-feeds/tradier">Tradier data feed</a> guide.</td>
            </tr>
        </tbody>
    </table>

    <li>Click the <span class="field-name">Node</span> field and then click the live trading node that you want to use from the drop-down menu.</li>
    <li><span class="qualifier">(Optional)</span> <a href="/docs/v2/cloud-platform/live-trading/notifications">Set up notifications</a>.</li>
    <li>Configure the <span class="box-name">Automatically restart algorithm</span> setting.</li>
    <p>By enabling automatic restarts, the algorithm will use best efforts to restart the algorithm if it fails due to a runtime error. This can help improve the algorithm's resilience to temporary outages such as a brokerage API disconnection.</p>
    <li>Click <span class="button-name">Deploy</span>.</li>
</ol>

<p>The deployment process can take up to 5 minutes. When the algorithm deploys, the <a href="/docs/v2/cloud-platform/live-trading/results">live results page</a> displays. If you know your brokerage positions before you deployed, you can verify they have been loaded properly by checking your equity value in the runtime statistics, your cashbook holdings, and your position holdings.</p>
