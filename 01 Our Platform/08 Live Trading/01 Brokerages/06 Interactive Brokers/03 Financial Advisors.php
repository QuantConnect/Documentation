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

<?php echo file_get_contents(DOCS_RESOURCES."/order-types/financial-advisors/allocation-methods.html"); ?>
