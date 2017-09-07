# Override the base class event handler for order events
def OnOrderEvent(self, orderEvent):
    order = self.Transactions.GetOrderById(orderEvent.OrderId)
    self.Debug("{0}: {1}: {2}".format(self.Time, order.Type, orderEvent))