<p>The <code>Option</code> object provides methods you can use for basic calculations. These methods require the underlying price. To get the <code>Option</code> object and the <code>Security</code> object for its underlying in any function, use the Option <code class="csharp">Symbol</code><code class="python">symbol</code> to access the value in the <code class="csharp">Securities</code><code class="python">securities</code> object.</p>

<div class="section-example-container">
    <pre class="csharp">var option = Securities[<?=$csSymbol?>];
var underlying = Securities[<?=$csSymbol?>.Underlying];
var underlyingPrice = underlying.Price;</pre>
    <pre class="python">option = self.securities[<?=$pySymbol?>]
underlying = self.securities[<?=$pySymbol?>.underlying]
underlying_price = underlying.price</pre>
</div>

<p>To get the Option <a href='/docs/v2/writing-algorithms/key-concepts/glossary#22-payoff'>payoff</a>, call the <code class="csharp">GetPayOff</code><code class="python">get_pay_off</code> method.</p>

<div class="section-example-container">
    <pre class="csharp">var payOff = option.GetPayOff(underlyingPrice);</pre>
    <pre class="python">pay_off = option.get_pay_off(underlying_price)</pre>
</div>

<p>To get the Option <a href='/docs/v2/writing-algorithms/key-concepts/glossary#16-intrinsic-value'>intrinsic value</a>, call the <code class="csharp">GetIntrinsicValue</code><code class="python">get_intrinsic_value</code> method.</p>

<div class="section-example-container">
    <pre class="csharp">var intrinsicValue = option.GetIntrinsicValue(underlyingPrice);</pre>
    <pre class="python">intrinsic_value = option.get_intrinsic_value(underlying_price)</pre>
</div>

<p>To get the Option <a href='/docs/v2/writing-algorithms/key-concepts/glossary#21-out-of-the-money-amount'>out-of-the-money amount</a>, call the <code class="csharp">OutOfTheMoneyAmount</code><code class="python">out_of_the_money_amount</code> method.</p>

<div class="section-example-container">
    <pre class="csharp">var otmAmount = option.OutOfTheMoneyAmount(underlyingPrice);</pre>
    <pre class="python">otm_amount = option.out_of_the_money_amount(underlying_price)</pre>
</div>

<p>To check whether the Option can be automatic exercised, call the <code class="csharp">IsAutoExercised</code><code class="python">is_auto_exercised</code> method.</p>

<div class="section-example-container">
    <pre class="csharp">var isAutoExercised = option.IsAutoExercised(underlyingPrice);</pre>
    <pre class="python">is_auto_exercised = option.is_auto_exercised(underlying_price)</pre>
</div>