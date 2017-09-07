# Set brokerage model using helper methods:
self.SetBrokerageModel(BrokerageName.FxcmBrokerage) # Defaults to margin account
self.SetBrokerageModel(BrokerageName.TradierBrokerage, \ 
                       AccountType.Cash) # Or override account type

# Supported brokerage names:
BrokerageName.FxcmBrokerage
             .OandaBrokerage
             .TradierBrokerage
             .InteractiveBrokersBrokerage