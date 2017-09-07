// Override the base class event handler for order events
public override void OnOrderEvent(OrderEvent orderEvent)
{
    var order = Transactions.GetOrderById(orderEvent.OrderId);
    Console.WriteLine("{0}: {1}: {2}", Time, order.Type, orderEvent);
}