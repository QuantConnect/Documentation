// In Initialize
FutureSymbol = future.Symbol;

// In OnData(Slice slice)
FuturesChain chain;
if (slice.FuturesChains.TryGetValue(FutureSymbol, out chain))
{
    var underlying = chain.Underlying;
    var contracts = chain.Contracts.Value; 
    foreach (var contract in contracts)
    {
        //
    }
}