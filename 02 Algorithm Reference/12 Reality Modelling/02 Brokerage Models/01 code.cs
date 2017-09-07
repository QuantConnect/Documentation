// Set brokerage model using helper methods:
SetBrokerageModel(BrokerageName.FxcmBrokerage); // Defaults to margin account
SetBrokerageModel(BrokerageName.TradierBrokerage, AccountType.Cash); //Or override account type

// Supported brokerage names:
BrokerageName.FxcmBrokerage
             .OandaBrokerage
             .TradierBrokerage
             .InteractiveBrokersBrokerage