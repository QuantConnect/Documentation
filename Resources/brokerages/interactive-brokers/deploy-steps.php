    <li>Enter your IB user name, ID, and password.</li>
    <? if ($localPlatform) { include(DOCS_RESOURCES."/brokerages/interactive-brokers/paper-trading-data-feeds.html"); } ?>
    <p>Your account details are not saved on QuantConnect.</p>
    <li>In the <span class="field-name">Weekly Restart UTC</span> field, enter the Coordinated Universal Time (UTC) time of when you want to receive notifications on Sundays to re-authenticate your account connection.</li>
    <p>For example, 4 PM UTC is equivalent to 11 AM Eastern Standard Time, 12 PM Eastern Daylight Time, 8 AM Pacific Standard Time, and 9 AM Pacific Daylight Time. To convert from UTC to a different time zone, see the <a rel='nofollow' target='_blank' href='https://www.utctime.net/utc-time-zone-converter'>UTC Time Zone Converter</a> on the UTC Time website.</p>
    <p>If your IB account has 2FA enabled, you receive a notification on your IB Key device every Sunday to re-authenticate the connection between IB and your live algorithm. If you don't re-authenticate before the timeout period, your algorithm quits executing.</p>
    <? if ($cloudPlatform) { ?>
    <li>Click the <span class="field-name">Data Provider</span> field and then click one of the data providers from the drop-down menu.</li>
    <p>The following table describes the available data providers:</p>
    <table class="qc-table table">
        <thead>
            <tr>
                <th>Data Provider</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>QuantConnect</td>
                <td>Use data collected across all of the exchanges. For more details about this data provider, see <a href="/docs/v2/cloud-platform/live-trading/data-providers">Data Providers</a>.</td>
            </tr>
            <tr>
                <td>IB</td>
                <td>Use data sourced directly from IB. For more details about this data provider, see the <a href="/docs/v2/cloud-platform/live-trading/data-providers/interactive-brokers">IB data provider</a> guide.</td>
            </tr>
            <tr>
                <td>QuantConnect + IB</td>
                <td>Use a combination of the QuantConnect and IB data providers. For more details about this option, see <a href="/docs/v2/cloud-platform/live-trading/data-proviers/interactive-brokers#07-Hybrid-QuantConnect-Data-Provider">Hybrid QuantConnect Data Provider</a>.</td>
            </tr>
        </tbody>
    </table>

    <? 
        include(DOCS_RESOURCES."/brokerages/interactive-brokers/paper-trading-data-feeds.html"); 
    } 
    ?>
