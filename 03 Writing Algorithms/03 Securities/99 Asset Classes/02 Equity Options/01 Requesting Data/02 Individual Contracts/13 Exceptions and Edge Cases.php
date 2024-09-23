<p>The following sections explain exceptions and edge cases with subscribing to individual Option contracts.</p>

<h4>Default Underlying Subscription Settings</h4>

<p>If you subscribe to an Equity Option contract but don't have a subscription to the underlying Equity, LEAN automatically subscribes to the underlying Equity with the following settings:</p>

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
            <td>Same as the Option contract</td>
        </tr>
        <tr>
            <td><a href='/docs/v2/writing-algorithms/securities/asset-classes/us-equity/requesting-data#09-Margin-and-Leverage'>Leverage</a></td>
            <td>0</td>
        </tr>
        <tr>
            <td><a href='/docs/v2/writing-algorithms/securities/asset-classes/us-equity/requesting-data#10-Extended-Market-Hours'>Extended Market Hours</a></td>
            <td>Same as the Option contract</td>
        </tr>
        <tr>
            <td><a href='/docs/v2/writing-algorithms/securities/asset-classes/us-equity/requesting-data#11-Data-Normalization'>Data Normalization</a></td>
            <td><code class="csharp">DataNormalizationMode.Raw</code><code class="python">DataNormalizationMode.RAW</code></td>
        </tr>
    </tbody>
</table>

<p>In this case, you still need the Equity <code>Symbol</code> to subscribe to Equity Option contracts. If you don't have access to it, create it.</p>
<div class="section-example-container">
    <pre class="csharp">_underlying = QuantConnect.Symbol.Create("SPY", SecurityType.Equity, Market.USA);</pre>
    <pre class="python">self._underlying = Symbol.create("SPY", SecurityType.EQUITY, Market.USA)</pre>
</div>

<h4>Manually Creating Option Symbol Objects</h4>

<p>
    To subscribe to an Option contract, you need the contract <code>Symbol</code>. 
    You can get the contract <code>Symbol</code> from the <code class="csharp">CreateOption</code><code class="python">create_option</code> method or from the <code class="csharp">OptionChainProvider</code><code class="python">option_chain_provider</code>. 
    If you use the <code class="csharp">CreateOption</code><code class="python">create_option</code> method, you need to provide the details of an existing contract.
</p>

<div class="section-example-container">
    <pre class="csharp">_contractSymbol = QuantConnect.Symbol.CreateOption(_underlying, Market.USA,
    OptionStyle.American, OptionRight.Call, 365, new DateTime(2022, 6, 17));</pre>
    <pre class="python">self._contract_symbol = Symbol.create_option(self._underlying, Market.USA,
    OptionStyle.AMERICAN, OptionRight.CALL, 365, datetime(2022, 6, 17))</pre>
</div>

<h4>Overriding the Initial Implied Volatility Guess</h4>

<? include(DOCS_RESOURCES."/reality-modeling/volatility-model.html"); ?>
