<p>
 Implied volatility,
 <script type="math/tex">
  \sigma
 </script>
 , is the market's expectation for the future volatility of an asset and is implied by the price of the assets's Options contracts. 
    You can't observe it in the market but you can derive it from the price of an Option.
    For more information about implied volatility, see
 <a href="/learning/articles/introduction-to-options/historical-volatility-and-implied-volatility#implied-volatility">
  Implied Volatility
 </a>
 .
</p>
<h4>
 Automatic Indicators
</h4>
<?
// Tag: div section-example-container testable
$name = "implied volatility";
$typeName = "ImpliedVolatility";
$helperMethod = "IV";

include(DOCS_RESOURCES."/option-indicators/equity.php");
include(DOCS_RESOURCES."/option-indicators/automatic-indicator.php"); 
?>
<div class="regression-test-results">
 <script class="csharp-result" type="text">
  {
    "Total Orders": "2",
    "Average Win": "0%",
    "Average Loss": "0%",
    "Compounding Annual Return": "-0.081%",
    "Drawdown": "0.700%",
    "Expectancy": "0",
    "Start Equity": "100000",
    "End Equity": "99993",
    "Net Profit": "-0.007%",
    "Sharpe Ratio": "-1.851",
    "Sortino Ratio": "-2.112",
    "Probabilistic Sharpe Ratio": "36.445%",
    "Loss Rate": "0%",
    "Win Rate": "0%",
    "Profit-Loss Ratio": "0",
    "Alpha": "-0.013",
    "Beta": "-0.195",
    "Annual Standard Deviation": "0.03",
    "Annual Variance": "0.001",
    "Information Ratio": "-2.259",
    "Tracking Error": "0.119",
    "Treynor Ratio": "0.283",
    "Total Fees": "$2.00",
    "Estimated Strategy Capacity": "$320000.00",
    "Lowest Capacity Asset": "SPY YFNTPLP22YG6|SPY R735QTJ8XC9X",
    "Portfolio Turnover": "0.04%",
    "OrderListHash": "799e082d8952e8f171e7fac24d687be0"
}
 </script>
 <script class="python-result" type="text">
  {
    "Total Orders": "2",
    "Average Win": "0%",
    "Average Loss": "0%",
    "Compounding Annual Return": "-1.147%",
    "Drawdown": "0.700%",
    "Expectancy": "0",
    "Start Equity": "100000",
    "End Equity": "99900",
    "Net Profit": "-0.100%",
    "Sharpe Ratio": "-2.038",
    "Sortino Ratio": "-2.293",
    "Probabilistic Sharpe Ratio": "33.483%",
    "Loss Rate": "0%",
    "Win Rate": "0%",
    "Profit-Loss Ratio": "0",
    "Alpha": "-0.016",
    "Beta": "-0.217",
    "Annual Standard Deviation": "0.031",
    "Annual Variance": "0.001",
    "Information Ratio": "-2.284",
    "Tracking Error": "0.121",
    "Treynor Ratio": "0.289",
    "Total Fees": "$2.00",
    "Estimated Strategy Capacity": "$300000.00",
    "Lowest Capacity Asset": "SPY YFNTPLOW4MEE|SPY R735QTJ8XC9X",
    "Portfolio Turnover": "0.04%",
    "OrderListHash": "5202fc357a88cd3228dd0fc9161b9052"
}
 </script>
</div>
<p>
 For more information about the
 <code class="csharp">
  IV
 </code>
 <code class="python">
  iv
 </code>
 method, see
 <a href="/docs/v2/writing-algorithms/securities/asset-classes/equity-options/greeks-and-implied-volatility/indicators#02-Parameters">
  Parameters
 </a>
 and
 <a href="/docs/v2/writing-algorithms/indicators/supported-indicators/implied-volatility#02-Using-IV-Indicator">
  Using IV Indicator
 </a>
 .
</p>
<h4>
 Manual Indicators
</h4>
<?
// Tag: div section-example-container testable
$name = "implied volatility";
$typeName = "ImpliedVolatility";
$indicatorPage = "implied-volatility";
$helperMethod = "IV";

include(DOCS_RESOURCES."/option-indicators/equity.php");
include(DOCS_RESOURCES."/option-indicators/manual-indicator.php");
?>
<div class="regression-test-results">
 <script class="csharp-result" type="text">
  {
    "Total Orders": "2",
    "Average Win": "0%",
    "Average Loss": "0%",
    "Compounding Annual Return": "-0.081%",
    "Drawdown": "0.700%",
    "Expectancy": "0",
    "Start Equity": "100000",
    "End Equity": "99993",
    "Net Profit": "-0.007%",
    "Sharpe Ratio": "-1.851",
    "Sortino Ratio": "-2.112",
    "Probabilistic Sharpe Ratio": "36.445%",
    "Loss Rate": "0%",
    "Win Rate": "0%",
    "Profit-Loss Ratio": "0",
    "Alpha": "-0.013",
    "Beta": "-0.195",
    "Annual Standard Deviation": "0.03",
    "Annual Variance": "0.001",
    "Information Ratio": "-2.259",
    "Tracking Error": "0.119",
    "Treynor Ratio": "0.283",
    "Total Fees": "$2.00",
    "Estimated Strategy Capacity": "$320000.00",
    "Lowest Capacity Asset": "SPY YFNTPLP22YG6|SPY R735QTJ8XC9X",
    "Portfolio Turnover": "0.04%",
    "OrderListHash": "799e082d8952e8f171e7fac24d687be0"
}
 </script>
 <script class="python-result" type="text">
  {
    "Total Orders": "2",
    "Average Win": "0%",
    "Average Loss": "0%",
    "Compounding Annual Return": "-1.147%",
    "Drawdown": "0.700%",
    "Expectancy": "0",
    "Start Equity": "100000",
    "End Equity": "99900",
    "Net Profit": "-0.100%",
    "Sharpe Ratio": "-2.038",
    "Sortino Ratio": "-2.293",
    "Probabilistic Sharpe Ratio": "33.483%",
    "Loss Rate": "0%",
    "Win Rate": "0%",
    "Profit-Loss Ratio": "0",
    "Alpha": "-0.016",
    "Beta": "-0.217",
    "Annual Standard Deviation": "0.031",
    "Annual Variance": "0.001",
    "Information Ratio": "-2.284",
    "Tracking Error": "0.121",
    "Treynor Ratio": "0.289",
    "Total Fees": "$2.00",
    "Estimated Strategy Capacity": "$300000.00",
    "Lowest Capacity Asset": "SPY YFNTPLOW4MEE|SPY R735QTJ8XC9X",
    "Portfolio Turnover": "0.04%",
    "OrderListHash": "5202fc357a88cd3228dd0fc9161b9052"
}
 </script>
</div>
<h4>
 Volatility Smoothing
</h4>
<p>
 The default
 <a href="/docs/v2/writing-algorithms/securities/asset-classes/equity-options/greeks-and-implied-volatility/indicators#02-Parameters">
  IV smoothing
 </a>
 method uses the one contract in the pair that's at-the-money or out-of-money to calculate the IV.
    To change the smoothing function, pass a
 <code class="csharp">
  mirrorOption
 </code>
 <code class="python">
  mirror_option
 </code>
 argument to the
 <code class="csharp">
  IV
 </code>
 <code class="python">
  iv
 </code>
 method or
 <code>
  ImpliedVolatility
 </code>
 constructor and then call the
 <code class="csharp">
  SetSmoothingFunction
 </code>
 <code class="python">
  set_smoothing_function
 </code>
 method of the resulting
 <code>
  ImpliedVolatility
 </code>
 object.
    The follow table describes the arguments of the custom function:
</p>
<table class="qc-table table">
 <thead>
  <tr>
   <th>
    Argument
   </th>
   <th>
    Data Type
   </th>
   <th>
    Description
   </th>
  </tr>
 </thead>
 <tbody>
  <tr>
   <td>
    <code>
     iv
    </code>
   </td>
   <td>
    <code class="csharp">
     decimal
    </code>
    <code class="python">
     float
    </code>
   </td>
   <td>
    The IV of the Option contract.
   </td>
  </tr>
  <tr>
   <td>
    <code class="csharp">
     mirrorIv
    </code>
    <code class="python">
     mirror_iv
    </code>
   </td>
   <td>
    <code class="csharp">
     decimal
    </code>
    <code class="python">
     float
    </code>
   </td>
   <td>
    The IV of the mirror Option contract.
   </td>
  </tr>
 </tbody>
</table>
<p>
 The method must return a
 <code class="csharp">
  decimal
 </code>
 <code class="python">
  float
 </code>
 as the smoothened IV.
</p>
<div class="section-example-container">
 <pre class="csharp">private ImpliedVolatility _iv;

public override void Initialize()
{
    var option = QuantConnect.Symbol.CreateOption("AAPL", Market.USA, OptionStyle.American, OptionRight.Put, 505m, new DateTime(2014, 6, 27));
    AddOptionContract(option);

    var mirrorOption = QuantConnect.Symbol.CreateOption("AAPL", Market.USA, OptionStyle.American, OptionRight.Call, 505m, new DateTime(2014, 6, 27));
    AddOptionContract(mirrorOption);

    _iv = IV(option, mirrorOption);
    // example: take average of the call-put pair
    _iv.SetSmoothingFunction((iv, mirrorIv) =&gt; (iv + mirrorIv) * 0.5m);
}</pre>
 <pre class="python">def initialize(self):
    option = Symbol.create_option("AAPL", Market.USA, OptionStyle.AMERICAN, OptionRight.PUT, 505, datetime(2014, 6, 27))
    self.add_option_contract(option)

    mirror_option = Symbol.create_option("AAPL", Market.USA, OptionStyle.AMERICAN, OptionRight.CALL, 505, datetime(2014, 6, 27))
    self.add_option_contract(mirror_option)

    self._iv = self.iv(option, mirror_option)
    # Example: The average of the call-put pair.
    self._iv.set_smoothing_function(lambda iv, mirror_iv: (iv + mirror_iv) * 0.5)
</pre>
</div>
