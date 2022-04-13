
<p>The data point indicators use only a single price data in their calculations. By default, those indicators use the closing price. For Equity, that price is the trade bar closing price. For other asset classes with quote bar data (bid/ask price), those indicators are calculated with the mid-price of the bid closing price and the ask closing price. </p><p>If you want to create an indicator with the other fields like Open, High, Low, or Close, you can specify the selector argument in the indicator helper method with the available fields. </p>
<div class="section-example-container">
<pre class="csharp">// define a 10-period RSI with indicator constructor
_rsi = new RelativeStrengthIndex(10, MovingAverageType.Simple);
// register the daily High price data to automatically update the indicator
RegisterIndicator(symbol, _rsi, Resolution.Daily, Field.High);</pre>
<pre class="python"># define a 10-period RSI with indicator constructor
self.rsi = RelativeStrengthIndex(10, MovingAverageType.Simple)
# register the daily High price data to automatically update the indicator
self.RegisterIndicator(self.symbol, self.rsi, Resolution.Daily, Field.High)</pre>
</div>