<p>
You can update the quantity, trigger price, limit price, and tag of LIT orders until the order fills or the brokerage prevents modifications. To update an order, pass an <code>UpdateOrderFields</code> object to the <code>Update</code> method on the <code>OrderTicket</code>. If you don't have the order ticket, <a href='/docs/v2/writing-algorithms/trading-and-orders/order-management/transaction-manager#02-Get-a-Single-Order-Ticket'>get it from the transaction manager</a>. The <code>Update</code> method returns an <a href='/docs/v2/writing-algorithms/trading-and-orders/order-management/order-tickets#06-Order-Response'>OrderResponse</a> to signal the success or failure of the update request.</p>

<div class="section-example-container">
<pre class="csharp">// Create a new order and save the order ticket
var ticket = LimitIfTouchedOrder("SPY", 100, 350, 340, tag: "Original tag");

// Update the order
var updateOrderFields = new UpdateOrderFields()
{
    Quantity = 80,
    TriggerPrice = 380,
    LimitPrice = 370,
    Tag = "New tag"
}
var response = ticket.Update(updateOrderFields);

// Check the OrderResponse
if (response.IsSuccess)
{ 
    Debug("Order updated successfully");
}</pre>
<pre class="python"># Create a new order and save the order ticket
ticket = self.limit_if_touched_order("SPY", 100, 350, 340, tag="Original tag")

# Update the order
update_order_fields = UpdateOrderFields()
update_order_fields.quantity = 80
update_order_fields.trigger_price = 380
update_order_fields.limit_price = 370
update_order_fields.tag = "New tag"
response = ticket.update(update_settings)

# Check the OrderResponse
if response.is_success:
    self.debug("Order updated successfully")</pre>
</div>

<? 
$supportedMethods = array("UpdateLimitPrice", "UpdateQuantity", "UpdateTriggerPrice", "UpdateTag");
include(DOCS_RESOURCES."/order-types/update-individual-fields.php");
include(DOCS_RESOURCES."/order-types/update-requests.html");
?>