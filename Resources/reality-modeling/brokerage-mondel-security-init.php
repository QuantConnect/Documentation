<?php
$getBrokerageModelInitCodeBlock = function($overwriteCodePy, $overwriteCodeC)
{
    echo "
<div class='section-example-container'>
<pre class='csharp'>// In Initialize
SetSecurityInitializer(new MySecurityInitializer(BrokerageModel, new FuncSecuritySeeder(GetLastKnownPrices)));

class MySecurityInitializer : BrokerageModelSecurityInitializer
{
    public MySecurityInitializer(IBrokerageModel brokerageModel, ISecuritySeeder securitySeeder)
        : base(brokerageModel, securitySeeder) {}
    
    public override void Initialize(Security security)
    {
        // First, call the superclass definition
        // This method sets the reality models of each security using the default reality models of the brokerage model
        base.Initialize(security);

        // Next, overwrite some of the reality models
        {$overwriteCodeC}
    }
}</pre>
<pre class='python'># In Initialize
self.SetSecurityInitializer(MySecurityInitializer(self.BrokerageModel, FuncSecuritySeeder(self.GetLastKnownPrices)))


class MySecurityInitializer(BrokerageModelSecurityInitializer):

    def __init__(self, brokerage_model: IBrokerageModel, security_seeder: ISecuritySeeder) -> None:
        super().__init__(brokerage_model, security_seeder)

    def Initialize(self, security: Security) -> None:
        # First, call the superclass definition
        # This method sets the reality models of each security using the default reality models of the brokerage model
        super().Initialize(security)

        # Next, overwrite some of the reality models
        {$overwriteCodePy}</pre>
</div>
    "; 
}
?>
