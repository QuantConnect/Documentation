<p>
    The <a href="/docs/v2/writing-algorithms/universes/index-options#05-Filter-by-Other-Contract-Properties">contract filter</a> determines which Future Option contracts are in your universe each trading day.
    The default filter selects the contracts with the following characteristics:
</p>
<? $annotation = "weeklies and non-standard contracts are not available"; include(DOCS_RESOURCES."/universes/option/default-filter.php"); ?>

<p>To get the prices and volumes for all of the Future Option contracts that pass your filter during a specific period of time, get the underlying Future contract and then call the <code class="csharp">OptionHistory</code><code class="python">option_history</code> method with the Future contract's <code>Symbol</code> object, a start <code class='csharp'>DateTime</code><code class='python'>datetime</code>, and an end <code class='csharp'>DateTime</code><code class='python'>datetime</code>.</p>

<div class='section-example-container'>
    <pre class='python'>start_date = datetime(2024, 1, 1)

# Select an underlying Futures contract. For example, get the front-month contract.
chain = list(qb.history[FutureUniverse](future.symbol, start_date, start_date+timedelta(2)))[0]
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
$dataFrameImg = "";
$dataFrame = "
<div class='dataframe-wrapper'>
<table class='dataframe python'>
  <thead>
    <tr style='text-align: right;'>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th>askclose</th>
      <th>askhigh</th>
      <th>asklow</th>
      <th>askopen</th>
      <th>asksize</th>
      <th>bidclose</th>
      <th>bidhigh</th>
      <th>bidlow</th>
      <th>bidopen</th>
      <th>bidsize</th>
      <th>close</th>
      <th>high</th>
      <th>low</th>
      <th>open</th>
      <th>volume</th>
    </tr>
    <tr>
      <th>expiry</th>
      <th>strike</th>
      <th>type</th>
      <th>symbol</th>
      <th>time</th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th rowspan='7' valign='top'>2024-03-15</th>
      <th rowspan='3' valign='top'>4575.0</th>
      <th rowspan='3' valign='top'>0</th>
      <th rowspan='3' valign='top'>ES YGT6I1W1ONB8|ES YGT6HGVF2SQP</th>
      <th>2024-01-02 10:00:00</th>
      <td>262.0</td>
      <td>316.50</td>
      <td>248.75</td>
      <td>256.0</td>
      <td>1.0</td>
      <td>247.25</td>
      <td>257.25</td>
      <td>187.25</td>
      <td>253.50</td>
      <td>1.0</td>
      <td>254.625</td>
      <td>286.875</td>
      <td>218.000</td>
      <td>254.750</td>
      <td>NaN</td>
    </tr>
    <tr>
      <th>2024-01-02 11:00:00</th>
      <td>250.5</td>
      <td>300.00</td>
      <td>248.25</td>
      <td>262.0</td>
      <td>2.0</td>
      <td>248.50</td>
      <td>256.50</td>
      <td>215.00</td>
      <td>247.25</td>
      <td>2.0</td>
      <td>249.500</td>
      <td>278.250</td>
      <td>231.625</td>
      <td>254.625</td>
      <td>NaN</td>
    </tr>
    <tr>
      <th>2024-01-02 12:00:00</th>
      <td>261.0</td>
      <td>300.00</td>
      <td>250.00</td>
      <td>250.5</td>
      <td>1.0</td>
      <td>259.25</td>
      <td>259.75</td>
      <td>216.25</td>
      <td>248.50</td>
      <td>1.0</td>
      <td>260.125</td>
      <td>279.875</td>
      <td>233.125</td>
      <td>249.500</td>
      <td>NaN</td>
    </tr>
    <tr>
      <th>...</th>
      <th>...</th>
      <th>...</th>
      <th>...</th>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
    </tr>
    <tr>
      <th rowspan='3' valign='top'>5215.0</th>
      <th rowspan='3' valign='top'>1</th>
      <th rowspan='3' valign='top'>ES 32FX6NKT3COAS|ES YGT6HGVF2SQP</th>
      <th>2024-03-14 15:00:00</th>
      <td>70.5</td>
      <td>92.25</td>
      <td>58.00</td>
      <td>64.5</td>
      <td>2.0</td>
      <td>69.75</td>
      <td>70.25</td>
      <td>36.75</td>
      <td>52.00</td>
      <td>1.0</td>
      <td>70.125</td>
      <td>81.250</td>
      <td>47.375</td>
      <td>58.250</td>
      <td>NaN</td>
    </tr>
    <tr>
      <th>2024-03-14 16:00:00</th>
      <td>75.0</td>
      <td>183.50</td>
      <td>63.25</td>
      <td>70.5</td>
      <td>10.0</td>
      <td>44.75</td>
      <td>89.75</td>
      <td>2.10</td>
      <td>69.75</td>
      <td>10.0</td>
      <td>67.750</td>
      <td>67.750</td>
      <td>67.750</td>
      <td>67.750</td>
      <td>1.0</td>
    </tr>
    <tr>
      <th>2024-03-14 17:00:00</th>
      <td>63.5</td>
      <td>84.00</td>
      <td>58.00</td>
      <td>75.0</td>
      <td>1.0</td>
      <td>58.50</td>
      <td>61.50</td>
      <td>35.50</td>
      <td>44.75</td>
      <td>1.0</td>
      <td>61.000</td>
      <td>72.750</td>
      <td>46.750</td>
      <td>59.875</td>
      <td>NaN</td>
    </tr>
  </tbody>
</table>
</div>
";
$expiryDatesImg = "https://cdn.quantconnect.com/i/tu/future-options-uiverse-history-get-expiry-dates-python.png";
$strikesImg = "https://cdn.quantconnect.com/i/tu/future-options-uiverse-history-get-strikes-python.png";
include(DOCS_RESOURCES."/universes/option/option-history-object.php"); 
?>
