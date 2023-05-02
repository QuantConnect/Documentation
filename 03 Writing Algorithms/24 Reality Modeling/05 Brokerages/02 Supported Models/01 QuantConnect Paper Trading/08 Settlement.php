<p>The following table shows which <a href='/docs/v2/writing-algorithms/reality-modeling/settlement/key-concepts'>settlement model</a> the <code>DefaultBrokerageModel</code> uses based on the security type and your account type:</p>

<? 
$includeLinks = true;
$brokerageModelName = "DefaultBrokerageModel";
include(DOCS_RESOURCES."/reality-modeling/default-settlement-models.php"); 
?>

<div class="section-example-container">
<pre class="csharp">// For US Equities with a cash account:
security.SettlementModel = new DelayedSettlementModel(Equity.DefaultSettlementDays, Equity.DefaultSettlementTime);

// For Equity Options with a cash account:
security.SettlementModel = new DelayedSettlementModel(Option.DefaultSettlementDays, Option.DefaultSettlementTime);

// For Futures
security.SettlementModel = new FutureSettlementModel();

// For remaining cases:
security.SettlementModel = new ImmediateSettlementModel();</pre>
<pre class="python"># For US Equities with a cash account:
security.SettlementModel = DelayedSettlementModel(Equity.DefaultSettlementDays, Equity.DefaultSettlementTime)

# For Equity Options with a cash account:
security.SettlementModel = DelayedSettlementModel(Option.DefaultSettlementDays, Option.DefaultSettlementTime)

# For Futures
security.SettlementModel = FutureSettlementModel()

# For remaining cases:
security.SettlementModel = ImmediateSettlementModel()</pre>
</div>