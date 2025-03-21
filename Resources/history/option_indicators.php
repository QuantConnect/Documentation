<p>
  To get historical <a href='/docs/v2/writing-algorithms/indicators/key-concepts'>indicator</a> values, call the <code class='csharp'>IndicatorHistory</code><code class='python'>indicator_history</code> method with an indicator and the security's <code>Symbol</code>.
</p>

<div class="section-example-container">
    <pre class="csharp">public class <?=$assetClass?>IndicatorHistoryAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        SetStartDate(2024, 12, 19);
        // Get the Symbol of the Option contract.
        var underlying = <?=$underlyingSymbolC?>;
        var symbol = OptionChain(underlying).OrderBy(c => c.OpenInterest).Last().Symbol;
        // Get the 21-day SMA values of the contract for the last 5 trading days. 
        var history = IndicatorHistory(new SimpleMovingAverage(21), symbol, 5, Resolution.Daily);
        // Get the maximum of the SMA values.
        var maxSMA = history.Max(indicatorDataPoint => indicatorDataPoint.Current.Value);
    }
}</pre>
    <pre class="python">class <?=$assetClass?>IndicatorHistoryAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 12, 19)
        # Get the Symbol of the Option contract.
        underlying = <?=$underlyingSymbolPy?>

        symbol = sorted(self.option_chain(underlying), key=lambda c: c.open_interest)[-1].symbol
        # Get the 21-day SMA values of the contract for the last 5 trading days. 
        history = self.indicator_history(SimpleMovingAverage(21), symbol, 5, Resolution.DAILY)</pre>
</div>

<p class='python'>To organize the data into a DataFrame, use the <code>data_frame</code> property of the result.</p>

<div class="python section-example-container">
    <pre class="python"># Organize the historical indicator data into a DataFrame to enable pandas wrangling.
history_df = history.data_frame</pre>
</div>

<?=$dataFrame?>

<div class="python section-example-container">
    <pre class="python"># Get the maximum of the SMA values.
sma_max = history_df.current.max()</pre>
</div>

<p>
  The <code class='csharp'>IndicatorHistory</code><code class='python'>indicator_history</code> method resets your indicator, makes a history request, and updates the indicator with the historical data.
	Just like with regular history requests, the <code class="csharp">IndicatorHistory</code><code class="python">indicator_history</code> method supports time periods based on a trailing number of bars, a trailing period of time, or a defined period of time.
	If you don't provide a <code>resolution</code> argument, it defaults to match the resolution of the security subscription.  
</p>

<p>
  To make the <code class="csharp">IndicatorHistory</code><code class="python">indicator_history</code> method update the indicator with an <a href='/docs/v2/writing-algorithms/indicators/automatic-indicators#07-Alternative-Price-Fields'>alternative price field</a> instead of the close (or mid-price) of each bar, pass a <code>selector</code> argument.
</p>


<div class="section-example-container">
	<pre class="csharp">// Get the historical values of an indicator over the last 30 days, applying the indicator to the security's volume.
var history = IndicatorHistory(indicator, symbol, TimeSpan.FromDays(30), selector: Field.Volume);</pre>
	<pre class="python"># Get the historical values of an indicator over the last 30 days, applying the indicator to the security's volume.
history = self.indicator_history(indicator, symbol, timedelta(30), selector=Field.VOLUME)</pre>
</div>

<p>
    Some indicators require the prices of multiple securities to compute their value (for example, the <a href='<?=$indicatorLink?>'>indicators for the Greeks and implied volatility</a>).
    In this case, pass a list of the <code>Symbol</code> objects to the method.
</p>
<div class="section-example-container">
	<pre class="csharp">public class <?=$assetClass?>MultiAssetIndicatorHistoryAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        SetStartDate(2024, 12, 19);
        // Get the Symbol of the underlying asset.
        var underlying = <?=$underlyingSymbolC?>;
        // Get the Option contract Symbol.
        var option = OptionChain(underlying).OrderBy(c => c.OpenInterest).Last().Symbol;
        // Get the Symbol of the mirror contract.
        var mirror = QuantConnect.Symbol.CreateOption(
            underlying.Value, option.ID.Market, option.ID.OptionStyle,
            option.ID.OptionRight == OptionRight.Put ? OptionRight.Call : OptionRight.Put,
            option.ID.StrikePrice, option.ID.Date
        );
        // Create the indicator.
        var indicator = new ImpliedVolatility(option, RiskFreeInterestRateModel, new DividendYieldProvider(underlying), mirror, OptionPricingModelType.ForwardTree);
        // Get the historical values of the indicator over the last 10 trading minutes
        var history = IndicatorHistory(indicator, new[] {underlying, option, mirror}, 10, Resolution.Minute);
        // Get the average IV value.
        var avgIV = history.Average(indicatorDataPoint => indicatorDataPoint.Current.Value);
    }
}</pre>
	<pre class="python">class <?=$assetClass?>MultiAssetIndicatorHistoryAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 12, 19)
        # Get the Symbol of the underlying asset.
        underlying = <?=$underlyingSymbolPy?>

        # Get the Option contract Symbol.
        option = sorted(self.option_chain(underlying), key=lambda c: c.open_interest)[-1].symbol
        # Get the Symbol of the mirror contract.
        mirror = Symbol.create_option(
            underlying.value, option.id.market, option.id.option_style, 
            OptionRight.Call if option.id.option_right == OptionRight.PUT else OptionRight.PUT,
            option.id.strike_price, option.id.date
        )
        # Create the indicator.
        indicator = ImpliedVolatility(
            option, self.risk_free_interest_rate_model, DividendYieldProvider(underlying),
            mirror, OptionPricingModelType.FORWARD_TREE
        )
        # Get the historical values of the indicator over the last 10 minutes.
        history = self.indicator_history(indicator, [underlying, option, mirror], 10, Resolution.MINUTE)
        # Get the average IV value.
        iv_avg = history.data_frame.current.mean()</pre>
</div>

<p class='csharp'>If you already have a list of <a href='/docs/v2/writing-algorithms/key-concepts/time-modeling/timeslices'>Slice</a> objects, you can pass them to the <code class="csharp">IndicatorHistory</code><code class="python">indicator_history</code> method to avoid the internal history request.</p>
<div class="csharp section-example-container">
	<pre class="csharp">var slices = History(new[] {symbol}, 30, Resolution.Daily);
var history = IndicatorHistory(indicator, slices);</pre>
</div>
