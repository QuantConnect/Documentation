<?
$brokerageName = "Bitfinex Exchange";
$cashState = false;
$holdingsState = false;
$secondBullet = "<p>If you are deploying a paper trading algorithm without the QuantConnect Paper Trading brokerage, include the following lines of code in the <code>Initialize</code> method of your algorithm:</p>
<div class='section-example-container'>
    <pre class='python'>self.SetAccountCurrency('TESTUSD') # or 'TESTUSDT'
self.SetBrokerageModel(BrokerageName.Bitfinex, AccountType.Cash)
self.SetBenchmark(lambda x: 0) # or the Symbol of the TESTBTCTESTUSD/TESTBTCTESTUSDT securities</pre>
    <pre class='csharp'>SetAccountCurrency(\"TESTUSD\"); // or \"TESTUSDT\"
SetBrokerageModel(BrokerageName.Bitfinex, AccountType.Cash);
SetBenchmark(x =&gt; 0); // or the Symbol of the TESTBTCTESTUSD/TESTBTCTESTUSDT securities</pre>
</div>";
$authentication = "<li>Enter your API key and secret.</li>";
$authentication .= file_get_contents(DOCS_RESOURCES."/brokerages/create-credentials/bitfinex.html");
$postDeploy = "";
include(DOCS_RESOURCES."/live-trading/deploy-live-algorithm.php");
?>