# In Initialize
self.FutureSymbol = future.Symbol;

// In OnData(self, slice)
chain = slice.FuturesChains[self.FutureSymbol]
underlying = chain.Underlying
contracts = chain.Contracts.Value
for contract in contracts:
    pass