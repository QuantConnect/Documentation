<p>Before you can subscribe to a Future Option contract, you may configure the underlying volatility model and you must get the contract <code>Symbol</code>.</p>

<h4>Configure the Underlying Volatility Model</h4>

<?php echo file_get_contents(DOCS_RESOURCES."/reality-modeling/volatility-model.html"); ?>

<h4>Get Contract Symbols</h4>

<p>To subscribe to a Future Option contract, you need the contract <code>Symbol</code>. You can get the contract <code>Symbol</code> from the <code>CreateOption</code> method or from the <code>OptionChainProvider</code>. If you use the <code>CreateOption</code> method, you need to provide the contract details.</p>

<div class="section-example-container">
    <pre class="csharp">_futureContractSymbol = QuantConnect.Symbol.CreateFuture(Futures.Indices.SP500EMini,
    Market.CME, new DateTime(2022, 6, 17));
_optionContractSymbol = QuantConnect.Symbol.CreateOption(_futureContractSymbol,
    Market.CME, OptionStyle.American, OptionRight.Call, 3600, new DateTime(2022, 6, 17))</pre>
    <pre class="python">self.future_contract_symbol = Symbol.CreateFuture(Futures.Indices.SP500EMini,
    Market.CME, datetime(2022, 6, 17))
self.option_contract_symbol = Symbol.CreateOption(self.future_contract_symbol,
    Market.CME, OptionStyle.American, OptionRight.Call, 3600, datetime(2022, 6, 17))</pre>
</div>

<p>Another way to get a Future Option contract <code>Symbol</code> is to use the <code>OptionChainProvider</code>. The <code>GetOptionContractList</code> method of <code>OptionChainProvider</code> returns a list of <code>Symbol</code> objects that reference the available Option contracts for a given underlying Future contract on a given date. The <code>Symbol</code> you pass to the method can reference any of the following Futures contracts:</p>

<ul>
    <li>The <a href="/docs/v2/writing-algorithms/universes/futures#12-Continous-Contracts">continuous Futures contract</a></li>
    <li>A contract in the <a href="/docs/v2/writing-algorithms/universes/futures">Futures universe</a></li>
    <li>A contract that you added with <code>AddFutureContract</code></li>
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
    <pre class="python">option_contract_symbols = self.OptionChainProvider.GetOptionContractList(self.future_contract_symbol, self.Time)
expiry = min([symbol.ID.Date for symbol in option_contract_symbols])
filtered_symbols = [symbol for symbol in option_contract_symbols if symbol.ID.Date == expiry and symbol.ID.OptionRight == OptionRight.Call]
self.option_contract_symbol = sorted(filtered_symbols, key=lambda symbol: symbol.ID.StrikePrice)[0]</pre>
</div>

<h4>Subscribe to Contracts</h4>

<p>To create a Future Option contract subscription, pass the contract <code>Symbol</code> to the <code>AddFutureOptionContract</code> method. Save a reference to the contract <code>Symbol</code> so you can easily access the Option contract in the <a href="/docs/v2/writing-algorithms/securities/asset-classes/future-options/handling-data#06-Option-Chains">OptionChain</a> that LEAN passes to the <code>OnData</code> method. To override the default <a href="/docs/v2/writing-algorithms/reality-modeling/options-models/pricing">pricing model</a> of the Option, <a href='https://www.quantconnect.com/docs/v2/writing-algorithms/reality-modeling/options-models/pricing#04-Set-Models'>set a pricing model</a>.</p>

<div class="section-example-container">
    <pre class="csharp">var option = AddFutureOptionContract(_optionContractSymbol);
option.PriceModel = OptionPriceModels.BjerksundStensland();</pre>
    <pre class="python">option = self.AddFutureOptionContract(self.option_contract_symbol)
option.PriceModel = OptionPriceModels.BjerksundStensland()</pre>
</div>

<p>The <code>AddFutureOptionContract</code> method creates a subscription for a single Option contract and adds it to your <span class="new-term">user-defined</span> universe. To create a dynamic universe of Future Option contracts, add a <a href="/docs/v2/writing-algorithms/universes/future-options">Future Options universe</a>.</p>

<h4>Warm Up Contract Prices</h4>

<p>If you subscribe to a Future Option contract with <code>AddFutureOptionContract</code>, you'll need to wait until the next <code>Slice</code> to receive data and trade the contract. To trade the contract in the same time step you subscribe to the contract, set the current price of the contract in a <a href='/docs/v2/writing-algorithms/initialization#07-Set-Security-Initializer'>security initializer</a>.</p>
<div class="section-example-container">
    <pre class="csharp">var seeder = new FuncSecuritySeeder(GetLastKnownPrices);
SetSecurityInitializer(new BrokerageModelSecurityInitializer(BrokerageModel, seeder, this));</pre>
    <pre class="python">seeder = FuncSecuritySeeder(self.GetLastKnownPrices)
self.SetSecurityInitializer(BrokerageModelSecurityInitializer(self.BrokerageModel, seeder, self))</pre>
</div>

<h4>Supported Assets</h4>

<p>To view the supported assets in the US Future Options dataset, see <a href="/docs/v2/writing-algorithms/datasets/algoseek/us-future-options#05-Supported-Assets">Supported Assets</a>.</p>
