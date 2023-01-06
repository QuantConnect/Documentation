<p>To set the pricing model of an Option, set its <code>PriceModel</code> property.</p>

<p>If you have access to the <code>Option</code> object when you subscribe to the Option universe or contract, you can set the price model immediately after you create the subscription.</p>

<div class="section-example-container">
    <pre class="csharp">// In Initialize
var option = AddOption("SPY");
option.PriceModel = OptionPriceModels.CrankNicolsonFD();</pre>
    <pre class="python"># In Initialize
option = self.AddOption("SPY")
option.PriceModel = OptionPriceModels.CrankNicolsonFD()</pre>
</div>

<p>Otherwise, set the price model in a <a href='/docs/v2/writing-algorithms/initialization#07-Set-Security-Initializer'>security initializer</a>.</p>

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
        if (security.Type == SecurityType.Option) // Option type
        {
            security.PriceModel = OptionPriceModels.CrankNicolsonFD();
        }
    }
}</pre>
    <pre class="python"># In Initialize
seeder = SecuritySeeder.Null
self.SetSecurityInitializer(MySecurityInitializer(self.BrokerageModel, seeder, self))

# Outside of the algorithm class
class MySecurityInitializer(BrokerageModelSecurityInitializer):

    def __init__(self, brokerage_model: IBrokerageModel, security_seeder: ISecuritySeeder, algorithm: QCAlgorithm) -&gt; None:
        super().__init__(brokerage_model, security_seeder)
        self.algorithm = algorithm

    def Initialize(self, security: Security) -&gt; None:
        # First, call the superclass definition
        # This method sets the reality models of each security using the default reality models of the brokerage model
        super().Initialize(security)

        # Next, set the price model
        if security.Type == SecurityType.Equity: # Option type
            security.PriceModel = OptionPriceModels.CrankNicolsonFD()</pre>
</div>