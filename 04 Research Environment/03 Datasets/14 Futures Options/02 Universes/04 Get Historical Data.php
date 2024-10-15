<p>You need to <a href='/docs/v2/research-environment/datasets/futures-options/universes#03-Create-Subscriptions'>add a Future to your QuantBook</a> before you can request historical data for Future Option contracts. To get the prices and volumes for all of the Option contracts of the underlying Futures contract over a specific period of time, call the <code class="csharp">OptionHistory</code><code class="python">option_history</code> method with the underlying Future contract's <code>Symbol</code> object, a start <code class='csharp'>DateTime</code><code class='python'>datetime</code>, and an end <code class='csharp'>DateTime</code><code class='python'>datetime</code>. The following example gets the front-month Futures contract and then gets all of the Option contracts on top of that Futures contract.</p>

<div class='section-example-container'>
    <pre class='python'>start_date = datetime(2024, 1, 1)

# Select a contract. For example, get the front-month Futures contract.
futures_contract = sorted(
    qb.future_chain_provider.get_future_contract_list(future.symbol, start_date),
    key=lambda symbol: symbol.id.date
)[0]

# Get the Options data for the selected Futures contract.
option_history = qb.option_history(
    futures_contract, start_date, futures_contract.id.date, Resolution.HOUR, 
    fill_forward=False, extended_market_hours=False
).data_frame</pre>
    <pre class='csharp'>var startDate = new DateTime(2024, 1, 1);

// Select a contract. For example, get the front-month Futures contract.
var futuresContract = qb.FutureChainProvider.GetFutureContractList(future.Symbol, startDate)
    .OrderBy(symbol => symbol.ID.Date)
    .Last();

// Get the Options data for the selected Futures contract.
var optionHistory = qb.OptionHistory(
    futuresContract, startDate, futuresContract.ID.Date, Resolution.Hour, 
    fillForward: false, extendedMarketHours: false
);</pre>
</div>

