<?
$brokerageName = "Bybit Exchange";
$cashState = false;
$holdingsState = true;
$secondBullet = "";
$authentication = "<li>Enter your API key and secret.</li>" . file_get_contents(DOCS_RESOURCES."/brokerages/create-credentials/bitfinex.html") . "
<li>Click the <span class='field-name'>VIP Level</span> field and then click your level from the drop-down menu.</li>
<p>For more information about VIP levels, see <a href='https://www.bybit.com/en/help-center/article/FAQ-VIP-Program' target='_blank' rel='nofollow'>FAQ — Bybit VIP Program</a> on the Bybit website.</p>
<li>Click on the <span class='field-name'>Environment</span> field and then click one of the environments.</li>
<p>The following table shows the supported environments:</p>

<table class='table qc-table'>
    <thead>
        <tr>
            <th>Environment</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Live</td>
            <td>Trade with real money</td>
        </tr>
        <tr>
            <td>Demo</td>
            <td>Trade with paper money through the <a href='https://www.bybit.com/en/help-center/article/FAQ-Demo-Trading' target='_blank' rel='nofollow'>Bybit Demo Trading</a> environment</td>
        </tr>
        <tr>
            <td>Testnet</td>
            <td>Trade with paper money through the <a href='https://testnet.bybit.com/' target='_blank' rel='nofollow'>Bybit Testnet</a></td>
        </tr>
    </tbody>
</table>";
$postDeploy = "";
include(DOCS_RESOURCES."/live-trading/deploy-live-algorithm.php");
?>