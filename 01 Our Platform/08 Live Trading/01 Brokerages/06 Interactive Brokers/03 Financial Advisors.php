<p>IB supports FA accounts for Trading Firm and Institution organizations. FA accounts enable certified professionals to use a single trading algorithm to manage several client accounts. To place trades using a subset of client accounts, <a rel="nofollow" target="_blank" href="https://guides.interactivebrokers.com/tws/usersguidebook/financialadvisors/create_an_account_group_for_share_allocation.htm">create Account Groups in Trader Workstation</a> and then define the <code>InteractiveBrokersOrderProperties</code> when you create orders.</p>

<div class="section-example-container">
    <pre class="csharp">DefaultOrderProperties = new InteractiveBrokersOrderProperties
{
    FaGroup = "TestGroupEQ",
    FaMethod = "EqualQuantity",
    FaProfile = "TestProfileP",
    Account = "DU123456"
};</pre>
    <pre class="python">self.DefaultOrderProperties = InteractiveBrokersOrderProperties()
self.DefaultOrderProperties.FaGroup = "TestGroupEQ"
self.DefaultOrderProperties.FaMethod = "EqualQuantity"
self.DefaultOrderProperties.FaProfile = "TestProfileP"
self.DefaultOrderProperties.Account = "DU123456"</pre>
</div>

<?php echo file_get_contents(DOCS_RESOURCES."/specs/backtest-nodes.html"); ?>

<div class="section-example-container">
    <pre class="csharp">public override void Initialize()
{
    // Set the default order properties
    DefaultOrderProperties = new InteractiveBrokersOrderProperties()
    {
        FaGroup = "TestGroupEQ",
        FaMethod = "EqualQuantity",
        FaProfile = "TestProfileP",
        Account = "DU123456"
    };
}

public override void OnData(Slice data)
{
    // Use default order order properties
    LimitOrder(_symbol, quantity, limitPrice);

    // Override the default order properties
    // "NetLiq" requires a order size input
    LimitOrder(_symbol, quantity, limitPrice, 
               orderProperties: new InteractiveBrokersOrderProperties
               { 
                   FaMethod = "NetLiq" 
               });

    // "AvailableEquity" requires a order size input
    LimitOrder(_symbol, quantity, limitPrice, 
               orderProperties: new InteractiveBrokersOrderProperties
               { 
                   FaMethod = "AvailableEquity" 
               });
    
    // "PctChange" requires a percentage of portfolio input
    SetHoldings(_symbol, pctPortfolio,
                orderProperties: new InteractiveBrokersOrderProperties
                { 
                    FaMethod = "PctChange" 
                });
}</pre>
    <pre class="python">def Initialize(self):
    # Set the default order properties
    self.DefaultOrderProperties = InteractiveBrokersOrderProperties()
    self.DefaultOrderProperties.FaGroup = "TestGroupEQ"
    self.DefaultOrderProperties.FaMethod = "EqualQuantity"
    self.DefaultOrderProperties.FaProfile = "TestProfileP"
    self.DefaultOrderProperties.Account = "DU123456"

def OnData(self, data):
    # Override the default order properties
    # "NetLiq" requires a order size input
    order_properties = InteractiveBrokersOrderProperties()
    order_properties.FaMethod = "NetLiq"
    self.LimitOrder(self.symbol, quantity, limit_price, orderProperties=order_properties)

    # "AvailableEquity" requires a order size input
    order_properties.FaMethod = "AvailableEquity"
    self.LimitOrder(self.symbol, quantity, limit_price, orderProperties=order_properties)

    # "PctChange" requires a percentage of portfolio input
    order_properties.FaMethod = "PctChange"
    self.SetHoldings(self.symbol, pct_portfolio, orderProperties=order_properties)</pre>
</div>
