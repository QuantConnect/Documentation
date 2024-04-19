<p>Before you can subscribe to a Future Option contract, you may configure the underlying volatility model and you must get the contract <code>Symbol</code>.</p>

<h4>Configure the Underlying Futures Contract</h4>

<p>In most cases, you should <a href='/docs/v2/writing-algorithms/securities/asset-classes/futures/requesting-data#02-Create-Subscriptions'>subscribe to the underlying Futures contract</a> before you subscribe to a Futures Option contract. If you don't, LEAN automatically subscribes to the underlying Futures contract with the following settings:</p>

<table class="qc-table table">
    <thead>
        <tr>
            <th>Setting</th>
            <th>Value</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><a href='/docs/v2/writing-algorithms/securities/asset-classes/futures/requesting-data#05-Fill-Forward'>Fill forward</a></td>
            <td>Same as the Option contract</td>
        </tr>
        <tr>
            <td><a href='/docs/v2/writing-algorithms/securities/asset-classes/futures/requesting-data#06-Margin-and-Leverage'>Leverage</a></td>
            <td>0</td>
        </tr>
        <tr>
            <td><a href='/docs/v2/writing-algorithms/securities/asset-classes/futures/requesting-data#07-Extended-Market-Hours'>Extended Market Hours</a></td>
            <td>Same as the Option contract</td>
        </tr>
    </tbody>
</table>

<p>In this case, you still need the Futures contract <code>Symbol</code> to subscribe to Futures Option contracts. If you don't have access to it, create it.</p>

<div class="section-example-container">
    <pre class="csharp">_futureContractSymbol = QuantConnect.Symbol.CreateFuture(Futures.Indices.SP500EMini,
    Market.CME, new DateTime(2022, 6, 17));</pre>
    <pre class="python">self.future_contract_symbol = Symbol.create_future(Futures.Indices.SP500E_MINI,
    Market.CME, datetime(2022, 6, 17))</pre>
</div>

<p>For more information about getting the <code>Symbol</code> of Futures contracts, see <a href='/docs/v2/writing-algorithms/securities/asset-classes/futures/requesting-data#02-Create-Subscriptions'>Create Subscriptions</a>.</p>


<?php echo file_get_contents(DOCS_RESOURCES."/reality-modeling/volatility-model.html"); ?>

<h4>Get Contract Symbols</h4>

<p>To subscribe to a Future Option contract, you need the contract <code>Symbol</code>. You can get the contract <code>Symbol</code> from the <code>CreateOption</code> method or from the <code>OptionChainProvider</code>. If you use the <code>CreateOption</code> method, you need to provide the contract details.</p>

<div class="section-example-container">
    <pre class="csharp">_optionContractSymbol = QuantConnect.Symbol.CreateOption(_futureContractSymbol,
    Market.CME, OptionStyle.American, OptionRight.Call, 3600, new DateTime(2022, 6, 17))</pre>
    <pre class="python">self.option_contract_symbol = Symbol.create_option(self.future_contract_symbol,
    Market.CME, OptionStyle.AMERICAN, OptionRight.CALL, 3600, datetime(2022, 6, 17))</pre>
</div>

<p>Another way to get a Future Option contract <code>Symbol</code> is to use the <code>OptionChainProvider</code>. The <code>GetOptionContractList</code> method of <code>OptionChainProvider</code> returns a list of <code>Symbol</code> objects that reference the available Option contracts for a given underlying Future contract on a given date. The <code>Symbol</code> you pass to the method can reference any of the following Futures contracts:</p>

<ul>
    <li>The <a href="/docs/v2/writing-algorithms/universes/futures#12-Continous-Contracts">continuous Futures contract</a></li>
    <li>A contract in the <a href="/docs/v2/writing-algorithms/universes/futures">Futures universe</a></li>
    <li>A contract that you added with <code class="csharp">AddFutureContract</code><code class="python">add_future_contract</code></li>
</ul>

<p>To filter and select contracts that the <code>GetOptionContractList</code> method returns, you can use the following properties of each <code>Symbol</code> object:</p>
    <table class="qc-table table">
        <thead>
            <tr>
                <th>Property</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                 <td><code>ID.Date</code></td>
                 <td>The expiration date of the contract.</td>
            </tr>
            <tr>
                 <td><code>ID.StrikePrice</code></td>
                 <td>The strike price of the contract.</td>
            </tr>
            <tr>
                 <td><code>ID.OptionRight</code></td>
                 <td>
                     The contract type. The <code>OptionRight</code> enumeration has the following members:
                     <div data-tree="QuantConnect.OptionRight"></div>
                  </td>
            </tr>
            <tr>
                 <td><code>ID.OptionStyle</code></td>
                 <td>
                     The contract style. The <code>OptionStyle</code> enumeration has the following members:
                     <div data-tree="QuantConnect.OptionStyle"></div>
                     We currently only support American-style Options for Future Options.
                  </td>
            </tr>
        </tbody>
    </table>
    
<div class="section-example-container">
    <pre class="csharp">var optionContractSymbols = OptionChainProvider.GetOptionContractList(_futureContractSymbol, Time);
var expiry = optionContractSymbols.Select(symbol =&gt; symbol.ID.Date).Min();
var filteredSymbols = optionContractSymbols.Where(symbol =&gt; symbol.ID.Date == expiry &amp;&amp; symbol.ID.OptionRight == OptionRight.Call);
_optionContractSymbol = filteredSymbols.OrderByDescending(symbol =&gt; symbol.ID.StrikePrice).Last();</pre>
    <pre class="python">option_contract_symbols = self.option_chain_provider.get_option_contract_list(self.future_contract_symbol, self.time)
expiry = min([symbol.id.date for symbol in option_contract_symbols])
filtered_symbols = [symbol for symbol in option_contract_symbols if symbol.id.date == expiry and symbol.id.option_right == OptionRight.CALL]
self.option_contract_symbol = sorted(filtered_symbols, key=lambda symbol: symbol.id.strike_price)[0]</pre>
</div>

<h4>Subscribe to Contracts</h4>

<p>To create a Future Option contract subscription, pass the contract <code>Symbol</code> to the <code class="csharp">AddFutureOptionContract</code><code class="python">add_future_option_contract</code>  method. Save a reference to the contract <code>Symbol</code> so you can easily access the Option contract in the <a href="/docs/v2/writing-algorithms/securities/asset-classes/future-options/handling-data#06-Option-Chains">OptionChain</a> that LEAN passes to the <code class="csharp">OnData</code><code class="python">on_data</code> method. To override the default <a href="/docs/v2/writing-algorithms/reality-modeling/options-models/pricing">pricing model</a> of the Option, <a href='https://www.quantconnect.com/docs/v2/writing-algorithms/reality-modeling/options-models/pricing#04-Set-Models'>set a pricing model</a>.</p>

<div class="section-example-container">
    <pre class="csharp">var option = AddFutureOptionContract(_optionContractSymbol);
option.PriceModel = OptionPriceModels.BinomialCoxRossRubinstein();</pre>
    <pre class="python">option = self.add_future_option_contract(self.option_contract_symbol)
option.price_model = OptionPriceModels.binomial_cox_ross_rubinstein()</pre>
</div>

<p>The <code class="csharp">AddFutureOptionContract</code><code class="python">add_future_option_contract</code>  method creates a subscription for a single Option contract and adds it to your <span class="new-term">user-defined</span> universe. To create a dynamic universe of Future Option contracts, add a <a href="/docs/v2/writing-algorithms/universes/future-options">Future Options universe</a>.</p>

<h4>Warm Up Contract Prices</h4>

<p>If you subscribe to a Future Option contract with <code>AddFutureOptionContract</code>, you'll need to wait until the next <code>Slice</code> to receive data and trade the contract. To trade the contract in the same time step you subscribe to the contract, set the current price of the contract in a <a href='/docs/v2/writing-algorithms/initialization#07-Set-Security-Initializer'>security initializer</a>.</p>
<div class="section-example-container">
    <pre class="csharp">var seeder = new FuncSecuritySeeder(GetLastKnownPrices);
SetSecurityInitializer(new BrokerageModelSecurityInitializer(BrokerageModel, seeder));</pre>
    <pre class="python">seeder = FuncSecuritySeeder(self.get_last_known_prices)
self.set_security_initializer(BrokerageModelSecurityInitializer(self.brokerage_model, seeder))</pre>
</div>

<h4>Supported Assets</h4>

<p>To view the supported assets in the US Future Options dataset, see <a href="/docs/v2/writing-algorithms/datasets/algoseek/us-future-options#07-Supported-Assets">Supported Assets</a>.</p>
