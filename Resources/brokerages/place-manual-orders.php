<?php
$getPlaceManualOrdersText = function($viewLivePerformanceLink) {
    echo "
        <p>Follow these steps to place manual orders in your live algorithm:</p>
        <ol>
            <li><a href='{$viewLivePerformanceLink}'>Open the live performance page</a> of the algorithm for which you want to place manual orders.</li>
            <li>On the live performance page, click the <span class='tab-name'>Holdings</span> tab.</li>
            <li>If the algorithm doesn't have a subscription to the security that you want to trade, follow these steps:</li>
            <ol>
                <li>Click <span class='button-name'>Add Security</span>.</li>
                <li>Enter the symbol, security type, resolution, leverage, and market.</li>
                <li>If you want the data for the security to be filled-forward, check the <span class='box-name'>Fill Forward</span> check box.</li>
                <li>If you want to subscribe to extended market hours for the security, check the <span class='box-name'>Extended Market Hours</span> check box.</li>
                <li>Click <span class='button-name'>Add Security</span>.</li>
                <p>'Success Command Enqueue' displays.</p>
            </ol>
            <li>If you don't see the security for which you want to place a manual order in the Algorithm Holdings table, select the <span class='box-name'>Show All Portfolio</span> check box.</li>
            <li>Click the security for which you want to place a manual order.</li>
            <li>If you want to liquidate the position, click <span class='button-name'>Liquidate</span>. Otherwise, click <span class='button-name'>Create Order</span> and then enter an order quantity and type.</li>
            <li>Click <span class='button-name'>Submit Order</span>.</li>
            <li>Click <span class='button-name'>Close</span>.</li>
        </ol>
    ";
}
?>
