<p>Before you can subscribe to an Option contract, you must configure the underlying Equity and get the contract <code>Symbol</code>.</p>

<div class="section-example-container">
    <pre class="csharp">public class BasicOptionAlgorithm : QCAlgorithm
{
    private Symbol _underlying;
    private Option _contract = null;

    public override void Initialize()
    {
        SetStartDate(2020, 1, 1);
        _underlying = AddEquity("SPY", dataNormalizationMode: DataNormalizationMode.Raw).Symbol;
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
    <pre class="python">class BasicOptionAlgorithm(QCAlgorithm):
    def initialize(self):
        self.set_start_date(2020, 1, 1)
        self._underlying = self.add_equity("SPY", data_normalization_mode=DataNormalizationMode.RAW).symbol
        self._contract = None
    
    def on_data(self, data):
        if not self._contract:
            contract_symbols = self.option_chain_provider.get_option_contract_list(self._underlying, self.time)
            expiry = min([symbol.id.date for symbol in contract_symbols])
            filtered_symbols = [symbol for symbol in contract_symbols if symbol.id.date == expiry and symbol.id.option_right == OptionRight.CALL]
            symbol = sorted(filtered_symbols, key=lambda symbol: symbol.id.strike_price)[0]
            self._contract = self.add_option_contract(symbol)</pre>
</div>

<h4>Configure the Underlying Equity</h4>

<p>If you want to subscribe to the underlying Equity in the <code class="csharp">Initialize</code><code class="python">initialize</code> method, set the Equity <a href="/docs/v2/writing-algorithms/securities/asset-classes/us-equity/requesting-data#11-Data-Normalization">data normalization</a> to <code class="csharp">DataNormalizationMode.Raw</code><code class="python">DataNormalizationMode.RAW</code>.</p>

<div class="section-example-container">
    <pre class="csharp">_symbol = AddEquity("SPY", dataNormalizationMode: DataNormalizationMode.Raw).Symbol;</pre>
    <pre class="python">self._symbol = self.add_equity("SPY", data_normalization_mode=DataNormalizationMode.RAW).symbol</pre>
</div>

<p>If your algorithm has a dynamic <a href="/docs/v2/writing-algorithms/universes/equity">universe</a> of Equities, before you add the Equity universe in the <code class="csharp">Initialize</code><code class="python">initialize</code> method, set the universe data normalization mode to <code class="csharp">DataNormalizationMode.Raw</code><code class="python">DataNormalizationMode.RAW</code>.</p>

<div class="section-example-container">
    <pre class="csharp">UniverseSettings.DataNormalizationMode = DataNormalizationMode.Raw;</pre>
    <pre class="python">self.universe_settings.data_normalization_mode = DataNormalizationMode.RAW</pre>
</div>

<?php echo file_get_contents(DOCS_RESOURCES."/reality-modeling/volatility-model.html"); ?>

<h4>Get Contract Symbols</h4>

<p>
    To subscribe to an Option contract, you need the contract <code>Symbol</code>. 
    The perferred method to getting Option contract <code>Symbol</code> objects is to use the <code class="csharp">OptionChainProvider</code><code class="python">option_chain_provider</code>. 
    The <code class="csharp">GetOptionContractList</code><code class="python">get_option_contract_list</code> method of <code class="csharp">OptionChainProvider</code><code class="python">option_chain_provider</code> returns a list of <code>Symbol</code> objects for a given date and underlying Equity, which you can then sort and filter to find the specific contract(s) you want to trade. 
    To filter and select contracts, you can use the following properties of each <code>Symbol</code> object:
</p>

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
                     The contract type. The <code>OptionRight</code> enumeration has the following members:
                     <div data-tree="QuantConnect.OptionRight"></div>
                  </td>
            </tr>
            <tr>
                 <td><code class="csharp">ID.OptionStyle</code><code class="python">id.option_style</code></td>
                 <td>
                     The contract style. The <code>OptionStyle</code> enumeration has the following members:
                     <div data-tree="QuantConnect.OptionStyle"></div>
                     We currently only support American-style Options for US Equity Options.
                  </td>
            </tr>
        </tbody>
    </table>

<div class="section-example-container">
    <pre class="csharp">var contractSymbols = OptionChainProvider.GetOptionContractList(_symbol, Time);
var expiry = contractSymbols.Select(symbol =&gt; symbol.ID.Date).Min();
var filteredSymbols = contractSymbols.Where(symbol =&gt; symbol.ID.Date == expiry &amp;&amp; symbol.ID.OptionRight == OptionRight.Call);
_contractSymbol = filteredSymbols.OrderByDescending(symbol =&gt; symbol.ID.StrikePrice).Last();</pre>
    <pre class="python">contract_symbols = self.option_chain_provider.get_option_contract_list(self._symbol, self.time)
expiry = min([symbol.id.date for symbol in contract_symbols])
filtered_symbols = [symbol for symbol in contract_symbols if symbol.id.date == expiry and symbol.id.option_right == OptionRight.CALL]
self._contract_symbol = sorted(filtered_symbols, key=lambda symbol: symbol.id.strike_price)[0]</pre>
</div>

<h4>Subscribe to Contracts</h4>

<p>To create an Equity Option contract subscription, pass the contract <code>Symbol</code> to the <code class="csharp">AddOptionContract</code><code class="python">add_option_contract</code>  method. Save a reference to the contract <code class="csharp">Symbol</code><code class="python">symbol</code> so you can easily access the Option contract in the <a href="/docs/v2/writing-algorithms/securities/asset-classes/equity-options/handling-data#04-Option-Chains">OptionChain</a> that LEAN passes to the <code class="csharp">OnData</code><code class="python">on_data</code> method. This method returns an <code>Option</code> object. To override the default <a href="/docs/v2/writing-algorithms/reality-modeling/options-models/pricing">pricing model</a> of the Option, <a href='https://www.quantconnect.com/docs/v2/writing-algorithms/reality-modeling/options-models/pricing#04-Set-Models'>set a pricing model</a>.</p>

<div class="section-example-container">
    <pre class="csharp">var option = AddOptionContract(_contractSymbol);
option.PriceModel = OptionPriceModels.BinomialCoxRossRubinstein();</pre>
    <pre class="python">option = self.add_option_contract(self._contract_symbol)
option.price_model = OptionPriceModels.binomial_cox_ross_rubinstein()</pre>
</div>

<p>The <code class="csharp">AddOptionContract</code><code class="python">add_option_contract</code>  method creates a subscription for a single Option contract and adds it to your <span class="new-term">user-defined</span> universe. To create a dynamic universe of Option contracts, add an <a href="/docs/v2/writing-algorithms/universes/equity-options">Equity Options universe</a> or an <a href="/docs/v2/writing-algorithms/algorithm-framework/universe-selection/options-universes">Options Universe Selection model</a>.</p>

<h4>Warm Up Contract Prices</h4>

<p>If you subscribe to an Option contract with <code class="csharp">AddOptionContract</code><code class="python">add_option_contract</code>, you'll need to wait until the next <code>Slice</code> to receive data and trade the contract. To trade the contract in the same time step you subscribe to the contract, set the current price of the contract in a <a href='/docs/v2/writing-algorithms/initialization#07-Set-Security-Initializer'>security initializer</a>.</p>

<div class="section-example-container">
    <pre class="csharp">var seeder = new FuncSecuritySeeder(GetLastKnownPrices);
SetSecurityInitializer(new BrokerageModelSecurityInitializer(BrokerageModel, seeder));</pre>
    <pre class="python">seeder = FuncSecuritySeeder(self.get_last_known_prices)
self.set_security_initializer(BrokerageModelSecurityInitializer(self.brokerage_model, seeder))</pre>
</div>

<h4>Supported Assets</h4>

<p>To view the supported assets in the US Equities dataset, see the <a href="/datasets/algoseek-us-equity-options/explorer">Data Explorer</a>.</p>
