<p class='python'>To convert the <code>OptionHistory</code> object to a <code>DataFrame</code> that contains the trade and quote information of each contract and the underlying, use the <code>data_frame</code> property.</p>
<div class='python section-example-container'>
    <pre class='python'>option_history.data_frame</pre>
</div>
<img class='python' src='<?=$dataFrameImg?>' alt='DataFrame of Options data'>

<p class='python'>To get the expiration dates of all the contracts in an <code>OptionHistory</code> object, call the <code class='csharp'>GetExpiryDates</code><code class='csharp'>get_expiry_dates</code> method.</p>
<div class='python section-example-container'>
    <pre class='python'>option_history.get_expiry_dates()</pre>
</div>
<img class='python' src='<?=$expiryDatesImg?>' alt='list of expiry dates'>

<p class='python'>To get the strike prices of all the contracts in an <code>OptionHistory</code> object, call the <code class='csharp'>GetStrikes</code><code class='csharp'>get_strikes</code> method.</p>
<div class='python section-example-container'>
    <pre class='python'>option_history.get_strikes()</pre>
</div>
<img class='python' src='<?=$strikesImg?>' alt='List of strike prices'>
