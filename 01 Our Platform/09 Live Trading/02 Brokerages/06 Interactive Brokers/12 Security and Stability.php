<?php
include(DOCS_RESOURCES."/brokerages/security-and-stability.php");
$getBrokerageStabilityText("IB", "https://www.interactivebrokers.com/en/software/systemStatus.php");
?>

<h4>Connections</h4>
<p>By default, IB only supports one connection at a time to your account. If you interfere with your brokerage account while an algorithm is connected to it, the algorithm may stop executing. If you deploy a live running algorithm with your IB account and want to open Trader Workstation (TWS) with the same IB account, <a rel='nofollow' target="_blank" href='https://ibkr.info/node/1004'>create a second user on your IB account</a> and log in to TWS with the new user credentials. To run more than one algorithm with IB, <a rel="nofollow" target="_blank" href="https://www.interactivebrokers.com.hk/en/software/ptgstl/topics/ptgsubaccounts.htm">open an IB subaccount</a> for each additional algorithm.</p>

<p>If you can't log in to TWS with your credentials, contact IB. If you can log in to TWS but can't log in to the deployment wizard, <a href='/contact'>contact us</a> and provide the algorithm ID and deployment ID.</p>

<h4>SMS 2FA</h4>
<p>Our IB integration doesn't support Two-Factor Authentication (2FA) via SMS or the Online Security Code Card. Use the <a rel="nofollow" target="_blank" href="https://guides.interactivebrokers.com/iphone/log_in/ibkey.htm?tocpath=IB%20Key%20Security%20Protocol%7C_____0">IB Key Security via IBKR Mobile</a> instead.</p>

<h4>System Resets</h4>
<p>If your IB account has 2FA enabled, you receive a notification on your IB Key device every Sunday to re-authenticate the connection between IB and your live algorithm. When you <a href='/docs/v2/our-platform/live-trading/brokerages/interactive-brokers#16-Deploy-Live-Algorithms'>deploy your algorithm</a>, you can select a  time on Sunday that that you'll receive the notification. If you don't re-authenticate before the timeout period, your algorithm quits executing.</p>
