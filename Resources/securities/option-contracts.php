<p>
    <code>OptionContract</code> objects represent the data of a single Option contract in the market. 
    To get the Option contracts in the <code>Slice</code>, use the <code class="csharp">Contracts</code><code class="python">contracts</code> property of the <code>OptionChain</code>.
</p>

<?
if ($isFutureOptions) 
{
?>

<div class='section-example-container'>
    <pre class='csharp'>public override void OnData(Slice slice)
{
    // Try to get the OptionChain using the canonical symbol
    if (slice.OptionChains.TryGetValue(_optionContractSymbol.Canonical, out var chain))
    {
        // Get individual contract data
        if (chain.Contracts.TryGetValue(_optionContractSymbol, out var contract))
        {
            var price = contract.Price;
        }
    }
}
</pre>
    <pre class='python'>def on_data(self, slice: Slice) -> None:
    # Try to get the OptionChain using the canonical symbol
    chain = slice.option_chains.get(self._option_contract_symbol.canonical)
    if chain:
        # Get individual contract data
        contract = chain.contracts.get(self._option_contract_symbol)
        if contract:
            price = contract.price</pre>
</div>  

<p>You can also iterate through the <code class="csharp">FuturesChains</code><code class="python">futures_chains</code> first.</p>

<div class='section-example-container'>
    <pre class='csharp'>public override void OnData(Slice slice)
{
    // Iterate all received Canonical Symbol-FuturesChain key-value pairs
    foreach (var kvp in slice.FuturesChains)
    {
        var continuousContractSymbol = kvp.Key;
        var futuresChain = kvp.Value;
        
        // Select a Future Contract and create its canonical FOP Symbol
        var futuresContract = futuresChain.First();
        var canonicalFOPSymbol = QuantConnect.Symbol.CreateCanonicalOption(futuresContract.Symbol);
        // Try to get the OptionChain using the canonical FOP symbol
        if (slice.OptionChains.TryGetValue(canonicalFOPSymbol, out var optionChain))
        {
            // Get individual contract data
            if (optionChain.Contracts.TryGetValue(_optionContractSymbol, out var optionContract))
            {
                var price = optionContract.Price;
            }
        }
    }
}</pre>
    <pre class='python'>def on_data(self, slice: Slice) -> None:
    # Iterate all received Canonical Symbol-FuturesChain key-value pairs
    for continuous_future_symbol, futures_chain in slice.futures_chains.items():
        # Select a Future Contract and create its canonical FOP Symbol
        futures_contract = [contract for contract in futures_chain][0]
        canonical_fop_symbol = Symbol.create_canonical_option(futures_contract.symbol)
        # Try to get the OptionChain using the canonical FOP symbol
        option_chain = slice.option_chains.get(canonical_fop_symbol)
        if option_chain:
            # Get individual contract data
            option_contract = option_chain.contracts.get(self._option_contract_symbol)
            if option_contract:
                price = option_contract.price</pre>
</div> 
<? } else { ?>
<div class='section-example-container'>
    <pre class='csharp'>public override void OnData(Slice slice)
{
    // Try to get the OptionChain using the canonical symbol
    if (slice.OptionChains.TryGetValue(_contractSymbol.Canonical, out var chain))
    {
        // Get individual contract data
        if (chain.Contracts.TryGetValue(_contractSymbol, out var contract))
        {
            var price = contract.Price;
        }
    }
}</pre>
    <pre class='python'>def on_data(self, slice: Slice) -> None:
    # Try to get the OptionChain using the canonical symbol
    chain = slice.option_chains.get(self._contract_symbol.canonical)
    if chain:
        # Get individual contract data
        contract = chain.contracts.get(self._contract_symbol)
        if contract:
            price = contract.price</pre>
</div>

<?
}
?>

<h4>Greeks and Implied Volatility</h4>

<p>To get the Greeks and implied volatility of an Option contract, use the <code class="csharp">Greeks</code><code class="python">greeks</code> and <code>implied_volatility</code> members.</p>

<?
if ($isFutureOptions) 
{
?>

<div class='section-example-container'>
    <pre class='csharp'>public override void OnData(Slice slice)
{
    // Try to get the OptionChain using the canonical symbol
    if (slice.OptionChains.TryGetValue(_optionContractSymbol.Canonical, out var chain))
    {
        // Get individual contract data
        if (chain.Contracts.TryGetValue(_optionContractSymbol, out var contract))
        {
            // Get greeks data of the selected contract
            var delta = contract.Greeks.Delta;
            var iv = contract.ImpliedVolatility;
        }
    }
}</pre>
    <pre class='python'>def on_data(self, slice: Slice) -> None:
    # Try to get the OptionChain using the canonical symbol
    chain = slice.option_chains.get(self._option_contract_symbol.canonical)
    if chain:
        # Get individual contract data
        contract = chain.contracts.get(self._option_contract_symbol)
        if contract:
            # Get greeks data of the selected contract
            delta = contract.greeks.delta
            iv = contract.implied_volatility</pre>
</div>
<? } else { ?>
<div class='section-example-container'>
    <pre class='csharp'>public override void OnData(Slice slice)
{
    // Try to get the OptionChain using the canonical symbol
    if (slice.OptionChains.TryGetValue(_contractSymbol.Canonical, out var chain))
    {
        // Get individual contract data
        if (chain.Contracts.TryGetValue(_contractSymbol, out var contract))
        {
            // Get greeks data of the selected contract
            var delta = contract.Greeks.Delta;
            var iv = contract.ImpliedVolatility;
        }
    }
}</pre>
    <pre class='python'>def on_data(self, slice: Slice) -> None:
    # Try to get the OptionChain using the canonical symbol
    chain = slice.option_chains.get(self._contract_symbol.canonical)
    if chain:
        # Get individual contract data
        contract = chain.contracts.get(self._contract_symbol)
        if contract:
            # Get greeks data of the selected contract
            delta = contract.greeks.delta
            iv = contract.implied_volatility</pre>
</div>

<?
}
?>

<p>LEAN only calculates Greeks and implied volatility when you request them because they are expensive operations. If you invoke the <code class="csharp">Greeks</code><code class="python">greeks</code> property, the Greeks aren't calculated. However, if you invoke the <code class="csharp">Greeks.Delta</code><code class="python">greeks.delta</code>, LEAN calculates the delta. To avoid unecessary computation in your algorithm, only request the Greeks and implied volatility when you need them. For more information about the Greeks and implied volatility, see <a href='/docs/v2/writing-algorithms/reality-modeling/options-models/pricing'>Options Pricing</a>.</p>

<h4>Open Interest</h4>
