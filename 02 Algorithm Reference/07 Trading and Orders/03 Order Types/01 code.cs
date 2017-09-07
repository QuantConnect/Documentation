// Various order types:
// Fill a market order immediately (before moving to next line of code)
var newTicket = MarketOrder("IBM", 100);

// Place a long limit order with limit price less than current price
var newTicket = LimitOrder("IBM", 100, lastClose * .999m);

// Closing out a short position; long stop above the last close price
var stopPrice = close * 1.0025m;
var newTicket = StopMarketOrder("IBM", 100, stopPrice);

// Closing out a long position on market drop
var stopPrice = close * .9975m;
var newTicket = StopMarketOrder("IBM", -100, stopPrice);

// Limit order trigger on reaching the stop price
var newTicket = StopLimitOrder("IBM", 100, stopPrice, limitPrice);

// Market on Open/Close:
var newTicket = MarketOnCloseOrder("IBM", 100);
var newTicket = MarketOnOpenOrder("IBM", 100);