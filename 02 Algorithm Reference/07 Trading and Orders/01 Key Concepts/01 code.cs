// Creating an Order:
OrderTicket limitOrderTicket = LimitOrder("SPY", 100, 205);

// Updating an Order:
limitOrderTicket.Update(new UpdateOrderFields{LimitPrice = 207.50};

// Cancel an Order:
limitOrderTicket.Cancel();