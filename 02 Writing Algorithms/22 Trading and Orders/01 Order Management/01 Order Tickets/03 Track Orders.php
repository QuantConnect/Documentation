<p>As the state or your order updates over time, your order ticket automatically updates. To track an order, you can check any of the preceding order ticket properties.</p>

<p>To get an order field, call the <code>Get</code> method with an <code>OrderField</code>.</p> 

<div class="section-example-container">
<pre class="csharp">var limitPrice = orderTicket.Get(OrderField.LimitPrice);</pre>
<pre class="python">limit_price = order_ticket.Get(OrderField.LimitPrice)</pre>
</div>

<?php echo file_get_contents(DOCS_RESOURCES."/enumerations/order_field.html"); ?>


<p>To get most recent order response, call the <code>GetMostRecentOrderResponse</code> method.</p>
<div class="section-example-container">
<pre class="csharp">var response = orderTicket.GetMostRecentOrderResponse();</pre>
<pre class="python">response = order_ticket.GetMostRecentOrderResponse()</pre>
</div>
<p>This method returns an <code>OrderResponse</code> object, which have the following attributes:</p>
<div data-tree='QuantConnect.Orders.OrderResponse'></div>


<p>Instead of using order tickets to track orders, you can receive <a href='/docs/v2/writing-algorithms/trading-and-orders/order-events'>order events</a> through the <code>OnOrderEvent</code> event handler.</p>
