<?php
include(DOCS_RESOURCES."/brokerages/security-and-stability.php");
$getBrokerageStabilityText("IB",
	                       "/docs/v2/our-platform/tutorials/live-trading/brokerages/interactive-brokers#03-Deploy-Live-Algorithms", 
	                       "https://www.interactivebrokers.com/en/software/systemStatus.php");
?>

<h4>Connections</h4>
<p>IB only supports 1 connection at a time to your account. To run more than 1 algorithm, <a rel="nofollow" target="_blank" href="https://www.interactivebrokers.com.hk/en/software/ptgstl/topics/ptgsubaccounts.htm">open an IB subaccount</a> for each additional algorithm.</p>

<h4>SMS 2FA</h4>
<p>Our IB integration doesn't support Two-Factor Authentication (2FA) via SMS. Use the <a rel="nofollow" target="_blank" href="https://guides.interactivebrokers.com/iphone/log_in/using_ios.htm">IB Key protocol in IBKR Mobile</a> instead.</p>

<h4>System Resets</h4>
<p>If your IB account has 2FA enabled, you'll need to re-authenticate once a week. The reset schedule is available on the <a rel="nofollow" target="_blank" href="https://www.interactivebrokers.com/en/software/systemStatus.php">IB Status page</a>. If you miss the timeout period, your algorithm quits executing.</p>