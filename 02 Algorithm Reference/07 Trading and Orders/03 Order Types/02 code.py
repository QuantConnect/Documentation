# Various order types:
# Fill a market order immediately (before moving to next line of code)
newTicket = self.MarketOrder("IBM", 100)

# Place a long limit order with limit price less than current price
newTicket = self.LimitOrder("IBM", 100, lastClose * decimal.Decimal(.999))

# Closing out a short position; long stop above the last close price
stopPrice = close * decimal.Decimal(1.0025)
newTicket = self.StopMarketOrder("IBM", 100, stopPrice)

# Closing out a long position on market drop
stopPrice = close * decimal.Decimal(.9975)
newTicket = self.StopMarketOrder("IBM", -100, stopPrice)

# Limit order trigger on reaching the stop price
newTicket = self.StopLimitOrder("IBM", 100, stopPrice, limitPrice)

# Market on Open/Close:
newTicket = self.MarketOnCloseOrder("IBM", 100)
newTicket = self.MarketOnOpenOrder("IBM", 100)