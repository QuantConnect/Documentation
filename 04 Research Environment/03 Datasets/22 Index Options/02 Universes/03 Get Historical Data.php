<p>You need to <a href='/docs/v2/research-environment/datasets/index-options/universes#02-Create-Subscriptions'>add an Index Option to your QuantBook</a> before you can request historical data for Index Option contracts.</p>

<h4>Data on Filtered Contracts</h4>
<p>To get the prices and volumes for all of the Index Option contracts that pass your filter during a specific period of time, call the <code class="csharp">OptionHistory</code><code class="python">option_history</code> method with the underlying Index <code>Symbol</code> object, a start <code class='csharp'>DateTime</code><code class='python'>datetime</code>, and an end <code class='csharp'>DateTime</code><code class='python'>datetime</code>.</p>

<div class='section-example-container'>
    <pre class='python'>option_history = qb.option_history(
    index_symbol, datetime(2024, 1, 1), datetime(2024, 1, 5), Resolution.MINUTE, 
    fill_forward=False, extended_market_hours=False
)</pre>
    <pre class='csharp'>var optionHistory = qb.OptionHistory(
    indexSymbol, new DateTime(2024, 1, 1), new DateTime(2024, 1, 5), Resolution.Minute, 
    fillForward: False, extendedMarketHours: False
);</pre>
</div>


<h4>Daily Data on All Contracts</h4>
<p>
    To get daily data on all the tradable contracts for a given date, call the <code class="csharp">History&lt;OptionUniverse&gt;</code><code class="python">history</code> method with the canoncial Option Symbol, a start date, and an end date. 
    This method returns the entire Option chain for each trading day, not the subset of contracts that pass your universe filter. 
    The daily Option chains contain the prices, volume, open interest, implied volaility, and Greeks of each contract.
</p>

<div class='section-example-container'>
    <pre class='python'>history = qb.history(option.symbol, datetime(2024, 1, 1), datetime(2024, 1, 5))</pre>
    <pre class='csharp'>var history = qb.History&lt;OptionUniverse&gt;(option.Symbol, new DateTime(2024, 1, 1), new DateTime(2024, 1, 5));
foreach (var chain in history)
{
    var endTime = chain.EndTime;
    var filteredContracts = chain.Data
        .Select(contract => contract as OptionUniverse)
        .Where(contract => contract.Greeks.Delta > 0.3m);
    foreach (var contract in filteredContracts)
    {
        var price = contract.Price;
        var iv = contract.ImpliedVolatility;
    }
}</pre>
</div>

<p class='csharp'>The method represents each contract with an <code>OptionUniverse</code> object, which have the following properties:</p>
<div class='csharp' data-tree="QuantConnect.Data.UniverseSelection.OptionUniverse"></div>
