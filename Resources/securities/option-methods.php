<p>The <code>Option</code> object provides methods you can use for basic calculations. These methods require the underlying price. To get the <code>Option</code> object and the <code>Security</code> object for its underlying in any function, use the Option <code>Symbol</code> to access the value in the <code>Securities</code> object:</p>

<div class="section-example-container">
    <pre class="csharp">var option = Securities[<?=$csSymbol?>];
var underlying = Securities[<?=$csSymbol?>.Underlying];
var underlyingPrice = underlying.Price;</pre>
    <pre class="python">option = self.Securities[<?=$pySymbol?>]
underlying = self.Securities[<?=$pySymbol?>.Underlying]
underlying_price = underlying.Price</pre>
</div>

<p>To get the Option payoff, call the <code>GetPayOff</code> method:</p>

<div class="section-example-container">
    <pre class="csharp">var payOff = option.GetPayOff(underlyingPrice);</pre>
    <pre class="python">pay_off = option.GetPayOff(underlying_price)</pre>
</div>

<p>To get the Option intrinsic value, call the <code>GetIntrinsicValue</code> method:</p>

<div class="section-example-container">
    <pre class="csharp">var intrinsicValue = option.GetIntrinsicValue(underlyingPrice);</pre>
    <pre class="python">intrinsic_value = option.GetIntrinsicValue(underlying_price)</pre>
</div>

<p>To get the Option out-of-money ammount, call the <code>OutOfTheMoneyAmount</code> method:</p>

<div class="section-example-container">
    <pre class="csharp">var otmAmount = option.OutOfTheMoneyAmount(underlyingPrice);</pre>
    <pre class="python">otm_amount = option.OutOfTheMoneyAmount(underlying_price)</pre>
</div>

<p>To check whether the Option can be automatic exercised, call the <code>IsAutoExercised</code> method:</p>

<div class="section-example-container">
    <pre class="csharp">var isAutoExercised = option.IsAutoExercised(underlyingPrice);</pre>
    <pre class="python">is_auto_exercised = option.IsAutoExercised(underlying_price)</pre>
</div>