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
            <td>The risk free rate</td>
            <td><code class="csharp">null</code><code class="python">None</code></td>
        </tr>
        <tr>
            <td><code>dividendYield</code></td>
            <td><code class="csharp">decimal</code><code class="python">float</code></td>
            <td>The dividend yield</td>
            <td><code class="csharp">null</code><code class="python">None</code></td>
        </tr>
        <tr>
            <td><code>optionModel</code></td>
            <td><code>OptionPricingModelType</code></td>
            <td>The option pricing model used to estimate IV</td>
            <td><code>OptionPricingModelType.BlackScholes</code></td>
        </tr>
        <tr>
            <td><code>resolution</code></td>
            <td><code>Resolution</code></td>
            <td>The desired resolution of the data</td>
            <td><code class="csharp">null</code><code class="python">None</code></td>
        </tr>
    </tbody>
</table>