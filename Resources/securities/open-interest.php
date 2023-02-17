<p>Open interest is the number of outstanding contracts that haven't been settled. It provides a measure of investor interest and the market liquidity, so it's a popular metric to use for contract selection. Open interest is calculated once per day. To get the latest open interest value, use the <code>OpenInterest</code> property of the <code><?=$contractTypeName?></code> or <code><?=$contractTypeName?>Contract</code>.</p>

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

<?php if ($chainTypeName != "FuturesChains") { ?>
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
<?php } ?></pre>
    <pre class='python'>def OnData(self, slice: Slice) -> None:
    chain = slice.<?=$chainTypeName?>.get(self.contract_symbol.Canonical)
    if chain:
        contract = chain.Contracts.get(self.contract_symbol)
        if contract:
            open_interest = contract.OpenInterest</pre>
</div>
