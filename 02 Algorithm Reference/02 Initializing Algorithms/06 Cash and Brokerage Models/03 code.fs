(* In Initialize method, set the brokerage models *)
this.SetBrokerageModel(BrokerageName.InteractiveBrokersBrokerage, AccountType.Cash);
this.AddEquity("SPY", Resolution.Second) |> ignore
(* Then if required, you can set specific margin models for your secutities *)
this.Securities.Item("SPY").MarginModel <- new PatternDayTradingMarginModel()