# Creating an Order:
limitOrderTicket = self.LimitOrder("SPY", 100, 205)

# Updating an Order:
updateOrderFields = UpdateOrderFields()
updateOrderFields.LimitPrice = decimal.Decimal(207.50)
limitOrderTicket.Update(updateOrderFields)

# Cancel an Order:
limitOrderTicket.Cancel()