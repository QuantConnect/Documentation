<p>
You can update the quantity, trigger price, limit price, and tag of LIT orders until the order fills or the brokerage prevents modifications. To update an order, pass an <code>UpdateOrderFields</code> object to the <code>Update</code> method on the <code>OrderTicket</code>. The <code>Update</code> method returns an <a href='/docs/v2/writing-algorithms/trading-and-orders/order-management/order-tickets#06-Order-Response'>OrderResponse</a> to signal the success or failure of the update request.</p>

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
_response = ticket.Update(updateOrderFields);
</pre>
<pre class="python"># Create a new order and save the order ticket
ticket = self.LimitIfTouchedOrder("SPY", 100, 350, 340, tag="Original tag")

# Update the order
update_order_fields = UpdateOrderFields()
update_order_fields.Quantity = 80
update_order_fields.TriggerPrice = 380
update_order_fields.LimitPrice = 370
update_order_fields.Tag = "New tag"
self.response = ticket.Update(update_settings)
</pre>
</div>

<?php 
include(DOCS_RESOURCES."/order-types/update-individual-fields.php");
$supportedMethods = array("UpdateLimitPrice", "UpdateQuantity", "UpdateTag");
$getUpdateIndividualFieldsText($supportedMethods);

echo file_get_contents(DOCS_RESOURCES."/order-types/update-requests.html");

?>

