<p>To add a universe of Equity Option contracts, in the <code>Initialize</code> method, call the <code>AddOption</code> method. This method returns an <code>Option</code> object, which contains the canonical <code>Symbol</code>. You can't trade with the canonical Option <code>Symbol</code>, but save a reference to it so you can easily access the Option contracts in the <a href='/docs/v2/writing-algorithms/securities/asset-classes/equity-options/handling-data#02-Option-Chains'>OptionChain</a> that LEAN passes to the <code>OnData</code> method.</p>

<div class="section-example-container">
    <pre class="csharp">var option = AddOption("SPY");
_symbol = option.Symbol;</pre>
    <pre class="python">option = self.AddOption("SPY")
self.symbol = option.Symbol</pre>
</div>

<p>The following table describes the <code>AddOption</code> method arguments:</p>
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
            <td><code>ticker</code></td>
	        <td><code class="csharp">string</code><code class="python">str</code></td>
            <td>The underlying Equity ticker.</td>
            <td></td>
        </tr>
        <tr>
            <td><code>resolution</code></td>
	        <td><code class="csharp">Resolution?</code><code class="python">Resolution/NoneType</code></td>
            <td>The resolution of the market data.</td>
            <td><code class="python">None</code><code class="csharp">null</code></td>
        </tr>
        <tr>
            <td><code>market</code></td>
	        <td><code class="csharp">string</code><code class="python">str</code></td>
            <td>The underlying Equity market.</td>
            <td><code class="python">None</code><code class="csharp">null</code></td>
        </tr>
        <tr>
            <td><code>fillDataForward</code></td>
	        <td><code>bool</code></td>
            <td>If true, the current slice contains the last available data even if there is no data at the current time.</td>
            <td><code class="python">True</code><code class="csharp">true</code></td>
        </tr>
        <tr>
            <td><code>leverage</code></td>
	        <td><code class="csharp">decimal</code><code class="python">float</code></td>
            <td>The leverage for this Equity.</td>
            <td><code>Security.NullLeverage</code></td>
        </tr>
        <tr>
            <td><code>extendedMarketHours</code></td>
	        <td><code>bool</code></td>
            <td>A flag that signals if LEAN should send data during pre- and post-market trading hours.</td>
            <td><code class="python">False</code><code class="csharp">false</code></td>
        </tr>
    </tbody>
</table>


<p>If you add an Option universe for an underlying Equity that you don't have a subscription for, LEAN automatically subscribes to the underlying Equity with a <a href='/docs/v2/writing-algorithms/securities/asset-classes/us-equity/requesting-data#09-Data-Normalization'>data normalization mode</a> of <code>Raw</code>. If you already have a subscription to the underlying Equity but it's not <code>Raw</code>, it automatically changes to <code>Raw</code>.</p>

<p>To set the <a href='/docs/v2/writing-algorithms/reality-modeling/options-models/pricing'>price model</a> of the Option, set its <code>PriceModel</code> property.</p>

<div class="section-example-container">
    <pre class="csharp">option.PriceModel =  OptionPriceModels.CrankNicolsonFD();</pre>
    <pre class="python">option.PriceModel = OptionPriceModels.CrankNicolsonFD()</pre>
</div>
