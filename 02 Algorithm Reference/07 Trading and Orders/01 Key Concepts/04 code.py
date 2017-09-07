# Cancel all open orders from SPY
cancelledOrders = self.Transactions.CancelOpenOrders("SPY")

# Cancel order #10
cancelledOrder = self.Transactions.CancelOrder(10)

# Get open orders
openOrders = self.Transactions.GetOpenOrders()

# Get open orders from SPY
openOrders = self.Transactions.GetOpenOrders("SPY")

# Get open order #10
openOrder = self.Transactions.GetOrderById(10)

# Get all orders
orders = self.Transactions.GetOrders()

# Get order ticket #10
orderTicket = self.Transactions.GetOrderTicket(10)

# Get all orders tickets
openOrderTickets = self.Transactions.GetOrderTickets()