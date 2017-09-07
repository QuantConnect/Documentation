# In Initialize
self.OptionSymol = option.Symbol;

// In OnData
chain = slice.OptionChains[self.OptionSymbol].Value
# we sort the contracts to find at the money (ATM) contract with farthest expiration
contracts = sorted(sorted(chain, \
    key = lambda x: abs(chain.Underlying.Price - x.Strike)), \
    key = lambda x: x.Expiry, reverse=True)