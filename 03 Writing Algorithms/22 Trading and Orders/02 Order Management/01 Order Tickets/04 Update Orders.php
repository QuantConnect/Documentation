<p>To update an order, use its <code>OrderTicket</code>. You can update other orders until they are filled or the brokerage prevents modifications. You just can't update orders during <a href='/docs/v2/writing-algorithms/historical-data/warm-up-periods'>warm up</a> and <a href='/docs/v2/writing-algorithms/initialization'>initialization</a>.</p>

<h4>Updatable Properties</h4>

<p>The specific properties you can update depends on the order type. The following table shows the properties you can update for each order type.</p>

<table class="qc-table table  table-condensed" id='updatable-properties'>
<thead>
<tr>
<th width="25%" rowspan="2">Order Type</th>
<th colspan="5">Updatable Properties</th> 
</tr>
<tr>
<th width="15%" style='text-align: center'>Tag</th> 
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

<style>
#updatable-properties td:nth-child(2), 
#updatable-properties th:nth-child(2), 
#updatable-properties td:nth-child(3), 
#updatable-properties th:nth-child(3), 
#updatable-properties td:nth-child(4), 
#updatable-properties th:nth-child(4), 
#updatable-properties td:nth-child(5), 
#updatable-properties th:nth-child(5), 
#updatable-properties td:last-child, 
#updatable-properties th:last-child {
    text-align: center;
}
</style>

<h4>Update Methods</h4>

<p>To update an order, pass an <code>UpdateOrderFields</code> object to the <code class="csharp">Update</code><code class="python">update</code> method. The method returns an <code>OrderResponse</code> to signal the success or failure of the update request. 
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
ticket = self.limit_order("SPY", 100, 221.05, False, "New SPY trade")

# Update the order tag and limit price
updateSettings = UpdateOrderFields()
updateSettings.limit_price = 222.00
updateSettings.tag = "Limit Price Updated for SPY Trade"
response = ticket.update(updateSettings)

# Check the OrderResponse
if response.is_success:
    self.debug("Order updated successfully")</pre>
</div>

<?
$supportedMethods = array("UpdateLimitPrice", "UpdateQuantity", "UpdateStopPrice", "UpdateTag");
include(DOCS_RESOURCES."/order-types/update-individual-fields.php");
?>

<h4>Update Order Requests</h4>

<?
include(DOCS_RESOURCES."/order-types/update-requests.html");
?>

<h4>Workaround for Brokerages That Don’t Support Updates</h4>

<p>Not all brokerages fully support order updates. To check what functionality your brokerage supports for order updates, see the <span class='page-section-name'>Orders</span> section of the documentation for your <a href="/docs/v2/writing-algorithms/reality-modeling/brokerages/supported-models">brokerage model</a>. If your brokerage doesn't support order updates and you want to update an order, <a href='/docs/v2/writing-algorithms/trading-and-orders/order-management/order-tickets#05-Cancel-Orders'>cancel the order</a>. When you get an <a href='/docs/v2/writing-algorithms/trading-and-orders/order-events'>order event</a> that confirms the order is no longer active, place a new order.</p>

<div class="section-example-container">
<pre class="csharp">public override void OnData(Slice slice)
{
    // Cancel the order
    _ticket.Cancel();
}

public override void OnOrderEvent(OrderEvent orderEvent)
{
    if (_ticket != null 
        && orderEvent.OrderId == _ticket.OrderId 
        && orderEvent.Status == OrderStatus.Canceled)
    {
        // Place a new order
        var quantity = _ticket.Quantity - _ticket.QuantityFilled;
        var limitPrice = Securities[_ticket.Symbol].Price + 1;
        _ticket = LimitOrder(_ticket.Symbol, quantity, limitPrice);
    }
}</pre>
<pre class="python">def on_data(self, slice: Slice) -> None:
    # Cancel the order
    self.ticket.Cancel()

def on_order_event(self, orderEvent: OrderEvent) -> None:
    if self.ticket is not None \
        and orderEvent.OrderId == self.ticket.OrderId \
        and orderEvent.Status == OrderStatus.Canceled:
        # Place a new order
        quantity = self.ticket.Quantity - self.ticket.QuantityFilled
        limit_price = self.Securities[self.ticket.Symbol].Price + 1
        self.ticket = self.LimitOrder(self.ticket.Symbol, quantity, limit_price)</pre>
</div>
