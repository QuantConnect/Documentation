<p>Before you can subscribe to an Index Option contract, you must configure the underlying Index and get the contract <code class="csharp">Symbol</code><code class="python">symbol</code>.</p>

<div class="section-example-container">
    <pre class="csharp">public class BasicIndexOptionAlgorithm : QCAlgorithm
{
    private Symbol _underlying, _contractSymbol;
    public override void Initialize()
    {
        SetStartDate(2024, 1, 1);
        _underlying = AddIndex("SPX").Symbol;
    }

    public override void OnData(Slice data)
    {
        if (_contractSymbol != null)
        {
            return;
        }
        var chain = OptionChain(_underlying);
        var expiry = chain.Select(contract => contract.Expiry).Min();
        _contractSymbol = chain
            .Where(contract => 
                contract.Expiry == expiry && 
                contract.Right == OptionRight.Call &&
                contract.Greeks.Delta > 0.3m && 
                contract.Greeks.Delta < 0.7m
            )
            .OrderByDescending(contract => contract.OpenInterest)
            .First()
            .Symbol;
        AddOptionContract(_contractSymbol);
    }
}</pre>
    <pre class="python">class BasicIndexOptionAlgorithm(QCAlgorithm):

    def initialize(self):
        self.set_start_date(2024, 1, 1)
        self._underlying = self.add_index("SPX").symbol
        self._contract_symbol = None

    def on_data(self, data):
        if self._contract_symbol:
            return
        chain = self.option_chain(self._underlying, flatten=True).data_frame
        expiry = chain.expiry.min()
        self._contract_symbol = chain[
            (chain.expiry == expiry) &
            (chain.right == OptionRight.CALL) &
            (chain.delta < 0.7) &
            (chain.delta > 0.3)
        ].sort_values('openinterest').index[-1]
        self.add_option_contract(self._contract_symbol)</pre>
</div>

<h4>Configure the Underlying Index</h4>
<p>In most cases, you should <a href='/docs/v2/writing-algorithms/securities/asset-classes/index/requesting-data#02-Create-Subscriptions'>subscribe to the underlying Index</a> before you subscribe to an Index Option contract.</p>

<div class="section-example-container">
    <pre class="csharp">_underlying = AddIndex("SPX").Symbol;</pre>
    <pre class="python">self._underlying = self.add_index("SPX").symbol</pre>
</div>

<h4>Get Contract Symbols</h4>

<p>
    To subscribe to an Option contract, you need the contract <code>Symbol</code>. 
    The preferred method to getting Option contract <code>Symbol</code> objects is to use the <code class="csharp">OptionChain</code><code class="python">option_chain</code> method. 
    This method returns an <code>OptionChain</code> object, which represent an entire chain of Option contracts for a single underlying security.
    <span class='python'>
        You can even format the chain data into a DataFrame where each row in the DataFrame represents a single contract.
    </span>
    With the chain, sort and filter the data to find the specific contract(s) you want to trade.
</p>

<div class="section-example-container">
    <pre class="csharp">// Get the contracts available to trade.
//   Option A: Standard contracts.
var chain = OptionChain(
    QuantConnect.Symbol.CreateCanonicalOption(_underlying, Market.USA, "?SPX")
);

//   Option B: Weekly contracts.
//var chain = OptionChain(
//    QuantConnect.Symbol.CreateCanonicalOption(_underlying, "SPXW", Market.USA, "?SPXW")
//);

// Select a contract.
var expiry = chain.Select(contract => contract.Expiry).Min();
_contractSymbol = chain
    .Where(contract => 
        // Select call contracts with the closest expiry.
        contract.Expiry == expiry && 
        contract.Right == OptionRight.Call &&
        // Select contracts with a 0.3-0.7 delta.
        contract.Greeks.Delta > 0.3m && 
        contract.Greeks.Delta < 0.7m
    )
    // Select the contract with the largest open interest.
    .OrderByDescending(contract => contract.OpenInterest)
    .First()
    // Get the Symbol of the target contract.
    .Symbol;</pre>
    <pre class="python"># Get the contracts available to trade (in DataFrame format).
#   Option A: Standard contracts.
chain = self.option_chain(
    Symbol.create_canonical_option(self._underlying, Market.USA, "?SPX"), flatten=True
).data_frame

#  Option B: Weekly contracts.
#chain = self.option_chain(
#    Symbol.create_canonical_option(self._underlying, "SPXW", Market.USA, "?SPXW"), flatten=True
#).data_frame

# Select a contract.
expiry = chain.expiry.min()
self._contract_symbol = chain[
    # Select call contracts with the closest expiry.
    (chain.expiry == expiry) &
    (chain.right == OptionRight.CALL) &
    # Select contracts with a 0.3-0.7 delta.
    (chain.delta < 0.7) &
    (chain.delta > 0.3)
    # Select the contract with the largest open interest.
].sort_values('openinterest').index[-1]</pre>
</div>

<h4>Subscribe to Contracts</h4>
<p>To create an Index Option contract subscription, pass the contract <code>Symbol</code> to the <code class="csharp">AddIndexOptionContract</code><code class="python">add_index_option_contract</code> method. Save a reference to the contract <code>Symbol</code> so you can easily access the contract in the <a href="/docs/v2/writing-algorithms/securities/asset-classes/index-options/handling-data#04-Option-Chains">OptionChain</a> that LEAN passes to the <code class="csharp">OnData</code><code class="python">on_data</code> method. To override the default <a href="/docs/v2/writing-algorithms/reality-modeling/options-models/pricing">pricing model</a> of the Option, <a href='https://www.quantconnect.com/docs/v2/writing-algorithms/reality-modeling/options-models/pricing#03-Set-Models'>set a pricing model</a>.</p>

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
