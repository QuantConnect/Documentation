<p>
    The <a href="/docs/v2/writing-algorithms/universes/index-options#05-Filter-by-Other-Contract-Properties">contract filter</a> determines which Future Option contracts are in your universe each trading day.
    The default filter selects the contracts with the following characteristics:
</p>
<? $annotation = "weeklies and non-standard contracts are not available"; include(DOCS_RESOURCES."/universes/option/default-filter.php"); ?>

<p>To get the prices and volumes for all of the Future Option contracts that pass your filter during a specific period of time, get the underlying Future contract and then call the <code class="csharp">OptionHistory</code><code class="python">option_history</code> method with the Future contract's <code>Symbol</code> object, a start <code class='csharp'>DateTime</code><code class='python'>datetime</code>, and an end <code class='csharp'>DateTime</code><code class='python'>datetime</code>.</p>

<div class='section-example-container'>
    <pre class='python'>start_date = datetime(2024, 1, 1)

# Select an underlying Futures contract. For example, get the front-month contract.
chain = list(qb.history[FutureUniverse](future.symbol, start_date, start_date+timedelta(2))[0]
futures_contract = list(chain)[0].symbol

# Get the Options data for the selected Futures contract.
option_history = qb.option_history(
    futures_contract, start_date, futures_contract.id.date, Resolution.HOUR, 
    fill_forward=False, extended_market_hours=False
)</pre>
    <pre class='csharp'>var startDate = new DateTime(2024, 1, 1);

// Select an underlying Futures contract. For example, get the front-month contract.
var chain = qb.History&lt;FutureUniverse&gt;(future.Symbol, startDate, startDate.AddDays(2)).First();
var futuresContract = chain.First().Symbol;

// Get the Options data for the selected Futures contract.
var optionHistory = qb.OptionHistory(
    futuresContract, startDate, futuresContract.ID.Date, Resolution.Hour, 
    fillForward: false, extendedMarketHours: false
);</pre>
</div>

<? 
$dataFrameImg = "https://cdn.quantconnect.com/i/tu/future-options-uiverse-history-data-frame-python.png";
$expiryDatesImg = "https://cdn.quantconnect.com/i/tu/future-options-uiverse-history-get-expiry-dates-python.png";
$strikesImg = "https://cdn.quantconnect.com/i/tu/future-options-uiverse-history-get-strikes-python.png";
include(DOCS_RESOURCES."/universes/option/option-history-object.php"); 
?>
