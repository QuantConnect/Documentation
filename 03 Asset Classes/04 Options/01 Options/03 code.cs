// In Initialize
OptionSymol = option.Symbol;

// In OnData
OptionChain chain;
if (slice.OptionChains.TryGetValue(OptionSymbol, out chain))
{
    // we find at the money (ATM) put contract with farthest expiration
    var atmContract = chain
        .OrderByDescending(x => x.Expiry)
        .ThenBy(x => Math.Abs(chain.Underlying.Price - x.Strike))
        .ThenByDescending(x => x.Right)
        .FirstOrDefault();
}