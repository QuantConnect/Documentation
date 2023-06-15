    <li>Enter your IB user name, ID, and password.</li>
    <p>Your account details are not saved on QuantConnect.</p>
    <li>In the <span class="field-name">Weekly Restart UTC</span> field, enter the Coordinated Universal Time (UTC) time of when you want to receive notifications on Sundays to re-authenticate your account connection.</li>
    <p>For example, 4 PM UTC is equivalent to 11 AM Eastern Standard Time, 12 PM Eastern Daylight Time, 8 AM Pacific Standard Time, and 9 AM Pacific Daylight Time. To convert from UTC to a different time zone, see the <a rel='nofollow' target='_blank' href='https://www.utctime.net/utc-time-zone-converter'>UTC Time Zone Converter</a> on the UTC Time website.</p>
    <p>If your IB account has 2FA enabled, you receive a notification on your IB Key device every Sunday to re-authenticate the connection between IB and your live algorithm. If you don't re-authenticate before the timeout period, your algorithm quits executing.</p>
    <li>Click the <span class="field-name">Data Provider</span> field and then click one of the data feeds from the drop-down menu.</li>
    <p>The following table describes the available data feeds:</p>
    <table class="qc-table table">
        <thead>
            <tr>
                <th>Data Feed</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>QuantConnect</td>
                <td>Use data collected across all of the exchanges. For more details about this data feed, see <a href="/docs/v2/cloud-platform/live-trading/data-feeds">Data Feeds</a>.</td>
            </tr>
            <tr>
                <td>IB</td>
                <td>Use data sourced directly from IB. For more details about this data feed, see the <a href="/docs/v2/cloud-platform/live-trading/data-feeds/brokerage-data-feeds/interactive-brokers">IB data feed</a> guide.</td>
            </tr>
            <tr>
                <td>QuantConnect + IB</td>
                <td>Use a combination of the QuantConnect and IB data feeds. For more details about this option, see <a href="/docs/v2/cloud-platform/live-trading/data-feeds/brokerage-data-feeds/interactive-brokers#07-Hybrid-QuantConnect-Data-Feed">Hybrid QuantConnect Data Feed</a>.</td>
            </tr>
        </tbody>
    </table>

    <li>If you have subscriptions to <a href="/docs/v2/cloud-platform/live-trading/data-feeds/brokerage-data-feeds/interactive-brokers">IB data feeds</a> and want to use them, click the <span class="field-name">Data Provider</span> field and then click <span class="button-name">Interactive Brokers</span> from the drop-down menu.</li>
    <p>If you use IB data feeds and trade with a paper trading account, you need to share the data feed with your paper trading account. For instructions on sharing data feeds, see <a href="/docs/v2/cloud-platform/live-trading/brokerages/interactive-brokers#02-Account-Types">Account Types</a>.</p>