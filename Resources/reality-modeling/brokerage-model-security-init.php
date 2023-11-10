<?php
    $comment = $comment ?? "some of the reality models";
    if (isset($saveAlgorithm) && $saveAlgorithm)
    {
        $extraArgsC = ", this";
        $extraArgsPy = ", self";
        $extraParamsC = ", QCAlgorithm algorithm";
        $extraParamsPy = ", algorithm: QCAlgorithm";
        $classMemberC = "private QCAlgorithm _algorithm;

    ";
        $contructorBodyC = "
    {
        _algorithm = algorithm;
    }";
        $contructorBodyPy = "
        self.algorithm = algorithm";
    }
    else
    {
        $extraArgsC = "";
        $extraArgsPy = "";
        $extraParamsC = "";
        $extraParamsPy = "";
        $classMemberC = "";
        $contructorBodyC = "{}";
        $contructorBodyPy = "";
    }
?>
<div class='section-example-container'>
<pre class='csharp'>// In Initialize
SetSecurityInitializer(new MySecurityInitializer(BrokerageModel, new FuncSecuritySeeder(GetLastKnownPrices)<?=$extraArgsC?>));

// Outside of the algorithm class
class MySecurityInitializer : BrokerageModelSecurityInitializer
{
    <?=$classMemberC?>public MySecurityInitializer(IBrokerageModel brokerageModel, ISecuritySeeder securitySeeder<?=$extraParamsC?>)
        : base(brokerageModel, securitySeeder) <?=$contructorBodyC ?>
    
    
    public override void Initialize(Security security)
    {
        // First, call the superclass definition
        // This method sets the reality models of each security using the default reality models of the brokerage model
        base.Initialize(security);

        // Next, overwrite <?=$comment?>
        
        <?=$overwriteCodeC?>
    
    }
}</pre>
<pre class='python'># In Initialize
self.SetSecurityInitializer(MySecurityInitializer(self.BrokerageModel, FuncSecuritySeeder(self.GetLastKnownPrices)<?=$extraArgsPy?>))

# Outside of the algorithm class
class MySecurityInitializer(BrokerageModelSecurityInitializer):

    def __init__(self, brokerage_model: IBrokerageModel, security_seeder: ISecuritySeeder<?=$extraParamsPy?>) -> None:
        super().__init__(brokerage_model, security_seeder)<?=$contructorBodyPy?>


    def Initialize(self, security: Security) -> None:
        # First, call the superclass definition
        # This method sets the reality models of each security using the default reality models of the brokerage model
        super().Initialize(security)

        # Next, overwrite <?=$comment?>
        
        <?=$overwriteCodePy?></pre>
</div>
