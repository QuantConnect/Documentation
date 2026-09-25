<p>
    Follow these steps to start local live trading with the <?=$isBrokerage ? "{$brokerageName} brokerage" : "{$dataFeedName} data provider" ?>:
</p>

<ol>
    <li><a href='/docs/v2/lean-cli/initialization/authentication#02-Log-In'>Log in</a> to the CLI if you haven't done so already.</li>        
    <li>Open a terminal in the <a href='/docs/v2/lean-cli/initialization/organization-workspaces'>organization workspace</a> that contains the project.</li>
    <li>If your algorithm trades US Equities, Options, or Futures, <a href='/docs/v2/lean-cli/datasets/quantconnect'>purchase and download</a> the QuantConnect datasets that LEAN uses for the asset class. Crypto, Forex, and CFD algorithms can skip this step. The following table shows the datasets for each asset class:
    <table class='qc-table table'>
        <thead>
            <tr>
                <th>Asset Class</th>
                <th>Datasets</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>US Equity</td>
                <td><a href='https://www.quantconnect.com/datasets/quantconnect-us-equity-security-master/cli'>US Equity Security Master</a>, to get adjusted prices. Without it, your algorithm uses raw prices.</td>
            </tr>
            <tr>
                <td>US Equity Options</td>
                <td><a href='https://www.quantconnect.com/datasets/quantconnect-us-equity-security-master/cli'>US Equity Security Master</a> and <a href='https://www.quantconnect.com/datasets/quantconnect-us-equity-option-universe'>US Equity Option Universe</a></td>
            </tr>
            <tr>
                <td>US Index Options</td>
                <td><a href='https://www.quantconnect.com/datasets/quantconnect-us-index-option-universe'>US Index Option Universe</a></td>
            </tr>
            <tr>
                <td>US Futures</td>
                <td><a href='https://www.quantconnect.com/datasets/quantconnect-us-futures-security-master/cli'>US Futures Security Master</a> and <a href='https://www.quantconnect.com/datasets/quantconnect-us-future-universe'>US Future Universe</a></td>
            </tr>
            <tr>
                <td>US Future Options</td>
                <td><a href='https://www.quantconnect.com/datasets/quantconnect-us-futures-security-master/cli'>US Futures Security Master</a>, <a href='https://www.quantconnect.com/datasets/quantconnect-us-future-universe'>US Future Universe</a>, and <a href='https://www.quantconnect.com/datasets/quantconnect-us-future-option-universe'>US Future Option Universe</a>. To get access, <a href='https://www.quantconnect.com/contact'>contact us</a>.</td>
            </tr>
        </tbody>
    </table>
    If your algorithm uses <a href='https://www.quantconnect.com/docs/v2/writing-algorithms/universes/key-concepts'>universe selection</a>, also download the universe dataset, such as the <a href='/docs/v2/lean-cli/datasets/quantconnect/us-equity-coarse-fundamental'>US Equity Coarse Universe</a> or <a href='/docs/v2/lean-cli/datasets/quantconnect/us-etf-constituents'>US ETF Constituents</a>.
    </li>

<?
if ($isBrokerage) {
  $brokerages = array(
      "QuantConnect Paper Trading",
      "Interactive Brokers",
      "Tradier",
      "Oanda",
      "Bitfinex",
      "Coinbase Advanced Trade",
      "Binance",
      "Zerodha",
      "Samco",
      "Terminal Link",
      "Trading Technologies",
      "Kraken",
      "Charles Schwab",
      "Bybit",
      "TradeStation",
      "Alpaca",
      "Tastytrade",
      "Eze",
      "dYdX",
      "Webull",
      "Public",
      "Clear Street"
  );
  $brokerageNumber = array_search($brokerageName, $brokerages) + 1;
}

$dataProviders = array(
    "Interactive Brokers",
    "Tradier",
    "Oanda",
    "Bitfinex",
    "Coinbase Advanced Trade",
    "Binance",
    "Zerodha",
    "Samco",
    "Terminal Link",
    "Trading Technologies",
    "Kraken",
    "Charles Schwab",
    "IQFeed",
    "Polygon",
    "CoinApi",
    "ThetaData",
    "Custom data only",
    "Bybit",
    "TradeStation",
    "Alpaca",
    "Tastytrade",
    "Eze",
    "dYdX",
    "DataBento"
);
$dataProviderNumber = isset($dataProviderName) ? array_search($dataProviderName, $dataProviders) + 1 : -1;
?>
    
    <li>Run <code>lean live deploy "&lt;projectName&gt;"</code> to start a live deployment wizard for the project in <span class='public-directory-name'>. / &lt;projectName&gt;</span> and then enter <? if ($isBrokerage) { ?> the brokerage number, <span class='key-combinations'><?=$brokerageNumber?></span><? } else { ?>a brokerage number<? } ?>.
    <div class='cli section-example-container'>
<pre>$ lean live deploy "My Project"
Select a brokerage:
1) Paper Trading
2) Interactive Brokers
3) Tradier
4) OANDA
5) Bitfinex
6) Coinbase Advanced Trade
7) Binance
8) Zerodha
9) Samco
10) Terminal Link
11) Trading Technologies
12) Kraken
13) Charles Schwab
14) Bybit
15) TradeStation
16) Alpaca
17) Tastytrade
18) Eze
19) dYdX
20) Webull
21) Public
22) Clear Street
</div>
</li>

<?=$brokerageDetails ?>

<? if (isset($supportsCashHoldings) && $supportsCashHoldings) { ?> 
    <li>Set your initial cash balance.
        <div class='cli section-example-container'>
        <pre>$ lean live deploy "My Project"
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
    
<? if (isset($supportedPositionHoldings) && $supportedPositionHoldings) { ?>
    <li>Set your initial portfolio holdings.
        <div class='cli section-example-container'>
        <pre>$ lean live deploy "My Project"
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

<?
if ($isBrokerage && $brokerageName == "Terminal Link") {
?>
<li>Enter <span class='key-combinations'>9</span> to select the Terminal Link live data provider.
<div class='cli section-example-container'>
<pre>$ lean live deploy "My Project"
Select a live data provider:
1) Interactive Brokers
2) Tradier
3) Oanda
4) Bitfinex
5) Coinbase Advanced Trade
6) Binance
7) Zerodha
8) Samco
9) Terminal Link
10) Trading Technologies
11) Kraken
12) Charles Schwab
13) IQFeed
14) Polygon
15) CoinApi
16) ThetaData
17) Custom data only
18) Bybit
19) TradeStation
20) Alpaca
21) Tastytrade
22) Eze
23) dYdX
24) DataBento
To enter multiple options, separate them with comma: 9</pre>
</div>
</li>   
<?  
} else if (isset($dataProviderName)) {
?>
            <li>Enter <span class='key-combinations'><?=$dataProviderNumber?></span> to select the <?=$dataProviderName?> data provider.</li> 
            <div class='cli section-example-container'>
<pre>$ lean live deploy "My Project"
Select a live data feed:
1) Interactive Brokers
2) Tradier
3) Oanda
4) Bitfinex
5) Coinbase Advanced Trade
6) Binance
7) Zerodha
8) Samco
9) Terminal Link
10) Trading Technologies
11) Kraken
12) Charles Schwab
13) IQFeed
14) Polygon
15) CoinApi
16) ThetaData
17) Custom data only
18) Bybit
19) TradeStation
20) Alpaca
21) Tastytrade
22) Eze
23) dYdX
24) DataBento
To enter multiple options, separate them with comma: <?=$dataProviderNumber?></pre>
            </div>
            </li>
<?
    echo $dataProviderDetails;
} else {
?><li>Enter the number of the live data provider(s) to use and then follow the steps required for the data connection.
<div class='cli section-example-container'>
<pre>$ lean live deploy "My Project"
Select a live data provider:
1) Interactive Brokers
2) Tradier
3) Oanda
4) Bitfinex
5) Coinbase Advanced Trade
6) Binance
7) Zerodha
8) Samco
9) Terminal Link
10) Trading Technologies
11) Kraken
12) Charles Schwab
13) IQFeed
14) Polygon
15) CoinApi
16) ThetaData
17) Custom data only
18) Bybit
19) TradeStation
20) Alpaca
21) Tastytrade
22) Eze
23) dYdX
24) DataBento
To enter multiple options, separate them with comma:</pre>
</div>
</li>
    <?if ($isBrokerage) {?>
    <p>If you select one of the following data providers, see the respective page for more instructions:</p>
    <ul>
        <li><a href='/docs/v2/lean-cli/live-trading/data-providers/iqfeed'>IQFeed</a></li>
        <li><a href='/docs/v2/lean-cli/live-trading/data-providers/polygon'>Polygon</a></li>
        <li><a href='/docs/v2/lean-cli/live-trading/data-providers/theta-data'>Theta Data</a></li>
    </ul>
    <? } ?>
<? } ?>


<?=$dataFeedDetails ?>
     
    <li>
        View the result in the <span class='public-directory-name'>&lt;projectName&gt; / live / &lt;timestamp&gt;</span> directory.
        Results are stored in real-time in JSON format.
        You can save results to a different directory by providing the <code>--output &lt;path&gt;</code> option in step 4.
    </li>
</ol>
<p>
    If you already have a live environment configured in your <a href='/docs/v2/lean-cli/initialization/configuration#03-Lean-Configuration'>Lean configuration file</a>, you can skip the interactive wizard by providing the <code>--environment &lt;value&gt;</code> option in step 4.
    The value of this option must be the name of an environment which has <code>live-mode</code> set to <code>true</code>.
</p>