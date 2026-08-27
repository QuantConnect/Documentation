<?
$brokerageName = "<a rel='nofollow' target='_blank' href='https://qnt.co/interactivebrokers'>Interactive Brokers</a>";
$cashState = false;
$holdingsState = false;
$secondBullet = "";
$authentication = "
    <li>In the <span class='page-section-name'>Interactive Brokers (FIX) Authentication</span> section, follow these steps:</li>
    <ol>
        <li>Click the <span class='field-name'>Account</span> field and then click your <a rel='nofollow' target='_blank' href='https://qnt.co/interactivebrokers'>IB</a> user name from the drop-down menu.</li>
        <p>To use an IB user name you haven't linked yet, click <span class='button-name'>+ Add Account</span> and then enter it in the <span class='field-name'>IBKR User Name</span> field.</p>
        <li>Click <span class='button-name'>Authenticate</span> and then log in to IB.</li>
        <p>When you succeed, the <span class='field-name'>Authorization</span> row shows <span class='field-name'>Authorization Complete</span> and the wizard displays the <span class='field-name'>Account ID</span> and <span class='field-name'>IBKR QC UID</span> fields.</p>
        <li>Click the <span class='field-name'>Account ID</span> field and then click the IB account you want to trade from the drop-down menu.</li>
        <li>Copy the value in the <span class='field-name'>IBKR QC UID</span> field and then add it to your IB account.</li>
        <p>QuantConnect generates the <span class='field-name'>IBKR QC UID</span> from the account you select, so it stays empty until you select an <span class='field-name'>Account ID</span>. Your IB account only accepts orders from QuantConnect after you register this value, so add it before you continue.</p>
        <p>To register it, follow the steps in <a href='/docs/v2/cloud-platform/live-trading/brokerages/interactive-brokers#03-FIX-Integration'>FIX Integration</a> and then return to this page. You only need to register each <span class='field-name'>IBKR QC UID</span> once, so repeat those steps when you trade an account you haven't registered yet. After you save it, wait about 5 minutes for IB to apply the change before you deploy.</p>
    </ol>";
$dataProviderDetails =  "<p>In most cases, we suggest using <a href='/docs/v2/cloud-platform/datasets/interactive-brokers#06-Hybrid-Data-Provider'>both the QC and IB data providers</a>.</p>" . file_get_contents(DOCS_RESOURCES."/brokerages/interactive-brokers/paper-trading-data-feeds.html");
$postDeploy = "<li>If your IB account has 2FA enabled, tap the notification on your IB Key device and then enter your pin.</li>";
include(DOCS_RESOURCES."/live-trading/deploy-live-algorithm.php");
?>
