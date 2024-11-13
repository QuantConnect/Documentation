<p>As the state of your order updates over time, your order ticket automatically updates. To track an order, you can check any of the preceding order ticket properties.</p>

<p>To get an order field, call the <code class="csharp">Get</code><code class="python">get</code> method with an <code>OrderField</code>.</p> 

<div class="section-example-container">
<pre class="csharp">private Symbol _symbol;
private OrderTicket _ticket;

public override void Initialize()
{
    _symbol = AddEquity("SPY").Symbol;
}

public override void OnData(Slice slice)
{
    // Place order if not invested and save the order ticket for later retrival.
    if (!Portfolio.Invested && slice.Bars.TryGetValue(_symbol, out var bar))
    {
        _ticket = LimitOrder(_symbol, 10, bar.Close);
    }
    // Get the limit price if the order is filled.
    else if (Portfolio.Invested)
    {
        var limitPrice = _ticket.Get(OrderField.LimitPrice);
    }
}</pre>
<pre class="python">def initialize(self) -&gt; None:
    self._symbol = self.add_equity("SPY").symbol
    self._ticket = None
    
def on_data(self, slice: Slice) -&gt; None:
    # Place order if not invested and save the order ticket for later retrival.
    if not self.portfolio.invested and self._symbol in slice.bars:
        self._ticket = self.limit_order(self._symbol, 10, slice.bars[self._symbol].close)
    # Get the limit price if the order is filled.
    elif self.portfolio.invested:
        limit_price = self._ticket.get(OrderField.LIMIT_PRICE)</pre>
</div>

<p>The <code>OrderField</code> enumeration has the following members:</p>
<div data-tree="QuantConnect.Orders.OrderField"></div>


<p>In addition to using order tickets to track orders, you can receive <a href='/docs/v2/writing-algorithms/trading-and-orders/order-events'>order events</a> through the <code class="csharp">OnOrderEvent</code><code class="python">on_order_event</code> event handler.</p>
