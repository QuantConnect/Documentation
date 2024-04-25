<p>Open interest is the number of outstanding contracts that haven't been settled. It provides a measure of investor interest and the market liquidity, so it's a popular metric to use for contract selection. Open interest is calculated once per day. To get the latest open interest value, use the <code class="csharp">OpenInterest</code><code class="python">open_interest</code> property of the <code><?=$contractTypeName?></code> or <code class="csharp"><?=$contractTypeName?>Contract</code><code class="python"><?=$contractTypeName?>contract</code>.</p>

<div class='section-example-container'>
    <pre class='csharp'>public override void OnData(Slice slice)
{
    if (slice.<?=$chainTypeName?>.TryGetValue(_contractSymbol.Canonical, out var chain))
    {
        if (chain.Contracts.TryGetValue(_contractSymbol, out var contract))
        {
            var openInterest = contract.OpenInterest;
        }
    }
}

<? if ($chainTypeName != "FuturesChains") { ?>
public void OnData(<?=$chainTypeName?> <?=$variableName?>)
{
    if (<?=$variableName?>.TryGetValue(_contractSymbol.Canonical, out var chain))
    {
        if (chain.Contracts.TryGetValue(_contractSymbol, out var contract))
        {
            var openInterest = contract.OpenInterest;
        }
    }
}
<? } ?></pre>
    <pre class='python'>def on_data(self, slice: Slice) -> None:
    chain = slice.<?=$chainTypeName?>.get(self.contract_symbol.canonical)
    if chain:
        contract = chain.contracts.get(self.contract_symbol)
        if contract:
            open_interest = contract.open_interest</pre>
</div>