// Cancel all open orders from SPY
List<OrderTicket> cancelledOrders = Transactions.CancelOpenOrders("SPY")

// Cancel order #10
OrderTicket cancelledOrder = Transactions.CancelOrder(10);

// Get open orders
List<Order> openOrders = Transactions.GetOpenOrders();
List<Order> openOrders = Transactions
    .GetOrders(x => x.Status.IsOpen()).ToList();

// Get open orders from SPY
List<Order> openOrders = Transactions.GetOpenOrders("SPY");
List<Order> openOrders = Transactions
    .GetOrders(x => x.Status.IsOpen() && x.Symbol == "SPY").ToList();

// Get open order #10
Order openOrder = Transactions.GetOrderById(10);

// Get order ticket #10
OrderTicket orderTicket = Transactions.GetOrderTicket(10);

// Get open orders tickets from SPY
IEnumerable<OrderTicket> openOrderTickets = Transactions
    .GetOrderTickets(x => x.Status.IsOpen() && x.Symbol == "SPY");