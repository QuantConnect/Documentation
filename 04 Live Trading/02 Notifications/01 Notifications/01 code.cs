//Send yourself an email on live trade executions
Notify.Email("myEmailAddress@gmail.com", "Live Trade Executed", "Bought 100 Shares of AAPL");
Notify.Sms("+1 1234 5678", "SPY Trade at " + data.Bars["SPY"].Close);
