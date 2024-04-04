<p><code>OptionContract</code> objects represent the data of a single Option contract in the market. They have the following properties:</p>
<div data-tree='QuantConnect.Data.Market.OptionContract'></div>

<p>To get the Option contracts in the <code>Slice</code>, use the <code>Contracts</code> property of the <code>OptionChain</code>.</p>

<?php
if ($isFutureOptions) 
{
?>

<div class='section-example-container'>
    <pre class='csharp'>public override void OnData(Slice slice)
{
    if (slice.OptionChains.TryGetValue(_optionContractSymbol.Canonical, out var chain))
    {
        if (chain.Contracts.TryGetValue(_optionContractSymbol, out var contract))
        {
            var price = contract.Price;
        }
    }
}
</pre>
    <pre class='python'>def OnData(self, slice: Slice) -> None:
    chain = slice.OptionChains.get(self.option_contract_symbol.Canonical)
    if chain:
        contract = chain.Contracts.get(self.option_contract_symbol)
        if contract:
            price = contract.Price</pre>
</div>  

<p>You can also iterate through the <code>FuturesChains</code> first.</p>

<div class='section-example-container'>
    <pre class='csharp'>public override void OnData(Slice slice)
{
    foreach (var kvp in slice.FuturesChains)
    {
        var continuousContractSymbol = kvp.Key;
        var futuresChain = kvp.Value;
        
        // Select a Future Contract and create its canonical FOP Symbol
        var futuresContract = futuresChain.First();
        var canonicalFOPSymbol = QuantConnect.Symbol.CreateCanonicalOption(futuresContract.Symbol);
        if (slice.OptionChains.TryGetValue(canonicalFOPSymbol, out var optionChain))
        {
            if (optionChain.Contracts.TryGetValue(_optionContractSymbol, out var optionContract))
            {
                var price = optionContract.Price;
            }
        }
    }
}</pre>
    <pre class='python'>def OnData(self, slice: Slice) -> None:
    for continuous_future_symbol, futures_chain in slice.FuturesChains.items():
        # Select a Future Contract and create its canonical FOP Symbol
        futures_contract = [contract for contract in futures_chain][0]
        canonical_fop_symbol = Symbol.CreateCanonicalOption(futures_contract.Symbol)
        option_chain = slice.OptionChains.get(canonical_fop_symbol)
        if option_chain:
            option_contract = option_chain.Contracts.get(self.option_contract_symbol)
            if option_contract:
                price = option_contract.Price</pre>
</div> 

<?php
}
else 
{
?>

<div class='section-example-container'>
    <pre class='csharp'>public override void OnData(Slice slice)
{
    if (slice.OptionChains.TryGetValue(_contractSymbol.Canonical, out var chain))
    {
        if (chain.Contracts.TryGetValue(_contractSymbol, out var contract))
        {
            var price = contract.Price;
        }
    }
}</pre>
    <pre class='python'>def OnData(self, slice: Slice) -> None:
    chain = slice.OptionChains.get(self.contract_symbol.Canonical)
    if chain:
        contract = chain.Contracts.get(self.contract_symbol)
        if contract:
            price = contract.Price</pre>
</div>

<?php
}
?>

<h4>Greeks and Implied Volatility</h4>

<p>To get the Greeks and implied volatility of an Option contract, use the <code>Greeks</code> and <code>ImpliedVolatility</code> members.</p>

<?php
if ($isFutureOptions) 
{
?>

<div class='section-example-container'>
    <pre class='csharp'>public override void OnData(Slice slice)
{
    if (slice.OptionChains.TryGetValue(_optionContractSymbol.Canonical, out var chain))
    {
        if (chain.Contracts.TryGetValue(_optionContractSymbol, out var contract))
        {
            var delta = contract.Greeks.Delta;
            var iv = contract.ImpliedVolatility;
        }
    }
}</pre>
    <pre class='python'>def OnData(self, slice: Slice) -> None:
    chain = slice.OptionChains.get(self.option_contract_symbol.Canonical)
    if chain:
        contract = chain.Contracts.get(self.option_contract_symbol)
        if contract:
            delta = contract.Greeks.Delta
            iv = contract.ImpliedVolatility</pre>
</div>

<?php
}
else 
{
?>

<div class='section-example-container'>
    <pre class='csharp'>public override void OnData(Slice slice)
{
    if (slice.OptionChains.TryGetValue(_contractSymbol.Canonical, out var chain))
    {
        if (chain.Contracts.TryGetValue(_contractSymbol, out var contract))
        {
            var delta = contract.Greeks.Delta;
            var iv = contract.ImpliedVolatility;
        }
    }
}</pre>
    <pre class='python'>def OnData(self, slice: Slice) -> None:
    chain = slice.OptionChains.get(self.contract_symbol.Canonical)
    if chain:
        contract = chain.Contracts.get(self.contract_symbol)
        if contract:
            delta = contract.Greeks.Delta
            iv = contract.ImpliedVolatility</pre>
</div>

<?php
}
?>

<p>LEAN only calculates Greeks and implied volatility when you request them because they are expensive operations. If you invoke the <code>Greeks</code> member, the Greeks aren't calculated. However, if you invoke the <code>Greeks.Delta</code> member, LEAN calculates the delta. To avoid unecessary computation in your algorithm, only request the Greeks and implied volatility when you need them. For more information about the Greeks and implied volatility, see <a href='/docs/v2/writing-algorithms/reality-modeling/options-models/pricing'>Options Pricing</a>.</p>


<h4>Open Interest</h4>
