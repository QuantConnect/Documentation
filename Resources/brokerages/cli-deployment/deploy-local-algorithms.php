<?php
$getDeployLocalAlgorithmsText = function($brokerageName, $brokerageDetails, $dataFeedDetails, $supportsIQFeed) {

    echo "
        <p>Follow these steps to start local live trading with the {$brokerageName} brokerage:</p>
        <ol>
            <li>Open a terminal in the directory you ran <code>lean init</code> in.</li>
            <li>Run <code>lean live \"&lt;projectName&gt;\"</code> to start a live deployment wizard for the project in <span class='private-directory-name'>./&lt;projectName&gt;</span> and then enter the {$brokerageName} brokerage number.
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
Enter an option:</pre>
</div>
            </li>
    ";

    echo $brokerageDetails;

    if ($supportsIQFeed) {
        echo "<li>";
        if ($brokerageName != 'QuantConnect Paper Trading') {
            echo "If you are on a Windows computer, e";
        }
        else {
            echo "E";
        }    

        echo "nter the number of the data feed to use.
            <div class='cli section-example-container'>
<pre>$ lean live 'My Project'
Select a data feed:
";

        if ($brokerageName == 'QuantConnect Paper Trading') {
            echo "1) Custom data only\n";
        }
        else {
            echo "1) {$brokerageName}\n";
        }

        echo "2) IQFeed
Enter an option:</pre>
</div>
            </li>";
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

        <p>
            If you are using the <a href='/docs/v2/lean-cli/projects/gui'>local GUI</a>, you can make it possible to monitor and control the live deployment through there by providing the <code>--gui</code> flag in step 2.
        </p>
    ";
}

?>
