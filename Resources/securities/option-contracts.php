<?php
$getOptionContractText = function($isFutureOptions)
{
    echo "
<p><code>OptionContract</code> objects represent the data of a single Option contract in the market. They have the following properties:</p>
<div data-tree='QuantConnect.Data.Market.OptionContract'></div>

<p>For more information about Greeks, see <a href='/tutorials/introduction-to-options/the-greek-letters'>The Greek Letters</a>.</p>

<p>To get the Option contracts in the <code>Slice</code>, use the <code>Contracts</code> property of the <code>OptionChain</code>.</p>
";
    if () 
    {
        echo "
<div class='section-example-container'>
    <pre class='csharp'>public override void OnData(Slice slice)
{
    if (slice.OptionChains.TryGetValue(_optionContractSymbol.Canonical, out var chain))
    {
        if (chain.Contracts.TryGetValue(_optionContractSymbol, out var contract))
        {
            //
        }
    }
}

public void OnData(OptionChains optionChains)
{
    if (optionChains.TryGetValue(_optionContractSymbol.Canonical, out var chain))
    {
        if (chain.Contracts.TryGetValue(_optionContractSymbol, out var contract))
        {
            //
        }
    }
}</pre>
    <pre class='python'>def OnData(self, slice: Slice) -> None:
    chain = slice.OptionChains.get(self.option_contract_symbol.Canonical)
    if chain:
        contract = chain.Contracts.get(self.option_contract_symbol)
        if contract:
            pass</pre>
</div>  


<div class='section-example-container'>
    <pre class='csharp'>public override void OnData(Slice slice)
{
    if (slice.OptionChains.TryGetValue(_contractSymbol.Canonical, out var chain))
    {
        if (chain.Contracts.TryGetValue(_contractSymbol, out var contract))
        {
            //
        }
    }
}

public void OnData(OptionChains optionChains)
{
    if (optionChains.TryGetValue(_contractSymbol.Canonical, out var chain))
    {
        if (chain.Contracts.TryGetValue(_contractSymbol, out var contract))
        {
            //
        }
    }
}</pre>
    <pre class='python'>def OnData(self, slice: Slice) -> None:
    for continuous_future_symbol, futures_chain in slice.FuturesChains.items():
        # Select a Future Contract and create it's canonical FOP symbol
        contract = [contract for contract in futures_chain][0]
        canonical_fop_symbol = Symbol.CreateCanonicalOption(contract.Symbol)
        chain = data.OptionChains.get(canonical_fop_symbol)
        if chain:
            contract = chain.Contracts.get(self.option_contract_symbol)
            if contract:
                pass</pre>
</div> 
        
        ";   
    }
    else 
    {
        echo "
<div class='section-example-container'>
    <pre class='csharp'>public override void OnData(Slice slice)
{
    if (slice.OptionChains.TryGetValue(_contractSymbol.Canonical, out var chain))
    {
        if (chain.Contracts.TryGetValue(_contractSymbol, out var contract))
        {
            //
        }
    }
}

public void OnData(OptionChains optionChains)
{
    if (optionChains.TryGetValue(_contractSymbol.Canonical, out var chain))
    {
        if (chain.Contracts.TryGetValue(_contractSymbol, out var contract))
        {
            //
        }
    }
}</pre>
    <pre class='python'>def OnData(self, slice: Slice) -> None:
    chain = slice.OptionChains.get(self.contract_symbol.Canonical)
    if chain:
        contract = chain.Contracts.get(self.contract_symbol)
        if contract:
            pass</pre>
</div>        
";   
    }
    
}
?>
