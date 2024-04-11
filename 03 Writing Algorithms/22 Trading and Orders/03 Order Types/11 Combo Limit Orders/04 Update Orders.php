<p>You can update the quantity, limit price, and tag of the limit orders in each leg until the combo order fills or the brokerage prevents modifications. To update an order, pass an <code>UpdateOrderFields</code> object to the <code>Update</code> method on the <code>OrderTicket</code>. If you don't have the order ticket, <a href='/docs/v2/writing-algorithms/trading-and-orders/order-management/transaction-manager#02-Get-a-Single-Order-Ticket'>get it from the transaction manager</a>. To update the limit price of the combo order, you only need to update the limit price of one of the leg orders. The <code>Update</code> method returns an <a href='/docs/v2/writing-algorithms/trading-and-orders/order-management/order-tickets#06-Order-Response'>OrderResponse</a> to signal the success or failure of the update request.</p>


<div class="section-example-container">
<pre class="csharp">// Create a new order and save the order ticket
var tickets = ComboLimitOrder(legs, quantity: 1, limitPrice: limitPrice);

// Update the leg orders
foreach (var ticket in tickets)
{
    var response = ticket.Update(new UpdateOrderFields() 
    {
        Quantity = 2 * Math.Sign(ticket.Quantity),
        LimitPrice = ticket.Get(OrderField.LimitPrice) + 0.01m,
        Tag = $"Update #{ticket.UpdateRequests.Count + 1}"
    }); 

    // Check if the update was successful
    if (response.IsSuccess) 
    {
        Debug($"Order updated successfully for {ticket.Symbol}");
    }
}</pre>
<pre class="python"># Create a new order and save the order tickets
tickets = self.combo_limit_order(legs, 1, limit_price)

# Update the leg orders
for ticket in tickets:
    update_settings = UpdateOrderFields()
    update_settings.quantity = 2 * np.sign(ticket.quantity)
    update_settings.limit_price = ticket.get(OrderField.limit_price) + 0.01
    update_settings.tag = f"Update #{len(ticket.update_requests) + 1}"
    response = ticket.update(update_settings)

    # Check if the update was successful
    if response.is_success:
        self.debug(f"Order updated successfully for {ticket.symbol}")</pre>
</div>

<?
$supportedMethods = array("UpdateLimitPrice", "UpdateQuantity", "UpdateTag");
include(DOCS_RESOURCES."/order-types/update-individual-fields.php");
include(DOCS_RESOURCES."/order-types/update-requests.html");
?>