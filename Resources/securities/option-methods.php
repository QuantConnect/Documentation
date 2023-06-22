<p>The <code>Option</code> object provides method you can use for basic calculations.</p>

<p>To get the Option payoff, call the <code>GetPayOff</code> method:</p>

<div class="section-example-container">
    <pre class="csharp">var option = Securities[<?=$csSymbol?>];
var underlyingPrice = Securities[<?=$csSymbol?>.Underlying].Price;
var payOff = option.GetPayOff(underlyingPrice);
    </pre>
    <pre class="python">option = self.Securities[<?=$pySymbol?>]
underlying_price = self.Securities[<?=$pySymbol?>.Underlying].Price
pay_off = option.GetPayOff(underlying_price)
    </pre>
</div>

<p>To get the Option intrinsic value, call the <code>GetIntrinsicValue</code> method:</p>

<div class="section-example-container">
    <pre class="csharp">var option = Securities[<?=$csSymbol?>];
var underlyingPrice = Securities[<?=$csSymbol?>.Underlying].Price;
var intrinsicValue = option.GetIntrinsicValue(underlyingPrice);
    </pre>
    <pre class="python">option = self.Securities[<?=$pySymbol?>]
underlying_price = self.Securities[<?=$pySymbol?>.Underlying].Price
intrinsic_value = option.GetIntrinsicValue(underlying_price)
    </pre>
</div>

<p>To get the Option out-of-money ammount, call the <code>OutOfTheMoneyAmount</code> method:</p>

<div class="section-example-container">
    <pre class="csharp">var option = Securities[<?=$csSymbol?>];
var underlyingPrice = Securities[<?=$csSymbol?>.Underlying].Price;
var otmAmount = option.OutOfTheMoneyAmount(underlyingPrice);
    </pre>
    <pre class="python">option = self.Securities[<?=$pySymbol?>]
underlying_price = self.Securities[<?=$pySymbol?>.Underlying].Price
otm_amount = option.OutOfTheMoneyAmount(underlying_price)
    </pre>
</div>

<p>To check whether the Option can be automatic exercised, call the <code>IsAutoExercised</code> method:</p>

<div class="section-example-container">
    <pre class="csharp">var option = Securities[<?=$csSymbol?>];
var underlyingPrice = Securities[<?=$csSymbol?>.Underlying].Price;
var isAutoExercised = option.IsAutoExercised(underlyingPrice);
    </pre>
    <pre class="python">option = self.Securities[<?=$pySymbol?>]
underlying_price = self.Securities[<?=$pySymbol?>.Underlying].Price
is_auto_exercised = option.IsAutoExercised(underlying_price)
    </pre>
</div>