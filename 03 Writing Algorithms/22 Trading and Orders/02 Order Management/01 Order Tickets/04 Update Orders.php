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
<pre class="csharp">private Symbol _symbol;
private OrderTicket _ticket;

public override void Initialize()
{
    _symbol = AddEquity("SPY").Symbol;
}

public override void OnData(Slice slice)
{
    // Place order if not order yet and save the order ticket for later retrival.
    if (_ticket == null && slice.Bars.TryGetValue(_symbol, out var bar))
    {
        _ticket = LimitOrder(_symbol, 10, bar.Close * 0.98m);
    }
    // If order is placed, update the limit price to be 90% of the orginal.
    else if (_ticket != null && _ticket.Status == OrderStatus.Submitted)
    {
        // Update the order tag and limit price
        var response = _ticket.Update(new UpdateOrderFields()
        { 
            Tag = "Our New Tag for SPY Trade",
            LimitPrice = _ticket.Get(OrderField.LimitPrice * 0.9m)
        });

        // Check if the update request is successfully submitted to the broker.
        // Note that it may not represent the order is updated successfully: during the order updating process, it may be filled or canceled.
        if (response.IsSuccess)
        { 
            Debug("Order update request is submitted successfully");
        }
    }
}</pre>
<pre class="python">def initialize(self) -&gt; None:
    self._symbol = self.add_equity("SPY").symbol
    self._ticket = None
    
def on_data(self, slice: Slice) -&gt; None:
    # Place order if not invested and save the order ticket for later retrival.
    if not self._ticket and self._symbol in slice.bars:
        self._ticket = self.limit_order("SPY", 100, slice.bars[self._symbol].close * 0.98)
    # If order is placed, update the limit price to be 90% of the orginal.
    elif self._ticket != None and self._ticket.status == OrderStatus.SUBMITTED:
        # Update the order tag and limit price
        update_settings = UpdateOrderFields()
        update_settings.limit_price = self._ticket.get(OrderField.LIMIT_PRICE) * 0.9
        update_settings.tag = "Limit Price Updated for SPY Trade"
        response = self._ticket.update(update_settings)

        # Check if the update request is successfully submitted to the broker.
        # Note that it may not represent the order is updated successfully: during the order updating process, it may be filled or canceled.
        if response.is_success:
            self.debug("Order update request is submitted successfully")</pre>
</div>

<?
$supportedMethods = array("UpdateLimitPrice", "UpdateQuantity", "UpdateStopPrice", "UpdateTag");
include(DOCS_RESOURCES."/order-types/update-individual-fields.php");
?>

<h4>Update Order Requests</h4>

<?
include(DOCS_RESOURCES."/order-types/update-requests.html");
?>

<h4>Workaround for Brokerages That Don't Support Updates</h4>

<p>Not all brokerages fully support order updates. To check what functionality your brokerage supports for order updates, see the <span class='page-section-name'>Orders</span> section of the documentation for your <a href="/docs/v2/writing-algorithms/reality-modeling/brokerages/supported-models">brokerage model</a>. If your brokerage doesn't support order updates and you want to update an order, <a href='/docs/v2/writing-algorithms/trading-and-orders/order-management/order-tickets#05-Cancel-Orders'>cancel the order</a>. When you get an <a href='/docs/v2/writing-algorithms/trading-and-orders/order-events'>order event</a> that confirms the order is no longer active, place a new order.</p>

<div class="section-example-container">
<pre class="csharp">private Symbol _symbol;
private OrderTicket _ticket;

public override void Initialize()
{
    _symbol = AddEquity("SPY").Symbol;
}

public override void OnData(Slice slice)
{
    // Place order if not order yet and save the order ticket for later retrival.
    if (_ticket == null && slice.Bars.TryGetValue(_symbol, out var bar))
    {
        _ticket = LimitOrder(_symbol, 10, bar.Close);
    }
    // If order is placed, cancel the order and place a new one as substituent.
    else if (_ticket != null && _ticket.Status == OrderStatus.Submitted)
    {
        // Cancel the order
        _ticket.Cancel();
    }
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
<pre class="python">def initialize(self) -&gt; None:
    self._symbol = self.add_equity("SPY").symbol
    self._ticket = None
    
def on_data(self, slice: Slice) -&gt; None:
    # Place order if not invested and save the order ticket for later retrival.
    if not self._ticket and self._symbol in slice.bars:
        self._ticket = self.limit_order("SPY", 100, slice.bars[self._symbol].close)
    # If order is placed, cancel the order and place a new one as substituent.
    elif self._ticket != None and self._ticket.status == OrderStatus.SUBMITTED:
        self._ticket.cancel()

def on_order_event(self, order_event: OrderEvent) -&gt; None:
    if (self._ticket and order_event.order_id == self._ticket.order_id and
        order_event.status == OrderStatus.CANCELED):
        # Place a new order
        quantity = self._ticket.quantity - self._ticket.quantity_filled
        limit_price = self.securities[self._ticket.symbol].price + 1
        self._ticket = self.limit_order(self._ticket.symbol, quantity, limit_price)</pre>
</div>
