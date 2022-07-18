<?php
$getDeployLocalAlgorithmsText = function($brokerageName, $dataFeedName, $isBrokerage, $brokerageDetails, $dataFeedDetails, $supportsIQFeed, $requiresSubscription, $moduleName="") {

    echo "<p>Follow these steps to start local live trading with the ";

    if ($isBrokerage)
    {
    	echo "{$brokerageName} brokerage:</p>";
    }
    else
    {
    	echo "{$dataFeedName} data feed:</p>";
    }

    $word = $isBrokerage ? 'the' : 'a';
    $brokeragePromptInput = $isBrokerage ? '' : '1';
    echo "
        <ol>
            <li>Open a terminal in your <a href='/docs/v2/lean-cli/initialization/directory-structure#02-lean-init'>CLI root directory</a>.</li>
            <li>Run <code>lean live \"&lt;projectName&gt;\"</code> to start a live deployment wizard for the project in <span class='private-directory-name'>./&lt;projectName&gt;</span> and then enter {$word} brokerage number.
            <div class='cli section-example-container'>
<pre>$ lean live 'My Project'
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
14) FTX 
Enter an option: {$brokeragePromptInput}</pre>
</div>
            </li>
    ";

    if ($requiresSubscription) {
        $module = $moduleName == "" ? $brokerageName : $moduleName;
        echo "
        <li>Enter the number of the organization that has a subscription for the {$module} module.
        <div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Select the organization with the {$module} module subscription:
1) Organization 1
2) Organization 2
3) Organization 3
Enter an option: 1</pre>
</div>
</li>";
    }

    echo $brokerageDetails;

    echo "<li>Enter the number of the data feed to use and then follow the steps required for the data connection.
            <div class='cli section-example-container'>
<pre>$ lean live 'My Project'
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
10) Trading Technologies
11) Kraken
12) FTX
13) IQFeed
14) Polygon Data Feed
15) Custom data only
To enter multiple options, separate them with comma.:</pre>
            </div>
            </li>";
    if ($isBrokerage)
    {
        echo "<p>If you select IQFeed, see <a href='/docs/v2/lean-cli/live-trading/other-data-feeds/iqfeed'>IQFeed</a> for set up instructions.</p>";
    }

    echo $dataFeedDetails;

    echo "
            <li>
                View the result in the <span class='private-directory-name'>&lt;projectName&gt;/live/&lt;timestamp&gt;</span> directory.
                Results are stored in real-time in JSON format.
                You can save results to a different directory by providing the <code>--output &lt;path&gt;</code> option in step 2.
            </li>
        </ol>
        <p>
            If you already have a live environment configured in your <a href='/docs/v2/lean-cli/initialization/configuration#03-Lean-Configuration'>Lean configuration file</a>, you can skip the interactive wizard by providing the <code>--environment &lt;value&gt;</code> option in step 2.
            The value of this option must be the name of an environment which has <code>live-mode</code> set to <code>true</code>.
        </p>
    ";
}
?>
