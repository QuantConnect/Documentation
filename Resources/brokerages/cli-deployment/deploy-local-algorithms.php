<p>
    Follow these steps to start local live trading with the <?=($isBrokerage) ? "{$brokerageName} brokerage" : "{$dataFeedName} data feed" ?>:
</p>

<ol>
    <li>Open a terminal in the <a href='/docs/v2/lean-cli/initialization/organization-workspaces'>organization workspace</a> that contains the project.</li>
    <li>Run <code>lean live "&lt;projectName&gt;"</code> to start a live deployment wizard for the project in <span class='public-directory-name'>. / &lt;projectName&gt;</span> and then enter <?=$isBrokerage ? 'the' : 'a'?> brokerage number.
    <div class='cli section-example-container'>
<pre>$ lean live "My Project"
Select a brokerage:
1) Paper Trading
2) Interactive Brokers
3) Tradier
4) OANDA
5) Bitfinex
6) Coinbase Pro
7) Binance
8) Zerodha
9) Samco
10) Terminal Link
11) Atreyu
12) Trading Technologies
13) Kraken
14) TD Ameritrade
Enter an option: <?=$isBrokerage ? '' : '1'?></pre>
</div>
</li>

<?=$brokerageDetails ?>

<li>Enter the number of the data feed to use and then follow the steps required for the data connection.
<div class='cli section-example-container'>
<pre>$ lean live "My Project"
Select a data feed:
1) Interactive Brokers
2) Tradier
3) Oanda
4) Bitfinex
5) Coinbase Pro
6) Binance
7) Zerodha
8) Samco
9) Terminal Link
10) Kraken
11) TD Ameritrade
12) IQFeed
13) Polygon Data Feed
14) Custom data only
To enter multiple options, separate them with comma.:</pre>
</div>
</li>

<?if ($isBrokerage) {?>
<p>If you select IQFeed, see <a href='/docs/v2/lean-cli/live-trading/data-feeds/iqfeed'>IQFeed</a> for set up instructions.</p>
<p>If you select Polygon Data Feed, see <a href='/docs/v2/lean-cli/live-trading/data-feeds/polygon'>Polygon</a> for set up instructions.</p>
<? } ?>

<?=$dataFeedDetails ?>

<? if ($supportsCashHoldings) { ?> 
    <li>Set your initial cash balance.
        <div class='cli section-example-container'>
        <pre>$ lean live "My Project"
Previous cash balance: [{'currency': 'USD', 'amount': 100000.0}]
Do you want to set a different initial cash balance? [y/N]: y 
Setting initial cash balance...
Currency: USD
Amount: 95800
Cash balance: [{'currency': 'USD', 'amount': 95800.0}]
Do you want to add more currency? [y/N]: n</pre>
        </div>
    </li> 
<? } ?> 
    
<? if ($supportedPositionHoldings) { ?>
    <li>Set your initial portfolio holdings.
        <div class='cli section-example-container'>
        <pre>$ lean live "My Project"
Do you want to set the initial portfolio holdings? [y/N]: y
Do you want to use the last portfolio holdings? [] [y/N]: n
Setting custom initial portfolio holdings...
Symbol: GOOG
Symbol ID: GOOCV VP83T1ZUHROL
Quantity: 10
Average Price: 50
Portfolio Holdings: [{'symbol': 'GOOG', 'symbolId': 'GOOCV VP83T1ZUHROL', 'quantity': 10, 'averagePrice': 50.0}]
Do you want to add more holdings? [y/N]: n</pre>
        </div>
        </li>
<? } ?>
     
    <li>
        View the result in the <span class='public-directory-name'>&lt;projectName&gt; / live / &lt;timestamp&gt;</span> directory.
        Results are stored in real-time in JSON format.
        You can save results to a different directory by providing the <code>--output &lt;path&gt;</code> option in step 2.
    </li>
</ol>
<p>
    If you already have a live environment configured in your <a href='/docs/v2/lean-cli/initialization/configuration#03-Lean-Configuration'>Lean configuration file</a>, you can skip the interactive wizard by providing the <code>--environment &lt;value&gt;</code> option in step 2.
    The value of this option must be the name of an environment which has <code>live-mode</code> set to <code>true</code>.
</p>
 
