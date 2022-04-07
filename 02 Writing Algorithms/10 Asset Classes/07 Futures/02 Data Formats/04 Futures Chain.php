<div>-easier to make a contract selection</div><div>-Keyed by canonical symbol, values are all the available options/futures</div><div>-Passed to OnData/Update</div><div>-Example of navigating the chain (We can select the contracts expiring no earlier than 90 days and select the contract with the largest expiry and trade on it.)<br></div>

<div class="section-example-container">
    <pre class="csharp">for chain in slice.FutureChains:
    # Get contracts expiring no earlier than in 90 days
    contracts = list(filter(lambda x: x.Expiry > self.Time + timedelta(90), chain.Value))

    # if there is any contract, trade the front contract
    if len(contracts) == 0: continue
    contract = sorted(contracts, key = lambda x: x.Expiry, reverse=True)[0]

    self.contractSymbol = contract.Symbol
    self.MarketOrder(front.Symbol , 1)
</pre>
    <pre class="python">foreach(var chain in slice.FutureChains)
{
    // find the front contract expiring no earlier than in 90 days
    var contract = 
    (   
        from futuresContract in chain.Value.OrderBy(x => x.Expiry)
        where futuresContract.Expiry > Time.Date.AddDays(90)
        select futuresContract
    ).FirstOrDefault();

    // if found, trade it
    if (contract != null)
    {
        _contractSymbol = contract.Symbol;
        MarketOrder(_contractSymbol, 1);
    }
}
</pre>
</div>


<div data-tree='QuantConnect.Data.Market.FuturesChains'></div>