 <? if (!$isSupported) { ?> <p>The CLI doesn't currently support deploying cloud algorithms with <?=$brokerageName?>. <? return; } ?>

    <p>Follow these steps to start live trading a project in the cloud with the <?=$brokerageName?> brokerage:</p>
        <ol>
            <li><a href='/docs/v2/lean-cli/initialization/authentication#02-Log-In'>Log in</a> to the CLI if you haven't done so already.</li>
            <li>Open a terminal in the <a href='/docs/v2/lean-cli/initialization/organization-workspaces'>organization workspace</a> that contains the project.</li>
            <li>Run <code>lean cloud live "&lt;projectName&gt;" --push --open</code> to push <span class='public-directory-name'>. / &lt;projectName&gt;</span>. to the cloud, start a live deployment wizard, and open the results in the browser once the deployment starts.
<div class='cli section-example-container'>
<pre>$ lean cloud live "My Project" --push --open
[1/1] Pushing 'My Project'
Successfully updated cloud file 'My Project/main.py'
Started compiling project 'My Project'
Successfully compiled project 'My Project'</pre>
</div>
            </li>

<?
$brokerages = array(
      "QuantConnect Paper Trading",
      "Interactive Brokers",
      "Tradier",
      "Oanda",
      "Bitfinex",
      "Coinbase",
      "Binance",
      "Zerodha",
      "Samco",
      "Terminal Link",
      "Trading Technologies",
      "Kraken",
      "TD Ameritrade"
);
$brokerageNumber = array_search($brokerageName, $brokerages) + 1;
?>
         
            <li>Enter <span class='key-combinations'><?=$brokerageNumber?></span> to select the <?=$brokerageName?> brokerage.
<div class='cli section-example-container'>
<pre>$ lean cloud live "My Project" --push --open
Select a brokerage:
1) Paper Trading
2) Interactive Brokers
3) Tradier
4) Oanda
5) Bitfinex
6) Coinbase Pro
7) Binance
8) Zerodha
9) Samco
10) Terminal Link
11) Trading Technologies
12) Kraken
13) TD Ameritrade
Enter an option: <?=$brokerageNumber?></pre>
</div>
            </li>

    <?=$brokerageDetails?>

            <li>Select the live node that you want to use. If you only have one idle live trading node, it is selected automatically and this step is skipped.
<div class='cli section-example-container'>
<pre>$ lean cloud live "My Project" --push --open
Select a node:
1) L-MICRO node 89c90172 - 1 CPU @ 2.4GHz, 0.5GB Ram
2) L-MICRO node 85a52135 - 1 CPU @ 2.4GHz, 0.5GB Ram
Enter an option: 1</pre>
</div>
            </li>

            <li>Configure your notification settings. You can configure any combination of email, webhook, SMS, and Telegram notifications for order events and emitted insights.  To view the number of notification you can send for free, see the <a href='/docs/v2/cloud-platform/organizations/resources#12-Live-Trading-Notification-Quotas'>Live Trading Notification Quotas</a>.
            <div class='cli section-example-container'>
<pre>$ lean cloud live "My Project" --push --open
Do you want to send notifications on order events? [y/N]: y
Do you want to send notifications on insights? [y/N]: y
Email notifications: None
Webhook notifications: None
SMS notifications: None
Select a notification method:
1) Email
2) Webhook
3) SMS
4) Telegram
Enter an option: 1
Email address: john.doe@example.com
Subject: Algorithm notification
Email notifications: john.doe@example.com
Webhook notifications: None
SMS notifications: None
Telegram notifications: None
Do you want to add another notification method? [y/N]: n</pre>
</div>
            </li>

            <li>Enable or disable automatic algorithm restarting. This feature attempts to restart your algorithm if it fails due to a runtime error, like a brokerage API disconnection.
            <div class='cli section-example-container'>
<pre>$ lean cloud live "My Project" --push --open
Do you want to enable automatic algorithm restarting? [Y/n]: y</pre>
</div>
            </li>
    
 <? if (!$supportsCashHoldings) { ?> <li>Set your initial cash balance.
            <div class='cli section-example-container'>
<pre>$ lean cloud live "My Project" --push --open
Previous cash balance: [{'currency': 'USD', 'amount': 100000.0}]
Do you want to set a different initial cash balance? [y/N]: y
Setting initial cash balance...
Currency: USD
Amount: 95800
Cash balance: [{'currency': 'USD', 'amount': 95800.0}]
Do you want to add more currency? [y/N]: n</pre>
</div></li> <? } ?>

 <? if (!$supportsPositionHoldings) { ?> <li>Set your initial portfolio holdings.
            <div class='cli section-example-container'>
<pre>$ lean cloud live "My Project" --push --open
Do you want to set the initial portfolio holdings? [y/N]: y
Do you want to use the last portfolio holdings? [] [y/N]: n
Setting custom initial portfolio holdings...
Symbol: GOOG
Symbol ID: GOOCV VP83T1ZUHROL
Quantity: 10
Average Price: 50
Portfolio Holdings: [{'symbol': 'GOOG', 'symbolId': 'GOOCV VP83T1ZUHROL', 'quantity': 10, 'averagePrice': 50.0}]
Do you want to add more holdings? [y/N]: n</pre>
</div></li> <? } ?> 
       
            <li>Verify the configured settings and confirm them to start the live deployment in the cloud.
            <div class='cli section-example-container'>
<pre>$ lean cloud live "My Project" --push --open
Brokerage: <?=$brokerageName?>
Project id: 1234567
Environment: Live
Server name: L-MICRO node 89c90172
Server type: L-MICRO
Data provider: QuantConnect
LEAN version: 11157
Order event notifications: Yes
Insight notifications: Yes
Email notifications: john.doe@example.com
Webhook notifications: None
SMS notifications: None
Telegram notifications: None
    
<? if (!$supportsCashHoldings) { ?> Initial live cash balance: [{'currency': 'USD', 'amount': 95800.0}] <? } ?>
    
<? if (!$supportsPositionHoldings) { ?> Initial live portfolio holdings: [{'symbol': 'GOOG', 'symbolId': 'GOOCV VP83T1ZUHROL', 'quantity': 10, 'averagePrice': 50.0}] <? } ?>

Automatic algorithm restarting: Yes
Are you sure you want to start live trading for project 'My Project'? [y/N]: y</pre>
</div>
            </li>
            <li>Inspect the result in the browser, which opens automatically after the deployment starts.</li>
        </ol>


        <p>Follow these steps to see the live status of a project:</p>

        <ol>
            <li><a href='/docs/v2/lean-cli/initialization/authentication#02-Log-In'>Log in</a> to the CLI if you haven't done so already.</li>
            <li>Open a terminal in the <a href='/docs/v2/lean-cli/initialization/organization-workspaces'>organization workspace</a> that contains the project.</li>
            <li>Run <code>lean cloud status "&lt;projectName&gt;"</code> to show the status of the cloud project named "&lt;projectName&gt;".
            <div class='cli section-example-container'>
<pre>$ lean cloud status "My Project"
Project id: 1234567
Project name: My Project
Project url: https://www.quantconnect.com/project/1234567
Live status: Running
Live id: L-1234567a8901d234e5e678ddd9b0123c
Live url: https://www.quantconnect.com/project/1234567/live
Brokerage: <?=$brokerageName?>
Launched: 2021-06-09 15:10:12 UTC</pre>
            </div>
            </li>
        </ol>
