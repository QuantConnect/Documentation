#Send yourself an email on live trade executions
self.Notify.Email("myEmailAddress@gmail.com", "Live Trade Executed", "Bought 100 Shares of AAPL")

# Example sending notification with price with slice obect from inside OnData
# Try not to do this; it'll send hundreds of sms!
def OnData(self, slice):
  self.Notify.Sms("+1234567890", "BTCUSD Price at " + str(slice["BTCUSD"].Close))
