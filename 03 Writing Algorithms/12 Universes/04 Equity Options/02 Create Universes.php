<p>To add a universe of Equity Option contracts, in the <code>Initialize</code> method, call the <code>AddOption</code> method. This method returns an <code>Option</code> object, which contains the canonical <code>Symbol</code>. You can't trade with the canonical Option <code>Symbol</code>, but save a reference to it so you can easily access the Option contracts in the <a href='/docs/v2/writing-algorithms/universes/equity-options#04-Navigate-Option-Chains'>OptionChain</a> that LEAN passes to the <code>OnData</code> method.</p>

<div class="section-example-container">
    <pre class="csharp">UniverseSettings.Asynchronous = true;
var option = AddOption("SPY");
_symbol = option.Symbol;</pre>
    <pre class="python">self.universe_settings.asynchronous = True
option = self.add_option("SPY")
self.symbol = option.symbol</pre>
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
            <td>The underlying Equity ticker. To view the supported underlying Equity tickers, see <a href='/docs/v2/writing-algorithms/datasets/algoseek/us-equity-options#06-Supported-Assets'>Supported Assets</a>.</td>
            <td></td>
        </tr>
        <tr>
            <td><code>resolution</code></td>
	        <td><code class="csharp">Resolution?</code><code class="python">Resolution/NoneType</code></td>
            <td>The resolution of the market data. To view the supported resolutions, see <a href='/docs/v2/writing-algorithms/securities/asset-classes/equity-options/requesting-data#03-Resolutions'>Resolutions</a>. The Equity resolution must be less than or equal to the Equity Option resolution. For example, if you set the Equity resolution to minute, then you must set the Equity Option resolution to minute, hour, or daily.</td>
            <td><code class="python">None</code><code class="csharp">null</code></td>
        </tr>
        <tr>
            <td><code>market</code></td>
	        <td><code class="csharp">string</code><code class="python">str</code></td>
            <td>The underlying Equity market.</td>
            <td><code class="python">None</code><code class="csharp">null</code></td>
        </tr>
        <tr>
            <td><code>fillForward</code></td>
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


<p>If you add an Equity Option universe but don't have a subscription to the underlying Equity, LEAN automatically subscribes to the underlying Equity with the following settings:</p>

<table class="qc-table table">
    <thead>
        <tr>
            <th>Setting</th>
            <th>Value</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><a href='/docs/v2/writing-algorithms/securities/asset-classes/us-equity/requesting-data#08-Fill-Forward'>Fill forward</a></td>
            <td>Same as the Option universe</td>
        </tr>
        <tr>
            <td><a href='/docs/v2/writing-algorithms/securities/asset-classes/us-equity/requesting-data#09-Margin-and-Leverage'>Leverage</a></td>
            <td>0</td>
        </tr>
        <tr>
            <td><a href='/docs/v2/writing-algorithms/securities/asset-classes/us-equity/requesting-data#10-Extended-Market-Hours'>Extended Market Hours</a></td>
            <td>Same as the Option universe</td>
        </tr>
        <tr>
            <td><a href='/docs/v2/writing-algorithms/securities/asset-classes/us-equity/requesting-data#11-Data-Normalization'>Data Normalization</a></td>
            <td><code>DataNormalizationMode.Raw</code></td>
        </tr>
    </tbody>
</table>

<p>If you already have a subscription to the underlying Equity but it's not <code>Raw</code> data normalization, LEAN automatically changes it to <code>Raw</code>.</p>

<p>To override the default <a href="/docs/v2/writing-algorithms/reality-modeling/options-models/pricing">pricing model</a> of the Option, <a href='https://www.quantconnect.com/docs/v2/writing-algorithms/reality-modeling/options-models/pricing#04-Set-Models'>set a pricing model</a>.</p>

<div class="section-example-container">
    <pre class="csharp">option.price_model = OptionPriceModels.crank_nicolson_fd();</pre>
    <pre class="python">option.price_model = OptionPriceModels.crank_nicolson_fd()</pre>
</div>

<?php echo file_get_contents(DOCS_RESOURCES."/reality-modeling/volatility-model.html"); ?>
