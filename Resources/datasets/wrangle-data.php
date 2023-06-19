<p class='python'>You need some <a href='<?=$historicalDataLink ?>'>historical data</a> to perform wrangling operations. The process to manipulate the historical data depends on its data type. To display <code>pandas</code> objects, run a cell in a notebook with the <code>pandas</code> object as the last line. To display other data formats, call the <code>print</code> method.</p>

<p class='csharp'>You need some <a href='<?=$historicalDataLink ?>'>historical data</a> to perform wrangling operations. Use LINQ to wrangle the data and then call the <code>Console.WriteLine</code> method in a Jupyter Notebook to display the data. The process to manipulate the historical data depends on its data type.</p>

<h4>DataFrame Objects</h4>

<? if ($supportsOptionHistory) { ?>
<p class='python'>If your history request returns a <code>DataFrame</code>, the <code>DataFrame</code> has the following index levels:</p>
<ol class='python'>
    <li>Contract expiry</li>
    <li>Contract strike price</li>
    <li>Contract type (call or put)</li>
    <li><a href='/docs/v2/writing-algorithms/key-concepts/security-identifiers#02-Encoding-Symbols'>Encoded contract Symbol</a></li>
    <li>The <code>EndTime</code> of the data sample</li>
</ol>
<p class='python'>The columns of the <code>DataFrame</code> are the data properties. Depending on how you request data, the <code>DataFrame</code> may contain data for the underlying security, which causes some of the index levels to be an empty string for the corresponding rows.</p>
<? } ?> 


<? if ($supportsFutureHistory) { ?>
<p class='python'>If your history request returns a <code>DataFrame</code>, the <code>DataFrame</code> has the following index levels:</p>
<ol class='python'>
    <li>Contract expiry</li>
    <li><a href='/docs/v2/writing-algorithms/key-concepts/security-identifiers#02-Encoding-Symbols'>Encoded contract Symbol</a></li>
    <li>The <code>EndTime</code> of the data sample</li>
</ol>
<p class='python'>The columns of the <code>DataFrame</code> are the data properties. Depending on how you request data, the <code>DataFrame</code> may contain data for the continuous Futures contract. The continuous contract doesn't expire, so the default expiry date of December 30, 1899 doesn't have any practical meaning.</p>
<? } ?>

<? if ($supportsOptionHistory || $supportsFutureHistory) { ?>
<img class='python docs-image' src='<?=$dataFrameImages[0] ?>' alt="Historical data dataframe">
<p class='python'>To select the rows of the contract(s) that expire at a specific time, index the <code>loc</code> property of the <code>DataFrame</code> with the expiry time.</p>
<div class='python section-example-container'>
    <pre class='python'>all_history_df.loc[<?=$contractExpiryDate?>]</pre>
</div>
<img class='python docs-image' src='<?=$dataFrameImages[1]?>' alt="Historical data dataframe of selected date">
<? } ?>

<? if ($supportsOptionHistory) { ?>
<p class='python'>If you remove the first three index levels, you can index the <code>DataFrame</code> with just the contract <code>Symbol</code>, similiar to how you would with non-derivative asset classes. To remove the first three index levels, call the <a href='https://pandas.pydata.org/docs/reference/api/pandas.Index.droplevel.html' rel='nofollow' target='_blank'>droplevel</a> method.</p>
<div class='python section-example-container'>
<pre class='python'>all_history_df.index = all_history_df.index.droplevel([0,1,2])</pre>
</div>
<? } ?>

<? if ($supportsFutureHistory) { ?>
<p class='python'>If you remove the first index level, you can index the <code>DataFrame</code> with just the contract <code>Symbol</code>, similiar to how you would with non-derivative asset classes. To remove the first index level, call the <a href='https://pandas.pydata.org/docs/reference/api/pandas.Index.droplevel.html' rel='nofollow' target='_blank'>droplevel</a> method.</p>
<div class='python section-example-container'>
<pre class='python'>all_history_df.index = all_history_df.index.droplevel(0)</pre>
</div>
<? } ?>

<? if ($supportsOptionHistory || $supportsFutureHistory) { ?>

<img class='python docs-image' src='<?=$dataFrameImages[2]?>' alt="Historical data dataframe of dropped first column">
<p class='python'>To select the historical data of a single <?=$assetClass ?> contract, index the <code>loc</code> property of the <code>DataFrame</code> with the contract <code>Symbol</code>.</p>
<div class='python section-example-container'>
    <pre class='python'>all_history_df.loc[<?=$primarySymbolPy?>]
</pre>
</div>
<img class='python docs-image' src='<?=$dataFrameImages[3]?>' alt='DataFrame of one <?=$assetClass ?>'>
<p class='python'>To select a column of the <code>DataFrame</code>, index it with the column name.</p>
<div class='python section-example-container'>
    <pre class='python'>all_history_df.loc[<?=$primarySymbolPy?>]['<?=$dataFrameColumnName ?>']
</pre>
</div>
<img class='python docs-image' src='<?=$dataFrameImages[4]?>' alt='Series of <?=$dataFrameColumnName?> values'>
<p class='python'>If you request historical data for multiple <?=$pluralAssetClass ?>, you can transform the <code>DataFrame</code> so that it's a time series of <?=$columnNameEnglish ?> values for all of the <?=$pluralAssetClass ?>. To transform the <code>DataFrame</code>, select the column you want to display for each <?=$singularAssetClass?> and then call the <a href='https://pandas.pydata.org/docs/reference/api/pandas.DataFrame.unstack.html' rel='nofollow' target='_blank'>unstack</a> method.</p>
<div class='section-example-container python'>
    <pre class='python'>all_history_df['<?=$dataFrameColumnName ?>'].unstack(level=0)</pre>
</div>
<p class='python'>The <code>DataFrame</code> is transformed so that the column indices are the <code>Symbol</code> of each security and each row contains the <?=$columnNameEnglish?>  value.</p>
<img class='python docs-image' src='<?=$dataFrameImages[5]?>' alt="Historical data dataframe with column indexed by symbols">

<? } else { ?>

<p class='python'>If the <code>History</code> method returns a <code>DataFrame</code>, the first level of the <code>DataFrame</code> index is the <a href='/docs/v2/writing-algorithms/key-concepts/security-identifiers#02-Encoding-Symbols'>encoded <?=$assetClass?> Symbol</a> and the second level is the <code>EndTime</code> of the data sample. The columns of the <code>DataFrame</code> are the data properties.</p>
<img class='python docs-image' src='<?=$dataFrameImages[0]?>' alt='DataFrame of two <?=$pluralAssetClass?>'>
<p class='python'>To select the historical data of a single <?=$assetClass?>, index the <code>loc</code> property of the <code>DataFrame</code> with the <?=$assetClass?> <code>Symbol</code>.</p>
<div class='python section-example-container'>
<pre class='python'>all_history_df.loc[<?=$primarySymbolPy?>]  # or all_history_df.loc['<?=$primaryTicker?>']
</pre>
</div>
<img class='python docs-image' src='<?=$dataFrameImages[1]?>' alt='DataFrame of one <?=$assetClass?>'>
<p class='python'>To select a column of the <code>DataFrame</code>, index it with the column name.</p>
<div class='python section-example-container'>
<pre class='python'>all_history_df.loc[<?=$primarySymbolPy?>]['<?=$dataFrameColumnName?>']
</pre>
</div>
<img class='python docs-image' src='<?=$dataFrameImages[2]?>' alt='Series of <?=$dataFrameColumnName?> values'>
<p class='python'>If you request historical data for multiple <?=$pluralAssetClass?>, you can transform the <code>DataFrame</code> so that it's a time series of <?=$columnNameEnglish?> values for all of the <?=$pluralAssetClass?>. To transform the <code>DataFrame</code>, select the column you want to display for each <?=$singularAssetClass?> and then call the <a href='https://pandas.pydata.org/docs/reference/api/pandas.DataFrame.unstack.html' rel='nofollow' target='_blank'>unstack</a> method.</p>
<div class='section-example-container python'>
    <pre class='python'>all_history_df['<?=$dataFrameColumnName?>'].unstack(level=0)</pre>
</div>
<p class='python'>The <code>DataFrame</code> is transformed so that the column indices are the <code>Symbol</code> of each <?=$singularAssetClass?> and each row contains the <?=$columnNameEnglish?> value.</p>
<img class='python docs-image' src='<?=$dataFrameImages[3]?>' alt='DataFrame of one <?=$assetClass ?>'>

<? } ?>


<? if ($assetClass == "Equity") { ?>
<p class='python'>
    If you prefer to display the ticker of each <code>Symbol</code> instead of the string representation of the <code>SecurityIdentifier</code>, follow these steps:
</p>
<ol class='python'>
  <li>Create a dictionary where the keys are the string representations of each <code>SecurityIdentifier</code> and the values are the ticker.</li>
  <div class='section-example-container python'>
    <pre class='python'>tickers_by_id = {str(x.ID): x.Value for x in qb.Securities.Keys}</pre>
  </div>
  <li>Get the values of the symbol level of the <code>DataFrame</code> index and create a list of tickers.</li>
  <div class='section-example-container python'>
    <pre class='python'>tickers = set([tickers_by_id[x] for x in all_history_df.index.get_level_values('symbol')])</pre>
  </div>
  <li>Set the values of the symbol level of the <code>DataFrame</code> index to the list of tickers.</li>
  <div class='section-example-container python'>
    <pre class='python'>all_history_df.index.set_levels(tickers, 'symbol', inplace=True)</pre>
  </div>
</ol>
<p class='python'>The new <code>DataFrame</code> is keyed by the ticker.</p>
<div class='section-example-container python'>
  <pre class='python'>all_history_df.loc[spy.Value]  # or all_history_df.loc["SPY"]  </pre>
</div>
<p class='python'>After the index renaming, the unstacked <code>DataFrame</code> has the following format:</p>
<img class='python docs-image' src='https://cdn.quantconnect.com/i/tu/us-equity-research-data-5.jpg' alt="Historical data dataframe of selected attribute by symbols">
<? } ?>

<p class="csharp">The historical data methods don't return DataFrame objects, but you can create one for efficient vectorized data wrangling.</p>
<div class='csharp section-example-container'>
<pre class='csharp'>using Microsoft.Data.Analysis; 

var columns = new DataFrameColumn[] {
    new PrimitiveDataFrameColumn<DateTime>("Time", history.Select(x => x[<?=$primarySymbolC?>].EndTime)),
    new DecimalDataFrameColumn("<?=$primaryTicker?> Open", history.Select(x => x[<?=$primarySymbolC?>].Open)),
    new DecimalDataFrameColumn("<?=$primaryTicker?> High", history.Select(x => x[<?=$primarySymbolC?>].High)),
    new DecimalDataFrameColumn("<?=$primaryTicker?> Low", history.Select(x => x[<?=$primarySymbolC?>].Low)),
    new DecimalDataFrameColumn("<?=$primaryTicker?> Close", history.Select(x => x[<?=$primarySymbolC?>].Close))
};
var df = new DataFrame(columns);
df</pre>
</div>
<img class='csharp docs-image' src='<?=$cSharpDataFrameImages[0]?>' alt="Historical C# dataframe">
<p class='csharp'>To select a particular column of the DataFrame, index it with the column name.</p>
<div class='section-example-container'>
<pre class='csharp'>df["<?=$primaryTicker?> <?=$dataFrameColumnName?>"]</pre>
</div>
<img class='csharp docs-image' src='<?=$cSharpDataFrameImages[1]?>' alt="Historical C# dataframe column">

<h4>Slice Objects</h4>
<p>If the <code>History</code> method returns <code>Slice</code> objects, iterate through the <code>Slice</code> objects to get each one. The <code>Slice</code> objects may not have data for all of your <?=$assetClass?> subscriptions. To avoid issues, check if the <code>Slice</code> contains data for your <?=$singularAssetClass?> before you index it with the <?=$assetClass?> <code>Symbol</code>.</p>
<div class='csharp section-example-container'>
<pre class='csharp'>foreach (var slice in allHistorySlice) {
<? if ($supportsAltData) {?>
    if (slice.ContainsKey(<?=$primarySymbolC?>))
    {
        var data = slice[<?=$primarySymbolC?>];
    }
<? } ?>
<? if ($supportsTrades) {?>
    if (slice.Bars.ContainsKey(<?=$primarySymbolC?>))
    {
        var tradeBar = slice.Bars[<?=$primarySymbolC?>];
    }
<? } ?>
<? if ($supportsQuotes) {?>
    if (slice.QuoteBars.ContainsKey(<?=$primarySymbolC?>))
    {
        var quoteBar = slice.QuoteBars[<?=$primarySymbolC?>];
    }
<? } ?>
}</pre>
<pre class='python'>for slice in all_history_slice:
    <? if ($supportsAltData) {?>
    if slice.ContainsKey(<?=$primarySymbolPy?>):
        data = slice[<?=$primarySymbolPy?>]
<? } ?>
<? if ($supportsTrades) {?>
    if slice.Bars.ContainsKey(<?=$primarySymbolPy?>):
        trade_bar = slice.Bars[<?=$primarySymbolPy?>]
<? } ?>
<? if ($supportsQuotes) {?>
    if slice.QuoteBars.ContainsKey(<?=$primarySymbolPy?>):
        quote_bar = slice.QuoteBars[<?=$primarySymbolPy?>]
<? } ?>
</pre>
</div>


<!---- ----->  


<? if (!$supportsAltData) { ?>
<? 
    if ($supportsTrades and $supportsQuotes) { 
        $b = "<code>TradeBar</code> and <code>QuoteBar</code>"; 
    } elseif ($supportsTrades) { 
        $b = "<code>TradeBar</code>"; 
    } else {
        $b = "<code>QuoteBar</code>";
    };
?>
<p>You can also iterate through each <?=$b?> in the <code>Slice</code>.</p>
<div class='section-example-container'>
<pre class='csharp'>foreach (var slice in allHistorySlice)
{
<? if ($supportsTrades) {?>
    foreach (var kvp in slice.Bars)
    {
        var symbol = kvp.Key;
        var tradeBar = kvp.Value;
    }
<? } ?>
<? if ($supportsQuotes) {?>
    foreach (var kvp in slice.QuoteBars)
    {
        var symbol = kvp.Key;
        var quoteBar = kvp.Value;
    }
<? } ?>
}</pre>
<pre class='python'>for slice in all_history_slice:
<? if ($supportsTrades) { ?>
    for kvp in slice.Bars:
        symbol = kvp.Key
        trade_bar = kvp.Value
<? } ?>
<? if ($supportsQuotes) {?>
    for kvp in slice.QuoteBars:
        symbol = kvp.Key
        quote_bar = kvp.Value
<? } ?>
</pre>
</div>

<p class='csharp'>You can also use LINQ to select each <?=$supportsTrades ? "<code>TradeBar</code>" : "<code>QuoteBar</code>" ?> in the <code>Slice</code> for a given <code>Symbol</code>.</p>
<div class='csharp section-example-container'>
<pre class='csharp'>
<? if ($supportsTrades) { ?>
var tradeBars = allHistorySlice.Where(slice => slice.Bars.ContainsKey(<?=$primarySymbolC?>)).Select(slice => slice.Bars[<?=$primarySymbolC?>]);
<? } else { ?>
var quoteBars = allHistorySlice.Where(slice => slice.QuoteBars.ContainsKey(<?=$primarySymbolC?>)).Select(slice => slice.QuoteBars[<?=$primarySymbolC?>]);
<? } ?>
</pre>
</div>
<? } ?>


<!---- ----->

<? if ($supportsTrades) { ?>
    <h4>TradeBar Objects</h4>
<p>If the <code>History</code> method returns <code>TradeBar</code> objects, iterate through the <code>TradeBar</code> objects to get each one.</p>
<div class='section-example-container'>
<pre class='csharp'>foreach (var tradeBar in singleHistoryTradeBars)
{
    Console.WriteLine(tradeBar);
}</pre>
<pre class='python'>for trade_bar in single_history_trade_bars:
    print(trade_bar)</pre>
</div>
<p>If the <code>History</code> method returns <code>TradeBars</code>, iterate through the <code>TradeBars</code> to get the <code>TradeBar</code> of each <?=$singularAssetClass?>. The <code>TradeBars</code> may not have data for all of your <?=$assetClass?> subscriptions. To avoid issues, check if the <code>TradeBars</code> object contains data for your security before you index it with the <?=$assetClass?> <code>Symbol</code>.</p>
<div class='section-example-container'>
<pre class='csharp'>foreach (var tradeBars in allHistoryTradeBars)
{
    if (tradeBars.ContainsKey(<?=$primarySymbolC?>))
    {
        var tradeBar = tradeBars[<?=$primarySymbolC?>];
    }
}</pre>
<pre class='python'>for trade_bars in all_history_trade_bars:
    if trade_bars.ContainsKey(<?=$primarySymbolPy?>):
        trade_bar = trade_bars[<?=$primarySymbolPy?>]</pre>
</div>
<p>You can also iterate through each of the <code>TradeBars</code>.</p>
<div class='section-example-container'>
<pre class='csharp'>foreach (var tradeBars in allHistoryTradeBars)
{
    foreach (var kvp in tradeBars)
    {
        var symbol = kvp.Key;
        var tradeBar = kvp.Value;
    }
}</pre>
<pre class='python'>for trade_bars in all_history_trade_bars:
    for kvp in trade_bars:
        symbol = kvp.Key
        trade_bar = kvp.Value<br></pre>
</div>
<? } ?>


<!---- ----->

<? if ($supportsQuotes) { ?>
<h4>QuoteBar Objects</h4>
<p>If the <code>History</code> method returns <code>QuoteBar</code> objects, iterate through the <code>QuoteBar</code> objects to get each one.</p>
<div class='section-example-container'>
<pre class='csharp'>foreach (var quoteBar in singleHistoryQuoteBars)
{
    Console.WriteLine(quoteBar);
}</pre>
<pre class='python'>for quote_bar in single_history_quote_bars:
    print(quote_bar)</pre>
</div>
<p>If the <code>History</code> method returns <code>QuoteBars</code>, iterate through the <code>QuoteBars</code> to get the <code>QuoteBar</code> of each <?=$singularAssetClass?>. The <code>QuoteBars</code> may not have data for all of your <?=$assetClass?> subscriptions. To avoid issues, check if the <code>QuoteBars</code> object contains data for your security before you index it with the <?=$assetClass?> <code>Symbol</code>.</p>
<div class='section-example-container'>
<pre class='csharp'>foreach (var quoteBars in allHistoryQuoteBars)
{
    if (quoteBars.ContainsKey(<?=$primarySymbolC?>))
    {
        var quoteBar = quoteBars[<?=$primarySymbolC?>];
    }
}</pre>
<pre class='python'>for quote_bars in all_history_quote_bars:
    if quote_bars.ContainsKey(<?=$primarySymbolPy?>):
        quote_bar = quote_bars[<?=$primarySymbolPy?>]</pre>
</div>
<p>You can also iterate through each of the <code>QuoteBars</code>.</p>
<div class='section-example-container'>
<pre class='csharp'>foreach (var quoteBars in allHistoryQuoteBars)
{
    foreach (var kvp in quoteBars)
    {
        var symbol = kvp.Key;
        var quoteBar = kvp.Value;
    }
}</pre>
<pre class='python'>for quote_bars in all_history_quote_bars:
    for kvp in quote_bars:
        symbol = kvp.Key
        quote_bar = kvp.Value</pre>
</div>
<? } ?>


<!---- ----->

<? if ($supportsTicks) { ?>
<h4>Tick Objects</h4>
<p>If the <code>History</code> method returns <code>Tick</code> objects, iterate through the <code>Tick</code> objects to get each one.</p>
<div class='section-example-container'>
<pre class='csharp'>foreach (var tick in singleHistoryTicks)
{
    Console.WriteLine(tick);
}</pre>
<pre class='python'>for tick in single_history_ticks:
    print(tick)</pre>
</div>
<p>If the <code>History</code> method returns <code>Ticks</code>, iterate through the <code>Ticks</code> to get the <code>Tick</code> of each <?=$singularAssetClass?>. The <code>Ticks</code> may not have data for all of your <?=$assetClass?> subscriptions. To avoid issues, check if the <code>Ticks</code> object contains data for your security before you index it with the <?=$assetClass?> <code>Symbol</code>.</p>
<div class='section-example-container'>
<pre class='csharp'>foreach (var ticks in allHistoryTicks)
{
    if (ticks.ContainsKey(<?=$primarySymbolC?>))
    {
        var tick = ticks[<?=$primarySymbolC?>];
    }
}</pre>
<pre class='python'>for ticks in all_history_ticks:
    if ticks.ContainsKey(<?=$primarySymbolPy?>):
        ticks = ticks[<?=$primarySymbolPy?>]</pre>
</div>
<p>You can also iterate through each of the <code>Ticks</code>.</p>
<div class='section-example-container'>
<pre class='csharp'>foreach (var ticks in allHistoryTicks)
{
    foreach (var kvp in ticks)
    {
        var symbol = kvp.Key;
        var tick = kvp.Value;
    }
}</pre>
<pre class='python'>for ticks in all_history_ticks:
    for kvp in ticks:
        symbol = kvp.Key
        tick = kvp.Value</pre>
</div>
<p>The <code>Ticks</code> objects only contain the last tick of each security for that particular <a href='/docs/v2/writing-algorithms/key-concepts/time-modeling/timeslices'>timeslice</a></p>
<? } ?>


<!---- ----->

<? if ($supportsOpenInterest) { ?>
<h4>OpenInterest Objects</h4>
<p>If the <code>History</code> method returns <code>OpenInterest</code> objects, iterate through the <code>OpenInterest</code> objects to get each one.</p>
<div class='section-example-container'>
<pre class='csharp'>foreach (var openInterest in singleHistoryOpenInterest)
{
    Console.WriteLine(openInterest);
}</pre>
<pre class='python'>for open_interest in single_history_open_interest:
    print(open_interest)</pre>
</div>
<p>If the <code>History</code> method returns a dictionary of <code>OpenInterest</code> objects, iterate through the dictionary to get the <code>OpenInterest</code> of each <?=$singularAssetClass?>. The dictionary of <code>OpenInterest</code> objects may not have data for all of your <?=$assetClass?> contract subscriptions. To avoid issues, check if the dictionary contains data for your contract before you index it with the <?=$assetClass?> contract <code>Symbol</code>.</p>
<div class='section-example-container'>
<pre class='csharp'>foreach (var openInterestDict in allHistoryOpenInterest)
{
    if (openInterestDict.ContainsKey(<?=$primarySymbolC?>))
    {
        var openInterest = openInterestDict[<?=$primarySymbolC?>];
    }
}</pre>
<pre class='python'>for open_interest_dict in all_history_open_interest:
    if open_interest_dict.ContainsKey(<?=$primarySymbolPy?>):
        open_interest = open_interest_dict[<?=$primarySymbolPy?>]</pre>
</div>
<p>You can also iterate through each of the <code>OpenInterest</code> dictionaries.</p>
<div class='section-example-container'>
<pre class='csharp'>foreach (var openInterestDict in allHistoryOpenInterest)
{
    foreach (var kvp in openInterestDict)
    {
        var symbol = kvp.Key;
        var openInterest = kvp.Value;
    }
}</pre>
<pre class='python'>for open_interest_dict in all_history_open_interest:
    for kvp in open_interest_dict:
        symbol = kvp.Key
        open_interest = kvp.Value</pre>
</div>
<? } ?>


<!---- ----->

<? if ($supportsOptionHistory) { ?>
<h4>OptionHistory Objects</h4>
<p>The <code>GetOptionHistory</code> method returns an <code>OptionHistory</code> object. To get each <a href='/docs/v2/writing-algorithms/key-concepts/time-modeling/timeslices'>slice</a> in the <code>OptionHistory</code> object, iterate through it.</p>

<div class='section-example-container'>
<pre class='csharp'>foreach (var slice in optionHistory)
{
    foreach (var kvp in slice.OptionChains)
    {
        var canonicalSymbol = kvp.Key;
        var chain = kvp.Value;
        foreach (var contract in chain)
        {
            
        }
    }
}</pre>
<pre class='python'>for slice in option_history:
    for canonical_symbol, chain in slice.OptionChains.items(): 
        for contract in chain:
            pass</pre>
</div>

<p class='python'>To convert the <code>OptionHistory</code> object to a <code>DataFrame</code> that contains the trade and quote information of each contract and the underlying, call the <code>GetAllData</code> method.</p>
<div class='python section-example-container'>
    <pre class='python'>option_history.GetAllData()</pre>
</div>
<p class='python'>To get the expiration dates of all the contracts in an <code>OptionHistory</code> object, call the <code>GetExpiryDates</code> method.</p>
<div class='python section-example-container'>
    <pre class='python'>option_history.GetExpiryDates()</pre>
</div>
<p class='python'>To get the strike prices of all the contracts in an <code>OptionHistory</code> object, call the <code>GetStrikes</code> method.</p>
<div class='python section-example-container'>
    <pre class='python'>option_history.GetStrikes()</pre>
</div>
<? } ?>


<!---- ----->

<? if ($supportsFutureHistory) { ?>
<h4>FutureHistory Objects</h4>
<p>The <code>GetFutureHistory</code> method returns a <code>FutureHistory</code> object. To get each <a href='/docs/v2/writing-algorithms/key-concepts/time-modeling/timeslices'>slice</a> in the <code>FutureHistory</code> object, iterate through it.</p>

<div class='section-example-container'>
<pre class='csharp'>foreach (var slice in futureHistory)
{
    foreach (var kvp in slice.FuturesChains)
    {
        var continuousContractSymbol = kvp.Key;
        var chain = kvp.Value;
        foreach (var contract in chain)
        {
            
        }
    }
}</pre>
<pre class='python'>for slice in future_history:
    for continuous_contract_symbol, chain in slice.FuturesChains.items(): 
        for contract in chain:
            pass</pre>
</div>

<p class='python'>To convert the <code>FutureHistory</code> object to a <code>DataFrame</code> that contains the trade and quote information of each contract, call the <code>GetAllData</code> method.</p>
<div class='python section-example-container'>
    <pre class='python'>future_history.GetAllData()</pre>
</div>
<p class='python'>To get the expiration dates of all the contracts in an <code>FutureHistory</code> object, call the <code>GetExpiryDates</code> method.</p>
<div class='python section-example-container'>
    <pre class='python'>future_history.GetExpiryDates()</pre>
</div>
<? } ?>
