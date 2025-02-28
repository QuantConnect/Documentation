<p>
  To get historical <a href='/docs/v2/writing-algorithms/indicators/key-concepts'>indicator</a> values, call the <code class='csharp'>IndicatorHistory</code><code class='python'>indicator_history</code> method with an indicator and the asset's <code>Symbol</code>.
</p>

<div class="section-example-container">
    <pre class="csharp">public class <?=$assetClass?>IndicatorHistoryAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        SetStartDate(2024, 12, 19);
        // Get the Symbol of an asset.
        var symbol = <?=$symbolC?>;
        // Get the 21-day SMA values of an asset for the last 5 trading days. 
        var history = IndicatorHistory(new SimpleMovingAverage(21), symbol, 5, Resolution.Daily);
        // Get the maximum of the SMA values.
        var maxSMA = history.Max(indicatorDataPoint => indicatorDataPoint.Current.Value);
    }
}</pre>
    <pre class="python">class <?=$assetClass?>IndicatorHistoryAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 12, 19)
        # Get the Symbol of an asset.
        symbol = <?=$symbolPy?>

        # Get the 21-day SMA values of an asset for the last 5 trading days. 
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
	<pre class="csharp">// Get the historical values of an indicator over the last 30 days, applying the indicator to the asset's volume.
var history = IndicatorHistory(indicator, symbol, TimeSpan.FromDays(30), selector: Field.Volume);</pre>
	<pre class="python"># Get the historical values of an indicator over the last 30 days, applying the indicator to the asset's volume.
history = self.indicator_history(indicator, symbol, timedelta(30), selector=Field.VOLUME)</pre>
</div>

<p>
    Some indicators require the prices of two assets to compute their value (for example, <a href='https://www.quantconnect.com/docs/v2/writing-algorithms/indicators/supported-indicators/beta'>Beta</a>).
    In this case, pass a list of the <code>Symbol</code> objects to the method.
</p>
<div class="section-example-container">
	<pre class="csharp">public class <?=$assetClass?>MultiAssetIndicatorHistoryAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        SetStartDate(2024, 12, 19);
        // Add the target and reference assets.
        var targetSymbol = <?=$targetSymbolC?>;
        var referenceSymbol = <?=$symbolC?>;
        // Create a 21-period Beta indicator.
        var beta = new Beta("", targetSymbol, referenceSymbol, 21);
        // Get the historical values of the indicator over the last 10 trading days.
        var history = IndicatorHistory(beta, new[] {targetSymbol, referenceSymbol}, 10, Resolution.Daily);
        // Get the average Beta value.
        var avgBeta = history.Average(indicatorDataPoint => indicatorDataPoint.Current.Value);
    }
}</pre>
	<pre class="python">class <?=$assetClass?>MultiAssetIndicatorHistoryAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 12, 19)
        # Add the target and reference assets.
        target_symbol = <?=$targetSymbolPy?>

        reference_symbol = <?=$symbolPy?>

        # Create a 21-period Beta indicator.
        beta = Beta("", target_symbol, reference_symbol, 21)
        # Get the historical values of the indicator over the last 10 trading days.
        history = self.indicator_history(beta, [target_symbol, reference_symbol], 10, Resolution.DAILY)
        # Get the average Beta value.
        beta_avg = history.data_frame.mean()</pre>
</div>

<p class='csharp'>If you already have a list of <a href='/docs/v2/writing-algorithms/key-concepts/time-modeling/timeslices'>Slice</a> objects, you can pass them to the <code class="csharp">IndicatorHistory</code><code class="python">indicator_history</code> method to avoid the internal history request.</p>
<div class="csharp section-example-container">
	<pre class="csharp">var slices = History(new[] {symbol}, 30, Resolution.Daily);
var history = IndicatorHistory(indicator, slices);</pre>
</div>
