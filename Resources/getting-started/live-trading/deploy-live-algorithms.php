<? 
$openProjectLink = $cloudPlatform ? "/docs/v2/cloud-platform/projects/getting-started#02-View-All-Projects" : "/docs/v2/local-platform/projects/getting-started#04-Open-Projects";
$brokerageName = $cloudPlatform ? "Paper Trading" : "Interactive Brokers";
$liveTradingResultsPage = $cloudPlatform ? "<a href='/docs/v2/cloud-platform/live-trading/results'>live results page</a>" : "live results page";

if ($cloudPlatform) { 
?>
<p>You must have an available <a href="/docs/v2/cloud-platform/organizations/resources#04-Live-Trading-Nodes">live trading node</a> for each live trading algorithm you deploy.</p>

<p>Follow these steps to deploy a live paper trading algorithm:</p>
<? } else { ?>
<p>Follow these steps to deploy a live trading algorithm with the Interactive Brokers brokerage:</p>
<? } ?>

<ol>
    <li><a href="<?=$openProjectLink?>">Open the project</a> that you want to deploy.</li>
    <li>Click the <? if ($localPlatform) { ?><img class="inline-icon" src= "https://cdn.quantconnect.com/i/tu/local-platform-live-trading-icon.png" alt="Local deploy live icon"> / <? } ?><img class="inline-icon" src= "https://cdn.quantconnect.com/i/tu/deploy-live-icon.png" alt="Deploy live icon"> <span class="icon-name">Deploy Live</span> icon.</li>
    <li>On the Deploy Live page, click the <span class="field-name">Brokerage</span> field and then click <span class="button-name"><?=$brokerageName?></span> from the drop-down menu.</li>

    <? 
    if ($localPlatform) {  
        include(DOCS_RESOURCES."/brokerages/interactive-brokers/deploy-steps.php");
    } 
    ?>

    <li>Click the <span class="field-name">Node</span> field and then click the live trading node that you want to use from the drop-down menu.</li>

    <? if ($cloudPlatform) {  ?>
    <li><span class="qualifier">(Optional)</span> Follow these steps to start the algorithm with existing cash holdings  (<a href='https://vimeo.com/703024505' rel='nofollow' target="_blank">see video</a>):</li>
    <ol>
        <li>In the <span class="page-section-name">Algorithm Cash State</span> section, click <span class="button-name">Show</span>.</li>
        <li>Click <span class="button-name">Add Currency</span>.</li>
        <li>Enter the currency ticker (for example, USD or BTC) and a quantity.</li>
    </ol>
    <li><span class="qualifier">(Optional)</span> Follow these steps to start the algorithm with existing position holdings  (<a href='https://vimeo.com/703024505' rel='nofollow' target="_blank">see video</a>):</li>
    <ol>
        <li>In the <span class="page-section-name">Algorithm Holdings State</span> section, click <span class="button-name">Show</span>.</li>
        <li>Click <span class="button-name">Add Holding</span>.</li>
        <li>Enter the symbol ID, symbol, quantity, and average price.</li>
    </ol>
    <li><span class="qualifier">(Optional)</span> <a href="/docs/v2/cloud-platform/live-trading/notifications">Set up notifications</a>.</li>
    <? } else { ?>
    <li><span class="qualifier">(Optional)</span> If you are deploying to QuantConnect Cloud, <a href="/docs/v2/cloud-platform/live-trading/notifications">set up notifications</a>.</li>
    <? } ?>

    <li>Configure the <span class="box-name">Automatically restart algorithm</span> setting.</li>
    <p>By enabling automatic restarts, the algorithm will use best efforts to restart the algorithm if it fails due to a runtime error. This can help improve the algorithm's resilience to temporary outages such as a brokerage API disconnection.</p>
    <li>Click <span class="button-name">Deploy</span>.</li>
</ol>

<p>The deployment process can take up to 5 minutes. When the algorithm deploys, the <?=$liveTradingResultsPage?> displays. If you know your brokerage positions before you deployed, you can verify they have been loaded properly by checking your equity value in the runtime statistics, your cashbook holdings, and your position holdings.</p>
