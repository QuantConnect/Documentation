<p>The benefit of the <code>Symbol</code> class is that it always maps to the same security, regardless of their trading ticker. When a company changes its trading ticker, LEAN sends a <code>SymbolChangedEvent</code> to the <code class="csharp">OnData</code><code class="python">on_data</code> method. <code>SymbolChangedEvent</code> objects have the following properties:</p>

<div data-tree='QuantConnect.Data.Market.SymbolChangedEvent'></div>

<p>To get the <code>SymbolChangedEvent</code> objects, index the <code class="csharp">SymbolChangedEvents</code><code class="python">symbol_changed_events</code> object with the security <code  class="csharp">Symbol</code><code class="python">symbol</code>. The <code class="csharp">SymbolChangedEvents</code><code class="python">symbol_changed_events</code> object may not contain data for your <code  class="csharp">Symbol</code><code class="python">symbol</code>. To avoid issues, check if the <code class="csharp">SymbolChangedEvents</code><code class="python">symbol_changed_events</code> object contains data for your security before you index it with the security <code  class="csharp">Symbol</code><code class="python">symbol</code>.</p>

<div class='section-example-container'>
    <pre class='csharp'>public override void OnData(Slice slice)
{
    if (slice.SymbolChangedEvents.ContainsKey(_symbol))
    {
        var symbolChangedEvent = slice.SymbolChangedEvents[_symbol];
    }
}

public override void OnSymbolChangedEvents(SymbolChangedEvents symbolChangedEvents)
{
    if (symbolChangedEvents.ContainsKey(_symbol))
    {
        var symbolChangedEvent = symbolChangedEvents[_symbol];
    }
}
</pre>
    <pre class='python'>def on_data(self, slice: Slice) -> None:
    symbol_changed_event = slice.symbol_changed_events.get(self.symbol)
    if symbol_changed_event:
        pass

def on_symbol_changed_events(self, symbol_changed_events: SymbolChangedEvents) -> None:
    symbol_changed_event = symbol_changed_events.get(self.symbol)
    if symbol_changed_event:
        pass</pre>
</div>

<p>You can also iterate through the <code class="csharp">SymbolChangedEvents</code><code class="python">symbol_changed_events</code> dictionary. The keys of the dictionary are the <code>Symbol</code> objects and the values are the <code>SymbolChangedEvent</code> objects.</p>
<div class='section-example-container'>
    <pre class='csharp'>public override void OnData(Slice slice)
{
    foreach (var kvp in slice.SymbolChangedEvents)
    {
        var symbol = kvp.Key;
        var symbolChangedEvent = kvp.Value;
    }
}

public override void OnSymbolChangedEvents(SymbolChangedEvents symbolChangedEvents)
{
    foreach (var kvp in symbolChangedEvents)
    {
        var symbol = kvp.Key;
        var symbolChangedEvent = kvp.Value;
    }
}</pre>
    <pre class='python'>def on_data(self, slice: Slice) -> None:
    for symbol, symbol_changed_event in slice.symbol_changed_events.items():
        pass


def on_symbol_changed_events(self, symbol_changed_events: SymbolChangedEvents) -> None:
    for symbol, symbol_changed_event in symbol_changed_events.items():
        pass</pre>
</div>

<p>If you have an open order for a security when they change their ticker, LEAN cancels your order. To keep your order, in the <code class="csharp">OnOrderEvent</code><code class="python">on_order_event</code> method, get the quantity and <code>Symbol</code> of the cancelled order and submit a new order.</p> 

<div class='section-example-container'>
    <pre class='csharp'>public override void OnOrderEvent(OrderEvent orderEvent)
{
    if (orderEvent.Status == OrderStatus.Canceled)
    {
        var ticket = Transactions.GetOrderTicket(orderEvent.OrderId);
        if (ticket.Tag.Contains("symbol changed event"))
        {
            Transactions.AddOrder(ticket.SubmitRequest);
        }
    } 
}</pre>
        <pre class='python'>def on_order_event(self, order_event: OrderEvent) -&gt; None:
    if order_event.status == OrderStatus.CANCELED:
        ticket = self.transactions.get_order_ticket(order_event.order_id)
        if "symbol changed event" in ticket.tag:
            self.transactions.add_order(ticket.submit_request)</pre>
    </div>

<p>LEAN stores the data for ticker changes in map files. To view some example map files, see the <a rel='nofollow' target='_blank' href='<?=$mapFilesLink?>'>LEAN GitHub repository</a>.</p>
