<p>The local platform allowed live trading with cloud live-trading node or local machine.</p>

<h4>Cloud Live-trading node</h4>
<?php echo file_get_contents(DOCS_RESOURCES."/getting-started/live-trading/deploy-live-algorithms.php"); ?>

<h4>Local Machine</h4>
<?php echo file_get_contents(DOCS_RESOURCES."/getting-started/live-trading/local-deployment-target.html"); ?>
<p>Then, follow the below steps to deploy live algorithm with your local machine.</p>
<ol>
    <li>Click the <img class="inline-icon" src= "https://cdn.quantconnect.com/i/tu/live-local.svg" alt="Local deploy live icon"> <span class="icon-name">Deploy Live</span> icon.</li>
    <li>On the Deploy Live page, click the <span class="field-name">Brokerage</span> field and select the brokerage you wish to trade with from the drop-down menu.</li>
    <li>Click the <span class="field-name">Node</span> field and then click the live trading node that you want to use from the drop-down menu.</li>
    <li><span class="qualifier">(Optional)</span> <a href="/docs/v2/<?php if ($cloudPlatform) { ?>cloud<? } elseif ($localPlatform) { ?>local<? } ?>-platform/live-trading/notifications">Set up notifications</a>.</li>
    <li>Configure the <span class="box-name">Automatically restart algorithm</span> setting.</li>
    <p>By enabling automatic restarts, the algorithm will use best efforts to restart the algorithm if it fails due to a runtime error. This can help improve the algorithm's resilience to temporary outages such as a brokerage API disconnection.</p>
    <li>Click <span class="button-name">Deploy</span>.</li>
</ol>
<!--- TODO: Refer to local brokerages available --->