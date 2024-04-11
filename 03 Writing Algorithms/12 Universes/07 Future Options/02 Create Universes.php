<p>To add a universe of Future Option contracts, in the <code>Initialize</code> method, <a href="/docs/v2/writing-algorithms/universes/futures#11-Create-Universes">define a Future universe</a> and then pass the canonical <code>Symbol</code> to the <code>AddFutureOption</code> method.<br></p>

<div class="section-example-container">
    <pre class="csharp">UniverseSettings.Asynchronous = true;
var future = AddFuture(Futures.Metals.Gold);
future.SetFilter(0, 90);
AddFutureOption(future.Symbol);</pre>
    <pre class="python">self.universe_settings.asynchronous = True
future = self.add_future(Futures.metals.gold)
future.set_filter(0, 90)
self.add_future_option(future.symbol)</pre>
</div>


<p>The following table describes the <code>AddFutureOption</code> method arguments:</p>

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
            <td><code>symbol</code></td>
	        <td><code>Symbol</code></td>
            <td>The continuous Future contract Symbol. To view the supported assets in the US Future Options dataset, see <a href='/docs/v2/writing-algorithms/datasets/algoseek/us-future-options#06-Supported-Assets'>Supported Assets</a>.</td>
            <td></td>
        </tr>
        <tr>
            <td><code>optionFilter</code></td>
	        <td><code class="csharp">Func&lt;OptionFilterUniverse, OptionFilterUniverse&gt;</code><code class="python">Callable[[OptionFilterUniverse], OptionFilterUniverse]</code></td>
            <td>A function that selects Future Option contracts</td>
            <td><code class='csharp'>null</code><code class='python'>None</code></td>
        </tr>
    </tbody>
</table>


<p>To override the default <a href="/docs/v2/writing-algorithms/reality-modeling/options-models/pricing">pricing model</a> of the Option, <a href='https://www.quantconnect.com/docs/v2/writing-algorithms/reality-modeling/options-models/pricing#04-Set-Models'>set a pricing model</a> in a security initializer.</p>

<div class="section-example-container">
    <pre class="csharp">// In Initialize
var seeder = SecuritySeeder.Null;
SetSecurityInitializer(new MySecurityInitializer(BrokerageModel, seeder, this));

// Outside of the algorithm class
class MySecurityInitializer : BrokerageModelSecurityInitializer
{
    private QCAlgorithm _algorithm;

    public MySecurityInitializer(IBrokerageModel brokerageModel, ISecuritySeeder securitySeeder, QCAlgorithm algorithm)
        : base(brokerageModel, securitySeeder) 
    {
        _algorithm = algorithm;
    }
    
    public override void Initialize(Security security)
    {
        // First, call the superclass definition
        // This method sets the reality models of each security using the default reality models of the brokerage model
        base.Initialize(security);

        // Next, set the price model
        if (security.Type == SecurityType.FutureOption) // Option type
        {
            security.PriceModel = OptionPriceModels.CrankNicolsonFD();
        }
    }
}</pre>
    <pre class="python"># In Initialize
seeder = SecuritySeeder.null
self.set_security_initializer(MySecurityInitializer(self.brokerage_model, seeder, self))

# Outside of the algorithm class
class MySecurityInitializer(BrokerageModelSecurityInitializer):

    def __init__(self, brokerage_model: IBrokerageModel, security_seeder: ISecuritySeeder, algorithm: QCAlgorithm) -&gt; None:
        super().__init__(brokerage_model, security_seeder)
        self.algorithm = algorithm

    def initialize(self, security: Security) -&gt; None:
        # First, call the superclass definition
        # This method sets the reality models of each security using the default reality models of the brokerage model
        super().initialize(security)

        # Next, set the price model
        if security.type == SecurityType.future_option: # Option type
            security.price_model = OptionPriceModels.crank_nicolson_fd()</pre>
</div>

<?php echo file_get_contents(DOCS_RESOURCES."/reality-modeling/volatility-model.html"); ?>
