<p>The <code>InteractiveBrokersBrokerageModel</code> uses the <a href='/docs/v2/writing-algorithms/reality-modeling/settlement/supported-models#02-Immediate-Model'>ImmediateSettlementModel</a> in most cases. If you trade US Equities or Equity Options with a cash account, it uses the <a href='/docs/v2/writing-algorithms/reality-modeling/settlement/supported-models#03-Delayed-Model'>DelayedSettlementModel</a> with the <a href='/docs/v2/writing-algorithms/reality-modeling/settlement/key-concepts#03-Default-Behavior'>default settlement rules</a>.</p>


<p>The following table shows which <a href='/docs/v2/writing-algorithms/reality-modeling/settlement/key-concepts'>settlement model</a> the <code>InteractiveBrokersBrokerageModel</code> uses based on the security type and your account type:</p>

<?
$brokerageModelName = "InteractiveBrokersBrokerageModel";
$includeLinks = false;
include(DOCS_RESOURCES."/reality-modeling/default-settlement-models.php"); 
?>

<div class="section-example-container">
<pre class="csharp">// For US Equities with a cash account:
security.SetSettlementModel(new DelayedSettlementModel(Equity.DefaultSettlementDays, Equity.DefaultSettlementTime));

// For Equity Options with a cash account:
security.SetSettlementModel(new DelayedSettlementModel(Option.DefaultSettlementDays, Option.DefaultSettlementTime));

// For remaining cases:
security.SetSettlementModel(new ImmediateSettlementModel());</pre>
<pre class="python"># For US Equities with a cash account:
security.set_settlement_model(DelayedSettlementModel(Equity.DEFAULTSETTLEMENTDAYS, Equity.default_settlement_time))

# For Equity Options with a cash account:
security.set_settlement_model(DelayedSettlementModel(Option.DEFAULTSETTLEMENTDAYS, Option.default_settlement_time))

# For remaining cases:
security.set_settlement_model(ImmediateSettlementModel())</pre>
</div>

<p>Interactive Brokers doesn't provide information on which assets aren't settled, so we assume each live trading session starts with its cash fully settled.</p>
