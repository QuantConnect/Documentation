<p>You can update the quantity, limit price, and tag of limit orders until the order fills or the brokerage prevents modifications. To update an order, pass an <code>UpdateOrderFields</code> object to the <code>Update</code> method on the <code>OrderTicket</code>. If you don't have the order ticket, <a href='/docs/v2/writing-algorithms/trading-and-orders/order-management/transaction-manager#02-Get-a-Single-Order-Ticket'>get it from the transaction manager</a>. The <code>Update</code> method returns an <a href='/docs/v2/writing-algorithms/trading-and-orders/order-management/order-tickets#06-Order-Response'>OrderResponse</a> to signal the success or failure of the update request.</p>

<div class="section-example-container">
<pre class="csharp">// Create a new order and save the order ticket
var ticket = LimitOrder("SPY", 100, 221.05m, tag: "original tag");

// Update the order
var response = ticket.Update(new UpdateOrderFields() 
{
    Quantity = 80,
    LimitPrice = 222.00m,
    Tag = "new tag"
});

// Check if the update was successful
if (response.IsSuccess) 
{
    Debug("Order updated successfully");
}</pre>
<pre class="python"># Create a new order and save the order ticket
ticket = self.LimitOrder("SPY", 100, 221.05, tag="original tag")

# Update the order
update_settings = UpdateOrderFields()
update_settings.Quantity = 80
update_settings.LimitPrice = 222.00
update_settings.Tag = "new tag"
response = ticket.Update(update_settings)

# Check if the update was successful
if response.IsSuccess:
    self.Debug("Order updated successfully")</pre>
</div>

<?
$supportedMethods = array("UpdateLimitPrice", "UpdateQuantity", "UpdateTag");
include(DOCS_RESOURCES."/order-types/update-individual-fields.php");
include(DOCS_RESOURCES."/order-types/update-requests.html");
?>