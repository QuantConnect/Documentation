<p><? if ($usBrokerage) { ?> 
    <? if ($cashAccount) { ?> 
        If you trade with a margin account, trades settle immediately
    <? } else if ($marginAccount) { ?>
        If you trade with a cash account, 
        <? if ($equities) { ?>
            Equity trades settle 2 days after the transaction date (T+2) <?=$options ? " and " : ""?>
        <? } ?>
        <? if ($options) { ?>
            Option trades settle on the business day following the transaction (T+1)
        <? } ?>
    <? } ?>
<? } else { ?>
    Trades settle immediately after the transaction
<? } ?></p>

<div class="section-example-container">
    <pre class="csharp">security.SettlementModel = new ImmediateSettlementModel();</pre>
    <pre class="python">security.SettlementModel = ImmediateSettlementModel()</pre>
</div>