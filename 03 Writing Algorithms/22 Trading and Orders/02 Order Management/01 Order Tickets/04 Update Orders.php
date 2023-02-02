<p>To update an order, use its <code>OrderTicket</code>. You can update other orders until they are filled or the brokerage prevents modifications. The specific properties you can update depends on the order type. The following table shows the properties you can update for each order type.<br></p>

<table class="qc-table table  table-condensed">
<thead>
<tr>
<th width="25%" rowspan="2">Order Type</th>
<th colspan="5">Updatable Properties</th> 
</tr>
<tr>
<th width="15%">Tag</th> 
<th width="15%">Quantity</th> 
<th width="15%">LimitPrice</th> 
<th width="15%">TriggerPrice</th> 
<th width="15%">StopPrice</th> 
</tr>
</thead>
<tbody>
<tr>
    <td>Market Order</td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
</tr>
<tr>
    <td>Limit Order</td>
    <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
    <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
    <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
    <td></td>
    <td></td>
</tr>
<tr>
    <td>Limit If Touched Order</td>
    <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
    <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
    <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
    <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
    <td></td>
</tr>
<tr>
    <td>Stop Market Order</td>
    <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
    <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
    <td></td>
    <td></td>
    <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
</tr>
<tr>
    <td>Stop Limit Order</td>
    <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
    <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
    <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
    <td></td>
    <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
</tr>
<tr>
    <td>Market On Open Order</td>
    <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
    <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
    <td></td>
    <td></td>
    <td></td>
</tr>
<tr>
    <td>Market On Close Order</td>
    <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
    <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
    <td></td>
    <td></td>
    <td></td>
</tr>
</tbody>
</table>

<p>Note that orders cannot be placed, canceled, or updated during warm up and initialization.</p>

<p>To update an order, pass an <code>UpdateOrderFields</code> object to the <code>Update</code> method. The method returns an <code>OrderResponse</code> to signal the success or failure of the update request. 
</p>

<div class="section-example-container">
<pre class="csharp">// Create an order 
var ticket = LimitOrder("SPY", 100, 221.05, tag: "New SPY trade");

// Update the order tag and limit price
var response = ticket.Update(new UpdateOrderFields()
{ 
    Tag = "Our New Tag for SPY Trade",
    LimitPrice = 222.00
});

// Check the OrderResponse
if (response.IsSuccess)
{ 
    Debug("Order updated successfully");
}</pre>
<pre class="python"> # Create an order 
ticket = self.LimitOrder("SPY", 100, 221.05, False, "New SPY trade")

# Update the order tag and limit price
updateSettings = UpdateOrderFields()
updateSettings.LimitPrice = 222.00
updateSettings.Tag = "Limit Price Updated for SPY Trade"
response = ticket.Update(updateSettings)

# Check the OrderResponse
if response.IsSuccess:
    self.Debug("Order updated successfully")</pre>
</div>

<?
$supportedMethods = array("UpdateLimitPrice", "UpdateQuantity", "UpdateStopPrice", "UpdateTag");
include(DOCS_RESOURCES."/order-types/update-individual-fields.php");
include(DOCS_RESOURCES."/order-types/update-requests.html");
?>

<div class="base-tree-container"></div>
<div><script data-tree="QuantConnect.Orders.OrderResponse"></script><script type="text/javascript">
    $(document).ready(function () {
        var dataTree = $("script[data-tree]").data("tree").split(",");
        initializeTreeView(dataTree);
    }); </script></div>


<p>Not all brokerages support order updates. To check if your brokerage supports order updates, see the <a href="/docs/v2/cloud-platform/live-trading/brokerages">brokerage integration documentation</a>.</p>