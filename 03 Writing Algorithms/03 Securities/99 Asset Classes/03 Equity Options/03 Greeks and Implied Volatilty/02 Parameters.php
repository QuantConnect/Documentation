<p>The following table describes the arguments the option indicators methods accept:</p>

<table class="qc-table table">
    <thead>
        <tr>
            <th>Argument</th>
            <th>Data Type</th>
            <th>Description</th>
            <th>Default Value</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>symbol</code></td>
            <td><code>Symbol</code></td>
            <td>The option symbol whose values we want as an indicator</td>
            <td></td>
        </tr>
        <tr>
            <td><code>mirrorOption</code></td>
            <td><code>Symbol</code></td>
            <td>The mirror option contract used for parity type calculation</td>
            <td><code class="csharp">null</code><code class="python">None</code></td>
        </tr>
        <tr>
            <td><code>riskFreeRate</code></td>
            <td><code class="csharp">decimal</code><code class="python">float</code></td>
            <td>The risk free rate, will use the US interest rate if not specified</td>
            <td><code class="csharp">null</code><code class="python">None</code></td>
        </tr>
        <tr>
            <td><code>dividendYield</code></td>
            <td><code class="csharp">decimal</code><code class="python">float</code></td>
            <td>The dividend yield, will calculate from past dividend payoffs if not specified</td>
            <td><code class="csharp">null</code><code class="python">None</code></td>
        </tr>
        <tr>
            <td><code>optionModel</code></td>
            <td><code>OptionPricingModelType</code></td>
            <td>The option pricing model used to calculate the Greek. Will use <code>OptionPricingModelType.BlackScholes</code> for European options and <code>OptionPricingModelType.BinomialCoxRossRubinstein</code> for American options if not specified.</td>
            <td><code class="csharp">null</code><code class="python">None</code></td>
        </tr>
        <tr>
            <td><code>resolution</code></td>
            <td><code>Resolution</code></td>
            <td>The desired resolution of the data</td>
            <td><code class="csharp">null</code><code class="python">None</code></td>
        </tr>
    </tbody>
</table>

<p>We would pass the opposite option contract of the put-call pair of the <code>symbol</code> to the <code>mirrorOption</code> argument if IV calculation with IV smoothing using the put-call pair is desired. The default IV smoothing method is using only out-of-money (OTM) contracts in the pair to obtain the IV. It can be changed using <code>SetSmoothingFunction</code> method in the <code>ImpliedVolatility</code> class/property.</p>

<p>Various option pricing models are accepted to calculate the IV and Greeks. The following table describes the <code>OptionPricingModelType</code> enumeration members:</p>
<div data-tree='QuantConnect.Indicators.OptionPricingModelType'></div>