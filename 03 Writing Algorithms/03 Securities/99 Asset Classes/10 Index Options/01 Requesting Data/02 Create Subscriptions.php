<p>Before you can subscribe to an Index Option contract, you must configure the underlying Index and get the contract <code>Symbol</code>.</p>

<h4>Configure the Underlying Index</h4>
<p>In the <code>Initialize</code> method, <a href='/docs/v2/writing-algorithms/securities/asset-classes/index/requesting-data#02-Create-Subscriptions'>subscribe to the underlying Index</a>.</p>

<div class="section-example-container">
    <pre class="csharp">_symbol = AddIndex("SPX").Symbol;</pre>
    <pre class="python">self.symbol = self.AddIndex("SPX").Symbol</pre>
</div>

<p>If you subscribe to an Index Option contract but don't have a subscription to the underlying Index, LEAN automatically subscribes to the underlying Index and sets its <a href='/docs/v2/writing-algorithms/securities/asset-classes/index/requesting-data#05-Fill-Forward'></a> property to match that of the Index Option contract.</p>

<?php echo file_get_contents(DOCS_RESOURCES."/reality-modeling/volatility-model.html"); ?>

<h4>Get Contract Symbols</h4>
<p>To get Index Option contract <code>Symbol</code> objects, call the <code>CreateOption</code> method or use the <code>OptionChainProvider</code>. If you use the <code>CreateOption</code> method, you need to know the specific contract details.</p>

<div class="section-example-container">
    <pre class="csharp">_symbol = QuantConnect.Symbol.Create("SPX", SecurityType.Index, Market.USA);

// Standard contracts
_contractSymbol = QuantConnect.Symbol.CreateOption(_symbol, Market.USA,
    OptionStyle.European, OptionRight.Call, 3650, new DateTime(2022, 6, 17));

// Weekly contracts
_weeklyContractSymbol = QuantConnect.Symbol.CreateOption(_symbol, "SPXW", Market.USA,
    OptionStyle.European, OptionRight.Call, 3650, new DateTime(2022, 6, 17));</pre>
    <pre class="python">self.symbol = Symbol.Create("SPX", SecurityType.Index, Market.USA)

# Standard contracts
self.contract_symbol = Symbol.CreateOption(self.symbol, Market.USA,
    OptionStyle.European, OptionRight.Call, 3650, datetime(2022, 6, 17))

# Weekly contracts
self.weekly_contract_symbol = Symbol.CreateOption(self.symbol, "SPXW", Market.USA,
    OptionStyle.European, OptionRight.Call, 3650, datetime(2022, 6, 17))</pre>
</div>

<p>Another way to get an Index Option contract <code>Symbol</code> is to use the <code>OptionChainProvider</code>. The <code>GetOptionContractList</code> method of <code>OptionChainProvider </code>returns a list of <code>Symbol</code> objects that reference the available Option contracts for a given underlying Index on a given date. To filter and select contracts, you can use the following properties of each <code>Symbol</code> object:</p>
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
                     We currently only support European-style Options for Index Options.
                  </td>
            </tr>
        </tbody>
    </table>

<div class="section-example-container">
    <pre class="csharp">// Standard contracts
_canonicalSymbol = QuantConnect.Symbol.CreateCanonicalOption(_symbol, Market.USA, "?SPX");
var contractSymbols = OptionChainProvider.GetOptionContractList(_canonicalSymbol, Time);
var expiry = contractSymbols.Select(symbol =&gt; symbol.ID.Date).Min();
var filteredSymbols = contractSymbols.Where(symbol =&gt; symbol.ID.Date == expiry && symbol.ID.OptionRight == OptionRight.Call);
_contractSymbol = filteredSymbols.OrderByDescending(symbol =&gt; symbol.ID.StrikePrice).Last();

// Weekly contracts
_weeklyCanonicalSymbol = QuantConnect.Symbol.CreateCanonicalOption(_symbol, "SPXW", Market.USA, "?SPXW");
var weeklyContractSymbols = OptionChainProvider.GetOptionContractList(_weeklyCanonicalSymbol, Time)
    .Where(symbol =&gt; OptionSymbol.IsWeekly(symbol));
var weeklyExpiry = weeklyContractSymbols.Select(symbol =&gt; symbol.ID.Date).Min();
filteredSymbols = contractSymbols.Where(symbol =&gt; symbol.ID.Date == weeklyExpiry &amp;&amp; symbol.ID.OptionRight == OptionRight.Call);
_weeklyContractSymbol = filteredSymbols.OrderByDescending(symbol =&gt; symbol.ID.StrikePrice).Last();</pre>
    <pre class="python"># Standard contracts
self.canonical_symbol = Symbol.CreateCanonicalOption(self.symbol, Market.USA, "?SPX")       
contract_symbols = self.OptionChainProvider.GetOptionContractList(self.canonical_symbol, self.Time)
expiry = min([symbol.ID.Date for symbol in contract_symbols])
filtered_symbols = [symbol for symbol in contract_symbols if symbol.ID.Date == expiry and symbol.ID.OptionRight == OptionRight.Call]
self.contract_symbol = sorted(filtered_symbols, key=lambda symbol: symbol.ID.StrikePrice)[0]

# Weekly contracts
self.weekly_canonical_symbol = Symbol.CreateCanonicalOption(self.symbol, "SPXW", Market.USA, "?SPXW")
weekly_contract_symbols = self.OptionChainProvider.GetOptionContractList(self.weekly_canonical_symbol, self.Time)
weekly_contract_symbols = [symbol for symbol in weekly_contract_symbols if OptionSymbol.IsWeekly(symbol)]
weekly_expiry = min([symbol.ID.Date for symbol in weekly_contract_symbols])
weekly_filtered_symbols = [symbol for symbol in weekly_contract_symbols if symbol.ID.Date == weekly_expiry and symbol.ID.OptionRight == OptionRight.Call]
self.weekly_contract_symbol = sorted(weekly_filtered_symbols, key=lambda symbol: symbol.ID.StrikePrice)[0]</pre>
</div>

<h4>Subscribe to Contracts</h4>
<p>To create an Index Option contract subscription, pass the contract <code>Symbol</code> to the <code>AddIndexOptionContract</code> method. Save a reference to the contract <code>Symbol</code> so you can easily access the contract in the <a href="/docs/v2/writing-algorithms/securities/asset-classes/index-options/handling-data#04-Option-Chains">OptionChain</a> that LEAN passes to the <code>OnData</code> method. To override the default <a href="/docs/v2/writing-algorithms/reality-modeling/options-models/pricing">pricing model</a> of the Option, <a href='https://www.quantconnect.com/docs/v2/writing-algorithms/reality-modeling/options-models/pricing#04-Set-Models'>set a pricing model</a>.</p>

<div class="section-example-container">
    <pre class="csharp">var option = AddIndexOptionContract(_contractSymbol);
option.PriceModel = OptionPriceModels.BlackScholes();<br></pre>
    <pre class="python">option = self.AddIndexOptionContract(self.contract_symbol)
option.PriceModel = OptionPriceModels.BlackScholes()<br></pre>
</div>

<p>The <code>AddIndexOptionContract</code> method creates a subscription for a single Index Option contract and adds it to your <span class="new-term">user-defined</span> universe. To create a dynamic universe of Index Option contracts, add an <a href="/docs/v2/writing-algorithms/universes/index-options">Index Option universe</a>.</p>

<h4>Warm Up Contract Prices</h4>
<p>If you subscribe to an Index Option contract with <code>AddIndexOptionContract</code>, you'll need to wait until the next <code>Slice</code> to receive data and trade the contract. To trade the contract in the same time step you subscribe to the contract, set the current price of the contract in a <a href='/docs/v2/writing-algorithms/initialization#07-Set-Security-Initializer'>security initializer</a>.</p>
<div class="section-example-container">
    <pre class="csharp">var seeder = new FuncSecuritySeeder(GetLastKnownPrices);
SetSecurityInitializer(new BrokerageModelSecurityInitializer(BrokerageModel, seeder, this));</pre>
    <pre class="python">seeder = FuncSecuritySeeder(self.GetLastKnownPrices)
self.SetSecurityInitializer(BrokerageModelSecurityInitializer(self.BrokerageModel, seeder, self))</pre>
</div>

<h4>Supported Assets</h4>
<p>To view the supported assets in the US Index Options dataset, see <a href='/docs/v2/writing-algorithms/datasets/algoseek/us-index-options#05-Supported-Assets'>Supported Assets</a>.</p>
