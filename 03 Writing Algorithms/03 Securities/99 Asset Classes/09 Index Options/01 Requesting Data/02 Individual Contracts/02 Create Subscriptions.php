<p>Before you can subscribe to an Index Option contract, you must configure the underlying Index and get the contract <code class="csharp">Symbol</code><code class="python">symbol</code>.</p>

<div class="section-example-container">
    <pre class="csharp">public class BasicIndexOptionAlgorithm : QCAlgorithm
{
    private Symbol _underlying;
    private Option _contract = null;

    public override void Initialize()
    {
        SetStartDate(2020, 1, 1);
        _underlying = AddIndex("SPX").Symbol;
    }

    public override void OnData(Slice data)
    {
        if (_contract == null)
        {
            var contractSymbols = OptionChainProvider.GetOptionContractList(_underlying, Time);
            var expiry = contractSymbols.Min(symbol => symbol.ID.Date);
            var filteredSymbols = contractSymbols
                .Where(symbol => symbol.ID.Date == expiry && symbol.ID.OptionRight == OptionRight.Call)
                .ToList();
            var symbol = filteredSymbols.OrderBy(symbol => symbol.ID.StrikePrice).First();
            _contract = AddOptionContract(symbol);
        }
    }
}</pre>
    <pre class="python">class BasicIndexOptionAlgorithm(QCAlgorithm):
    def initialize(self):
        self.set_start_date(2020, 1, 1)
        self._underlying = self.add_index("SPX").symbol
        self._contract = None
    
    def on_data(self, data):
        if not self._contract:
            contract_symbols = self.option_chain_provider.get_option_contract_list(self._underlying, self.time)
            expiry = min([symbol.id.date for symbol in contract_symbols])
            filtered_symbols = [symbol for symbol in contract_symbols if symbol.id.date == expiry and symbol.id.option_right == OptionRight.CALL]
            symbol = sorted(filtered_symbols, key=lambda symbol: symbol.id.strike_price)[0]
            self._contract = self.add_option_contract(symbol)</pre>
</div>

<h4>Configure the Underlying Index</h4>
<p>In most cases, you should <a href='/docs/v2/writing-algorithms/securities/asset-classes/index/requesting-data#02-Create-Subscriptions'>subscribe to the underlying Index</a> before you subscribe to an Index Option contract.</p>

<div class="section-example-container">
    <pre class="csharp">_symbol = AddIndex("SPX").Symbol;</pre>
    <pre class="python">self._symbol = self.add_index("SPX").symbol</pre>
</div>

<h4>Get Contract Symbols</h4>

<p>
    To subscribe to an Option contract, you need the contract <code>Symbol</code>.
    The preferred method to getting Option contract <code>Symbol</code> objects is to use the <code class="csharp">OptionChainProvider</code><code class="python">option_chain_provider</code>. 
    The <code class="csharp">GetOptionContractList</code><code class="python">get_option_contract_list</code> method of <code class="csharp">OptionChainProvider</code><code class="python">option_chain_provider</code> returns a list of <code>Symbol</code> objects for a given date and underlying Index, which you can then sort and filter to find the specific contract(s) you want to trade. 
</p>

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
self._canonical_symbol = Symbol.create_canonical_option(self._symbol, Market.USA, "?SPX")       
contract_symbols = self.option_chain_provider.get_option_contract_list(self._canonical_symbol, self.time)
expiry = min([symbol.id.date for symbol in contract_symbols])
filtered_symbols = [symbol for symbol in contract_symbols if symbol.id.date == expiry and symbol.id.option_right == OptionRight.CALL]
self._contract_symbol = sorted(filtered_symbols, key=lambda symbol: symbol.id.strike_price)[0]

# Weekly contracts
self._weekly_canonical_symbol = Symbol.create_canonical_option(self._symbol, "SPXW", Market.USA, "?SPXW")
weekly_contract_symbols = self.option_chain_provider.get_option_contract_list(self._weekly_canonical_symbol, self.time)
weekly_contract_symbols = [symbol for symbol in weekly_contract_symbols if OptionSymbol.is_weekly(symbol)]
weekly_expiry = min([symbol.id.date for symbol in weekly_contract_symbols])
weekly_filtered_symbols = [symbol for symbol in weekly_contract_symbols if symbol.id.date == weekly_expiry and symbol.id.option_right == OptionRight.CALL]
self._weekly_contract_symbol = sorted(weekly_filtered_symbols, key=lambda symbol: symbol.id.strike_price)[0]</pre>
</div>

<p>To filter and select contracts, you can use the following properties of each <code>Symbol</code> object:</p>
    <table class="qc-table table">
        <thead>
            <tr>
                <th>Property</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                 <td><code class="csharp">ID.Date</code><code class="python">id.date</code></td>
                 <td>The expiration date of the contract.</td>
            </tr>
            <tr>
                 <td><code class="csharp">ID.StrikePrice</code><code class="python">id.strike_price</code></td>
                 <td>The strike price of the contract.</td>
            </tr>
            <tr>
                 <td><code class="csharp">ID.OptionRight</code><code class="python">id.option_right</code></td>
                 <td>
                     The contract type, <code class="csharp">OptionRight.Put</code><code class="python">OptionRight.PUT</code> or <code class="csharp">OptionRight.Call</code><code class="python">OptionRight.CALL</code>.
                  </td>
            </tr>
            <tr>
                 <td><code class="csharp">ID.OptionStyle</code><code class="python">id.option_style</code></td>
                 <td>
                     The contract style, <code class="csharp">OptionStyle.American</code><code class="python">OptionStyle.AMERICAN</code> or <code class="csharp">OptionStyle.European</code><code class="python">OptionStyle.EUROPEAN</code>. 
                     We currently only support European-style Options for Index Options.
                  </td>
            </tr>
        </tbody>
    </table>

<h4>Subscribe to Contracts</h4>
<p>To create an Index Option contract subscription, pass the contract <code>Symbol</code> to the <code class="csharp">AddIndexOptionContract</code><code class="python">add_index_option_contract</code> method. Save a reference to the contract <code>Symbol</code> so you can easily access the contract in the <a href="/docs/v2/writing-algorithms/securities/asset-classes/index-options/handling-data#04-Option-Chains">OptionChain</a> that LEAN passes to the <code class="csharp">OnData</code><code class="python">on_data</code> method. To override the default <a href="/docs/v2/writing-algorithms/reality-modeling/options-models/pricing">pricing model</a> of the Option, <a href='https://www.quantconnect.com/docs/v2/writing-algorithms/reality-modeling/options-models/pricing#04-Set-Models'>set a pricing model</a>.</p>

<div class="section-example-container">
    <pre class="csharp">var option = AddIndexOptionContract(_contractSymbol);
option.PriceModel = OptionPriceModels.BlackScholes();<br></pre>
    <pre class="python">option = self.add_index_option_contract(self._contract_symbol)
option.PriceModel = OptionPriceModels.black_scholes()<br></pre>
</div>

<p>The <code class="csharp">AddIndexOptionContract</code><code class="python">add_index_option_contract</code> method creates a subscription for a single Index Option contract and adds it to your <span class="new-term">user-defined</span> universe. To create a dynamic universe of Index Option contracts, add an <a href="/docs/v2/writing-algorithms/universes/index-options">Index Option universe</a>.</p>

<h4>Warm Up Contract Prices</h4>
<p>If you subscribe to an Index Option contract with <code class="csharp">AddIndexOptionContract</code><code class="python">add_index_option_contract</code>, you'll need to wait until the next <code>Slice</code> to receive data and trade the contract. To trade the contract in the same time step you subscribe to the contract, set the current price of the contract in a <a href='/docs/v2/writing-algorithms/initialization#07-Set-Security-Initializer'>security initializer</a>.</p>
<div class="section-example-container">
    <pre class="csharp">var seeder = new FuncSecuritySeeder(GetLastKnownPrices);
SetSecurityInitializer(new BrokerageModelSecurityInitializer(BrokerageModel, seeder));</pre>
    <pre class="python">seeder = FuncSecuritySeeder(self.get_last_known_prices)
self.set_security_initializer(BrokerageModelSecurityInitializer(self.brokerage_model, seeder))</pre>
</div>

<h4>Supported Assets</h4>
<p>To view the supported assets in the US Index Options dataset, see <a href='/docs/v2/writing-algorithms/datasets/algoseek/us-index-options#08-Supported-Assets'>Supported Assets</a>.</p>
