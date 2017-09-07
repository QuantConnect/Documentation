#Brokerage model and account type:
self.SetBrokerageModel(BrokerageName.InterativeBrokersBrokerage, AccountType.Cash)

//Add securities and if required set custom margin models 
spy = self.AddEquity("SPY") # Default to minute bars
spy.MarginModel = PatternDayTradingMarginModel()