<p>You can deploy Alphas to live trading, update the version of Alphas that you have deployed, and adjust your allocation to an Alpha.</p>

<h4>Deploy Alpha</h4>

<?php
    echo file_get_contents(DOCS_RESOURCES."/deploy-alphas.html");
?>

<h4>Update Alpha</h4>
<p>If the author of an Alpha that you're running live updates the Alpha code, follow these steps to update the version that you have deployed:</p>
<ol>
    <li>Stop the algorithm.</li>
    <p>For instructions on stopping the algorithm, refer to <a href="../live-trading/brokerages">the tutorial of your brokerage</a>.</p>
    <li>In the project panel, click <span class='button-name'>Delete</span>.</li>
    <li>Redeploy the Alpha.</li>
</ol>

<h4>Adjust Alpha Allocation</h4>
<p>Follow these steps to adjust the amount of capital that you allocate to an Alpha:</p>
<ol>
    <li>Stop the algorithm.</li>
    <p>For instructions on stopping the algorithm, refer to <a href="../live-trading/brokerages">the tutorial of your brokerage</a>.</p>
    <li>If you want to increase your allocation to the Alpha passed the allocation size that your subscription allows or you want to reduce your allocation to the Alpha passed your subscription period, <a href="#05-Manage-Existing-Bids">update your bid</a> with the new allocation size.</li>
    <li>Adjust the amount of capital in your brokerage account to the amount of capital that you want to allocate to the Alpha.</li>
    <li>Redeploy the Alpha.</li>
</ol>

<h4>Cancel Subscription</h4>
<p>You can not cancel an Alpha subscription, but you may <a href="#05-Manage-Existing-Bids">cancel the auto-renewing bid</a> that you have for the Alpha. If you have strong reasons to request a cancellation and refund, <a href="/contact">contact us</a> with those reasons for evaluation.</p>
