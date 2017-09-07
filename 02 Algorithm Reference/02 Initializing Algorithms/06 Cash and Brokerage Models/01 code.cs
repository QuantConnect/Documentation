//Brokerage model and account type:
SetBrokerageModel(BrokerageName.TradierBrokerage, AccountType.Margin);

//Add securities and if required set custom margin models 
var spy = AddEquity("SPY"); //Defaults to minute bars.
spy.MarginModel = new PatternDayTradingMarginModel();