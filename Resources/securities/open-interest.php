<p>Open interest is the number of outstanding contracts that haven't been settled. It provides a measure of investor interest and the market liquidity, so it's a popular metric to use for contract selection. Open interest is calculated once per day. To get the latest open interest value, use the <code class="csharp">OpenInterest</code><code class="python">open_interest</code> property of the <code><?=$contractTypeName?></code> or <code class="csharp"><?=$contractTypeName?>Contract</code><code class="python"><?=$pyContractTypeName?>_contract</code>.</p>

<div class='section-example-container'>
    <pre class='csharp'>public override void OnData(Slice slice)
{
    // Try to get the <?=$chainTypeName?> using the canonical symbol
    if (slice.<?=$chainTypeName?>.TryGetValue(_contractSymbol.Canonical, out var chain))
    {
        // Get individual contract data
        if (chain.Contracts.TryGetValue(_contractSymbol, out var contract))
        {
            // Get the open interest of the selected contracts
            var openInterest = contract.OpenInterest;
        }
    }
}

<? if ($chainTypeName != "FuturesChains") { ?>
public void OnData(<?=$chainTypeName?> <?=$variableName?>)
{
    // Try to get the <?=$chainTypeName?> using the canonical symbol
    if (<?=$variableName?>.TryGetValue(_contractSymbol.Canonical, out var chain))
    {
        // Get individual contract data
        if (chain.Contracts.TryGetValue(_contractSymbol, out var contract))
        {
            // Get the open interest of the selected contracts
            var openInterest = contract.OpenInterest;
        }
    }
}
<? } ?></pre>
    <pre class='python'>def on_data(self, slice: Slice) -> None:
    # Try to get the <?=$pyChainTypeName?> using the canonical symbol
    chain = slice.<?=$pyChainTypeName?>.get(self._contract_symbol.canonical)
    if chain:
        # Get individual contract data
        contract = chain.contracts.get(self._contract_symbol)
        if contract:
            # Get the open interest of the selected contracts
            open_interest = contract.open_interest</pre>
</div>
