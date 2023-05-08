<p>You can update the quantity, limit price, and tag of the leg limit orders until the combo order fills or the brokerage prevents modifications. To update an order, pass an <code>UpdateOrderFields</code> object to the <code>Update</code> method on the <code>OrderTicket</code>. If you don't have the order ticket, <a href='/docs/v2/writing-algorithms/trading-and-orders/order-management/transaction-manager#02-Get-a-Single-Order-Ticket'>get it from the transaction manager</a>. The <code>Update</code> method returns an <a href='/docs/v2/writing-algorithms/trading-and-orders/order-management/order-tickets#06-Order-Response'>OrderResponse</a> to signal the success or failure of the update request.</p>


<div class="section-example-container">
<pre class="csharp">// Create a new order and save the order ticket
var tickets = ComboLegLimitOrder(legs, 1);

// Update the leg orders
foreach (var ticket in tickets)
{
    var direction = Math.Sign(ticket.Quantity);
    var response = ticket.Update(new UpdateOrderFields() 
    {
        Quantity = 2 * direction,
        LimitPrice = ticket.Get(OrderField.LimitPrice) + 0.01m * direction,
        Tag = $"Update #{ticket.UpdateRequests.Count + 1}"
    }); 

    // Check if the update was successful
    if (response.IsSuccess) 
    {
        Debug($"Order updated successfully for {ticket.Symbol}");
    }
}</pre>
<pre class="python"># Create a new order and save the order tickets
tickets = self.ComboLegLimitOrder(legs, 1)

# Update the leg orders
for ticket in tickets:
    direction = np.sign(ticket.Quantity)
    update_settings = UpdateOrderFields()
    update_settings.Quantity = 2 * direction
    update_settings.LimitPrice = ticket.Get(OrderField.LimitPrice) + 0.01 * direction
    update_settings.Tag = f"Update #{len(ticket.UpdateRequests) + 1}"
    response = ticket.Update(update_settings)

    # Check if the update was successful
    if response.IsSuccess:
        self.Debug(f"Order updated successfully for {ticket.Symbol}")</pre>
</div>

<?
$supportedMethods = array("UpdateLimitPrice", "UpdateQuantity", "UpdateTag");
include(DOCS_RESOURCES."/order-types/update-individual-fields.php");
include(DOCS_RESOURCES."/order-types/update-requests.html");
?>