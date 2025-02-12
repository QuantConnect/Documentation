<p>The following sections explain exceptions and edge cases with subscribing to individual Option contracts.</p>

<h4>Manually Creating Option Symbol Objects</h4>

<p>
	To subscribe to an Option contract, you need the contract <code>Symbol</code>. 
	To get Index Option contract <code>Symbol</code> objects, call the <code class="csharp">CreateOption</code><code class="python">create_option</code> or <code class="csharp">OptionChain</code><code class="python">option_chain</code> methods.
	If you use the <code class="csharp">CreateOption</code><code class="python">create_option</code> method, you need to know the specific contract details.</p>

<div class="section-example-container">
    <pre class="csharp">// Standard contracts
_contractSymbol = QuantConnect.Symbol.CreateOption(_underlying, Market.USA,
    OptionStyle.European, OptionRight.Call, 3650, new DateTime(2022, 6, 17));

// Weekly contracts
_weeklyContractSymbol = QuantConnect.Symbol.CreateOption(_underlying, "SPXW", Market.USA,
    OptionStyle.European, OptionRight.Call, 3650, new DateTime(2022, 6, 17));</pre>
    <pre class="python"># Standard contracts
self._contract_symbol = Symbol.create_option(self._underlying, Market.USA,
    OptionStyle.EUROPEAN, OptionRight.CALL, 3650, datetime(2022, 6, 17))

# Weekly contracts
self._weekly_contract_symbol = Symbol.create_option(self._underlying, "SPXW", Market.USA,
    OptionStyle.EUROPEAN, OptionRight.CALL, 3650, datetime(2022, 6, 17))</pre>
</div>

<h4>Default Underlying Subscription Settings</h4>

<p>If you subscribe to an Index Option contract but don't have a subscription to the underlying Index, LEAN automatically subscribes to the underlying Index and sets its <a href='/docs/v2/writing-algorithms/securities/asset-classes/index/requesting-data#05-Fill-Forward'>fill forward</a> property to match that of the Index Option contract. In this case, you still need the Index <code class="csharp">Symbol</code><code class="python">symbol</code> to subscribe to Index Option contracts. If you don't have access to it, create it.</p>
<div class="section-example-container">
    <pre class="csharp">_underlying = QuantConnect.Symbol.Create("SPX", SecurityType.Index, Market.USA);</pre>
    <pre class="python">self._underlying = Symbol.create("SPX", SecurityType.INDEX, Market.USA)</pre>
</div>

<h4>Overriding the Initial Implied Volatility Guess</h4>

<? include(DOCS_RESOURCES."/reality-modeling/volatility-model.html"); ?>
