<?php
$getCLIDeploymentIntroText = function($brokerageName) {
    echo "
        <p>The Lean CLI supports local live trading with all brokerages supported by LEAN, which makes the transfer from backtesting to live trading as seamless as possible. The Lean CLI also supports starting live trading for a cloud project on any of the brokerages supported in the cloud. We recommend live trading your projects in our cloud because we provide a battle-tested, colocated infrastructure racked in Equinix, maintained by our engineers to ensure the best possible stability and uptime. This page contains instructions on how to start live trading with the {$brokerageName} brokerage.</p>";
}

?>