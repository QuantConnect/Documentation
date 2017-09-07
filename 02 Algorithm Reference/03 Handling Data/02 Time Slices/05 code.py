#Access data in a Slice object: 
# 1. Grouped Properties: Ticks, Bars, Delistings, SymbolChangedEvents, Splits and Dividends
bars = slice.Bars; # e.g. bars["IBM"].Open

# 2. Dynamic String / Symbol Indexer:
bar = slice["IBM"] # e.g. bar.Open - TradeBar properties OHLCV
spyTickList = data["SPY"] # Tick assets return a list of Tick objects.