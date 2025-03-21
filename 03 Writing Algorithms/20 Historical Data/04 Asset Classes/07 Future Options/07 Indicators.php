<p>
  To get historical <a href='/docs/v2/writing-algorithms/indicators/key-concepts'>indicator</a> values, call the <code class='csharp'>IndicatorHistory</code><code class='python'>indicator_history</code> method with an indicator and the security's <code>Symbol</code>.
</p>

<div class="section-example-container">
    <pre class="csharp">public class FutureOptionIndicatorHistoryAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        SetStartDate(2024, 12, 19);
        // Add a FOP universe.
        var future = AddFuture(Futures.Indices.SP500EMini);
        future.SetFilter(universe => universe.FrontMonth());
        AddFutureOption(future.Symbol, universe => universe.FrontMonth().Strikes(0, 0).CallsOnly());
    }

    public override void OnSecuritiesChanged(SecurityChanges changes)
    {
        foreach (var security in changes.AddedSecurities)
        {
            if (security.Type != SecurityType.FutureOption)
            {
                continue;
            }
            // Get the 21-day SMA values of the contract for the last 5 trading days. 
            var history = IndicatorHistory(new SimpleMovingAverage(21), symbol, 5, Resolution.Daily);
            // Get the maximum of the SMA values.
            var maxSMA = history.Max(indicatorDataPoint => indicatorDataPoint.Current.Value);
        }
    }
}</pre>
    <pre class="python">class FutureOptionIndicatorHistoryAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 12, 19)
        # Add a FOP universe.
        future = self.add_future(Futures.Indices.SP_500_E_MINI)
        future.set_filter(lambda universe: universe.front_month())
        self.add_future_option(future.symbol, lambda universe: universe.front_month().strikes(0, 0).calls_only())

    def on_securities_changed(self, changes):
        for security in changes.added_securities:
            if security.type != SecurityType.FUTURE_OPTION:
                continue
            # Get the 21-day SMA values of the contract for the last 5 trading days. 
            history = self.indicator_history(SimpleMovingAverage(21), security.symbol, 5, Resolution.DAILY)</pre>
</div>

<p class='python'>To organize the data into a DataFrame, use the <code>data_frame</code> property of the result.</p>

<div class="python section-example-container">
    <pre class="python"># Organize the historical indicator data into a DataFrame to enable pandas wrangling.
history_df = history.data_frame</pre>
</div>

<div class='dataframe-wrapper'>
<table class="dataframe python">
  <thead>
    <tr style="text-align: right;">
      <th></th>
      <th>current</th>
      <th>rollingsum</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th>2024-12-12 17:00:00</th>
      <td>131.404762</td>
      <td>2759.500</td>
    </tr>
    <tr>
      <th>2024-12-13 17:00:00</th>
      <td>132.101190</td>
      <td>2774.125</td>
    </tr>
    <tr>
      <th>2024-12-16 17:00:00</th>
      <td>135.779762</td>
      <td>2851.375</td>
    </tr>
    <tr>
      <th>2024-12-17 17:00:00</th>
      <td>138.047619</td>
      <td>2899.000</td>
    </tr>
    <tr>
      <th>2024-12-18 17:00:00</th>
      <td>134.095238</td>
      <td>2816.000</td>
    </tr>
  </tbody>
</table>
</div>

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
	<pre class="csharp">// Get the historical values of an indicator over the last 30 days, applying the indicator to the contract's volume.
var history = IndicatorHistory(indicator, symbol, TimeSpan.FromDays(30), selector: Field.Volume);</pre>
	<pre class="python"># Get the historical values of an indicator over the last 30 days, applying the indicator to the contract's volume.
history = self.indicator_history(indicator, symbol, timedelta(30), selector=Field.VOLUME)</pre>
</div>

<p>
    Some indicators require the prices of multiple securities to compute their value (for example, the <a href='/docs/v2/writing-algorithms/securities/asset-classes/future-options/greeks-and-implied-volatility/indicators'>indicators for the Greeks and implied volatility</a>).
    In this case, pass a list of the <code>Symbol</code> objects to the method.
</p>
<div class="section-example-container">
	<pre class="csharp">public class FutureOptionMultiAssetIndicatorHistoryAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        SetStartDate(2024, 12, 19);
        // Add a FOP universe.
        var future = AddFuture(Futures.Indices.SP500EMini);
        future.SetFilter(universe => universe.FrontMonth());
        AddFutureOption(future.Symbol, universe => universe.FrontMonth().Strikes(0, 0).CallsOnly());
    }

    public override void OnSecuritiesChanged(SecurityChanges changes)
    {
        foreach (var security in changes.AddedSecurities)
        {
            if (security.Type != SecurityType.FutureOption)
            {
                continue;
            }
            var option = security.Symbol;
            // Get the Symbol of the mirror contract.
           var mirror = QuantConnect.Symbol.CreateOption(
                option.Underlying.Value, option.ID.Market, option.ID.OptionStyle,
                option.ID.OptionRight == OptionRight.Put ? OptionRight.Call : OptionRight.Put,
                option.ID.StrikePrice, option.ID.Date
            );
            // Create the indicator.
            var indicator = new ImpliedVolatility(option, RiskFreeInterestRateModel, new ConstantDividendYieldModel(0), mirror, OptionPricingModelType.ForwardTree);
            // Get the historical values of the indicator over the last 10 trading minutes.
            var history = IndicatorHistory(indicator, new[] {option.Underlying, option, mirror}, 10, Resolution.Minute);
            // Get the average IV value.
            var avgIV = history.Average(indicatorDataPoint => indicatorDataPoint.Current.Value);
        }
    }
}</pre>
	<pre class="python">class FutureOptionMultiAssetIndicatorHistoryAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 12, 19)
        # Add a FOP universe.
        future = self.add_future(Futures.Indices.SP_500_E_MINI)
        future.set_filter(lambda universe: universe.front_month())
        self.add_future_option(future.symbol, lambda universe: universe.front_month().strikes(0, 0).calls_only())

    def on_securities_changed(self, changes):
        for security in changes.added_securities:
            if security.type != SecurityType.FUTURE_OPTION:
                continue           
            option = security.symbol
            # Get the Symbol of the mirror contract.
            mirror = Symbol.create_option(
                option.underlying.value, option.id.market, option.id.option_style, 
                OptionRight.Call if option.id.option_right == OptionRight.PUT else OptionRight.PUT,
                option.id.strike_price, option.id.date
            )
            # Create the indicator.
            indicator = ImpliedVolatility(
                option, self.risk_free_interest_rate_model, ConstantDividendYieldModel(0),
                mirror, OptionPricingModelType.FORWARD_TREE
            )
            # Get the historical values of the indicator over the last 10 minutes.
            history = self.indicator_history(indicator, [option.underlying, option, mirror], 10, Resolution.MINUTE)
            # Get the average IV value.
            iv_avg = history.data_frame.current.mean()</pre>
</div>

<p class='csharp'>If you already have a list of <a href='/docs/v2/writing-algorithms/key-concepts/time-modeling/timeslices'>Slice</a> objects, you can pass them to the <code class="csharp">IndicatorHistory</code><code class="python">indicator_history</code> method to avoid the internal history request.</p>
<div class="csharp section-example-container">
	<pre class="csharp">var slices = History(new[] {symbol}, 30, Resolution.Daily);
var history = IndicatorHistory(indicator, slices);</pre>
</div>
