    <li>Enter your IB user name, ID, and password.</li>
    <? if ($localPlatform) { include(DOCS_RESOURCES."/brokerages/interactive-brokers/paper-trading-data-feeds.html"); } ?>
    <p>Your account details are not saved on QuantConnect.</p>
    <li>In the <span class="field-name">Weekly Restart UTC</span> field, enter the Coordinated Universal Time (UTC) time of when you want to receive notifications on Sundays to re-authenticate your account connection.</li>
    <p>For example, 4 PM UTC is equivalent to 11 AM Eastern Standard Time, 12 PM Eastern Daylight Time, 8 AM Pacific Standard Time, and 9 AM Pacific Daylight Time. To convert from UTC to a different time zone, see the <a rel='nofollow' target='_blank' href='https://www.utctime.net/utc-time-zone-converter'>UTC Time Zone Converter</a> on the UTC Time website.</p>
    <p>If your IB account has 2FA enabled, you receive a notification on your IB Key device every Sunday to re-authenticate the connection between IB and your live algorithm. If you don't re-authenticate before the timeout period, your algorithm quits executing.</p>
    <? if ($cloudPlatform) { 
        include(DOCS_RESOURCES."/brokerages/interactive-brokers/paper-trading-data-feeds.html"); 
    } 
    ?>
